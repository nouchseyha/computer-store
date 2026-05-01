<?php

namespace App\Livewire\Checkout;

use App\Helpers\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CheckoutPage extends Component
{
    public string $shipping_name    = '';
    public string $shipping_email   = '';
    public string $shipping_phone   = '';
    public string $shipping_city    = '';
    public string $shipping_address = '';
    public string $notes            = '';
    public string $payment_method   = 'cod';

    // KHQR state
    public ?string $khqrString    = null;
    public ?int    $orderId       = null;
    public ?string $orderNumber   = null;
    public float   $orderTotal    = 0;
    public string  $verifyStatus  = '';

    public function mount(): void
    {
        $user = auth()->user();
        if ($user) {
            $this->shipping_name    = $user->name    ?? '';
            $this->shipping_email   = $user->email   ?? '';
            $this->shipping_phone   = $user->phone   ?? '';
            $this->shipping_address = $user->address ?? '';
        }
    }

    protected function rules(): array
    {
        return [
            'shipping_name'    => 'required|string|max:255',
            'shipping_email'   => 'required|email',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city'    => 'required|string|max:100',
            'payment_method'   => 'required|in:cod,bakong_khqr',
        ];
    }

    public function placeOrder(): void
    {
        $this->validate();

        $cart = Cart::get();
        if (empty($cart)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

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
            'payment_method'   => $this->payment_method,
            'payment_status'   => 'unpaid',
            'shipping_name'    => $this->shipping_name,
            'shipping_email'   => $this->shipping_email,
            'shipping_phone'   => $this->shipping_phone,
            'shipping_address' => $this->shipping_address,
            'shipping_city'    => $this->shipping_city,
            'notes'            => $this->notes,
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

            // Decrement stock
            \App\Models\Product::where('id', $item['id'])
                ->where('stock', '>', 0)
                ->decrement('stock', $item['quantity']);
        }

        Cart::clear();
        $this->dispatch('cart-updated');

        if ($this->payment_method === 'bakong_khqr') {
            $this->khqrString  = $this->generateKhqrString($order);
            $this->orderId     = $order->id;
            $this->orderNumber = $order->order_number;
            $this->orderTotal  = (float) $order->total;
            $order->update(['payment_hash' => md5($this->khqrString)]);
            $this->sendTelegram($order, "⏳ [KHQR] Waiting for Payment");
            return;
        }

        $this->sendTelegram($order, "📦 [COD] New Order");
        $this->redirect(route('checkout.success', $order->order_number));
    }

    public function verifyKhqr(): void
    {
        $order = Order::find($this->orderId);
        if (!$order) return;

        $token = env('BAKONG_TOKEN');
        if (!$token) {
            $this->verifyStatus = 'error';
            return;
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
                // Send Telegram alert that payment was received
                $this->sendTelegram($order, "✅ [KHQR] Payment Confirmed!");
                $this->redirect(route('checkout.success', $order->order_number));
                return;
            }
        } catch (\Exception $e) {
            Log::error('Bakong verify error: ' . $e->getMessage());
        }

        $this->verifyStatus = 'not_found';
    }

    private function generateKhqrString(Order $order): string
    {
        $bakongId     = env('BAKONG_MERCHANT_ID', 'nouch_seyha@aba');
        $merchantName = env('BAKONG_MERCHANT_NAME', 'Nouch Seyha');
        $city         = env('BAKONG_CITY', 'Phnom Penh');
        $amount       = number_format((float) $order->total, 2, '.', '');

        // Tag 29: Merchant Account Information
        $guid   = "bakong.nbc.gov.kh";
        $sub00  = "00" . str_pad(strlen($guid), 2, '0', STR_PAD_LEFT) . $guid;
        $sub01  = "01" . str_pad(strlen($bakongId), 2, '0', STR_PAD_LEFT) . $bakongId;
        $tag29v = $sub00 . $sub01;
        $tag29  = "29" . str_pad(strlen($tag29v), 2, '0', STR_PAD_LEFT) . $tag29v;

        $p  = "000201";   // Payload Format Indicator
        $p .= "010212";   // Point of Initiation: 12 = dynamic (amount is fixed, cannot be changed)
        $p .= $tag29;     // Merchant Account Info
        $p .= "52040000"; // Merchant Category Code
        $p .= "5303840";  // Currency: 840 = USD
        $p .= "54" . str_pad(strlen($amount), 2, '0', STR_PAD_LEFT) . $amount; // Amount (locked)
        $p .= "5802KH";   // Country Code
        $p .= "59" . str_pad(strlen($merchantName), 2, '0', STR_PAD_LEFT) . $merchantName;
        $p .= "60" . str_pad(strlen($city), 2, '0', STR_PAD_LEFT) . $city;
        $p .= "6304";     // CRC placeholder

        return $p . $this->crc16($p);
    }

    private function crc16(string $data): string
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
            $x ^= $x >> 4;
            $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
        }
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    private function sendTelegram(Order $order, string $title): void
    {
        $token  = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
        if (!$token || !$chatId) return;

        $msg = "<b>{$title}</b>\n━━━━━━━━━━━━━━━━━━\n"
            . "🆔 <b>Order:</b> #{$order->order_number}\n"
            . "💰 <b>Total:</b> \${$order->total}\n"
            . "👤 <b>Customer:</b> {$order->shipping_name}\n"
            . "📞 <b>Phone:</b> <code>{$order->shipping_phone}</code>\n"
            . "📍 <b>City:</b> {$order->shipping_city}\n"
            . "🗒 <b>Notes:</b> " . ($order->notes ?? 'N/A');

        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId, 'text' => $msg, 'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout.checkout-page', [
            'cart'     => Cart::get(),
            'subtotal' => Cart::total(),
            'shipping' => 0,
        ]);
    }
}
