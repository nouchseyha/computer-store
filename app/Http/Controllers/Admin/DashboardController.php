<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products'   => Product::count(),
            'total_categories' => Category::count(),
            'total_orders'     => Order::count(),
            'total_users'      => User::where('is_admin', false)->count(),
            'revenue'          => Order::where('status', '!=', 'cancelled')->sum('total'),
            'pending_orders'   => Order::where('status', 'pending')->count(),
        ];
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
