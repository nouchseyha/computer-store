<?php

namespace App\Http\Controllers;

use App\Helpers\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::get();
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        return view('checkout.index');
    }

    public function store(Request $request)
    {
        $cart = Cart::get();
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_email'   => 'required|email',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city'    => 'required|string|max:100',
            'payment_method'   => 'required|in:cod,bakong_khqr',
        ]);

        $subtotal = Cart::total();
        $shipping = 0;
        $total    = $subtotal;

        $order = Order::create([
            'user_id'          => auth()->id(),
            'order_number'     => Order::generateOrderNumber(),
            'status'           => 'pending',
            'subtotal'         => $subtotal,
            'shipping'         => $shipping,
            'total'            => $total,
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'unpaid',
            'shipping_name'    => $request->shipping_name,
            'shipping_email'   => $request->shipping_email,
            'shipping_phone'   => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city'    => $request->shipping_city,
            'notes'            => $request->notes,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item['id'],
                'product_name' => $item['name'],
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
                'subtotal'     => $item['price'] * $item['quantity'],
            ]);
        }

        Cart::clear();

        if ($request->payment_method === 'bakong_khqr') {
            $khqrString   = $this->generateKhqrString($order);
            $payment_hash = md5($khqrString);
            $order->update(['payment_hash' => $payment_hash]);
            $this->sendTelegramNotification($order, "⏳ [KHQR] Payment Pending");
            return view('checkout.payment_waiting', compact('order', 'khqrString'));
        }

        $title = $request->payment_method === 'bank_transfer' ? "🏦 [Bank Transfer] New Order" : "📦 [COD] New Order";
        $this->sendTelegramNotification($order, $title);

        return redirect()->route('checkout.success', $order->order_number)
            ->with('success', 'Order placed successfully!');
    }

    /**
     * Generate a valid Bakong KHQR string (EMVCo / NBC standard).
     *
     * Structure reference:
     *   https://www.nbc.gov.kh/english/payment_system/bakong.php
     *
     * Tag 29 sub-fields:
     *   00 = globally unique identifier  → "bakong.nbc.gov.kh"
     *   01 = Bakong account ID           → e.g. "yourname@aba"
     */
    private function generateKhqrString(Order $order): string
    {
        $bakongId     = env('BAKONG_MERCHANT_ID', 'nouch_seyha@aba');
        $merchantName = env('BAKONG_MERCHANT_NAME', 'Nouch Seyha');
        $city         = env('BAKONG_CITY', 'Phnom Penh');
        $amount       = number_format((float) $order->total, 2, '.', '');

        // Sub-tag 00: globally unique identifier (16 chars fixed)
        $guid    = "bakong.nbc.gov.kh";
        $sub00   = "00" . str_pad(strlen($guid), 2, '0', STR_PAD_LEFT) . $guid;

        // Sub-tag 01: Bakong account ID
        $sub01   = "01" . str_pad(strlen($bakongId), 2, '0', STR_PAD_LEFT) . $bakongId;

        $tag29Value  = $sub00 . $sub01;
        $merchantInfo = "29" . str_pad(strlen($tag29Value), 2, '0', STR_PAD_LEFT) . $tag29Value;

        // Build EMVCo payload
        $payload  = "000201";           // Tag 00: Payload Format Indicator
        $payload .= "010212";           // Tag 01: Point of Initiation (12 = dynamic)
        $payload .= $merchantInfo;      // Tag 29: Merchant Account Information
        $payload .= "52040000";         // Tag 52: Merchant Category Code
        $payload .= "5303840";          // Tag 53: Transaction Currency (840 = USD)
        $payload .= "54" . str_pad(strlen($amount), 2, '0', STR_PAD_LEFT) . $amount; // Tag 54: Amount
        $payload .= "5802KH";           // Tag 58: Country Code
        $payload .= "59" . str_pad(strlen($merchantName), 2, '0', STR_PAD_LEFT) . $merchantName; // Tag 59
        $payload .= "60" . str_pad(strlen($city), 2, '0', STR_PAD_LEFT) . $city;                 // Tag 60
        $payload .= "6304";             // Tag 63: CRC placeholder (4 chars follow)

        return $payload . $this->crc16($payload);
    }

    /**
     * CRC-16/CCITT-FALSE as required by EMVCo QR spec.
     */
    private function crc16(string $data): string
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $x    = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
            $x   ^= $x >> 4;
            $crc  = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
        }
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * AJAX endpoint — verify payment via Bakong API.
     */
    public function verifyPayment(Request $request, string $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        $token = env('BAKONG_TOKEN');

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Payment gateway not configured.']);
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => 'Bearer ' . $token])
                ->post('https://api-bakong.nbc.gov.kh/v1/check_transaction_by_md5', [
                    'md5' => $order->payment_hash,
                ]);

            $result = $response->json();

            if (isset($result['responseCode']) && $result['responseCode'] === 0) {
                $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                $this->sendTelegramNotification($order, "✅ [KHQR] Payment Received!");
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            Log::error('Bakong verify error: ' . $e->getMessage());
        }

        return response()->json(['success' => false]);
    }

    public function success(string $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        return view('checkout.success', compact('order'));
    }

    private function sendTelegramNotification(Order $order, string $title): void
    {
        $token  = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
        if (!$token || !$chatId) return;

        $message = "<b>{$title}</b>\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "🆔 <b>Order:</b> #{$order->order_number}\n"
            . "💰 <b>Total:</b> \${$order->total}\n"
            . "👤 <b>Customer:</b> {$order->shipping_name}\n"
            . "📞 <b>Phone:</b> <code>{$order->shipping_phone}</code>\n"
            . "📍 <b>City:</b> {$order->shipping_city}\n"
            . "🗒 <b>Notes:</b> " . ($order->notes ?? 'N/A');

        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $message,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
        }
    }
}
