<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OrderResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now       = Carbon::now();
        $thisMonth = Order::whereMonth('created_at', $now->month)
                          ->whereYear('created_at', $now->year);
        $lastMonth = Order::whereMonth('created_at', $now->copy()->subMonth()->month)
                          ->whereYear('created_at', $now->copy()->subMonth()->year);

        $revenueThis = (clone $thisMonth)->where('status', '!=', 'cancelled')->sum('total');
        $revenueLast = (clone $lastMonth)->where('status', '!=', 'cancelled')->sum('total');
        $revenueGrowth = $revenueLast > 0
            ? round((($revenueThis - $revenueLast) / $revenueLast) * 100, 1)
            : null;

        // Orders by status
        $ordersByStatus = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Revenue last 7 days
        $revenueChart = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::now()->subDays($daysAgo);
            return [
                'date'    => $date->format('M d'),
                'revenue' => (float) Order::whereDate('created_at', $date)
                    ->where('status', '!=', 'cancelled')
                    ->sum('total'),
            ];
        });

        // Top 5 products by order count
        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get(['id', 'name', 'image', 'price', 'sale_price'])
            ->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'image'       => $p->image,
                'price'       => (float) ($p->sale_price ?? $p->price),
                'order_count' => $p->order_items_count,
            ]);

        return response()->json([
            'stats' => [
                'total_products'   => Product::count(),
                'total_categories' => Category::count(),
                'total_orders'     => Order::count(),
                'total_users'      => User::where('is_admin', false)->count(),
                'revenue'          => (float) Order::where('status', '!=', 'cancelled')->sum('total'),
                'revenue_this_month' => (float) $revenueThis,
                'revenue_growth'   => $revenueGrowth,
                'pending_orders'   => Order::where('status', 'pending')->count(),
                'paid_orders'      => Order::where('payment_status', 'paid')->count(),
            ],
            'orders_by_status' => $ordersByStatus,
            'revenue_chart'    => $revenueChart,
            'top_products'     => $topProducts,
            'recent_orders'    => OrderResource::collection(
                Order::with('user')->latest()->take(10)->get()
            ),
        ]);
    }
}
