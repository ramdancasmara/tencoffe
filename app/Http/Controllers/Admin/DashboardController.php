<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->whereIn('status', ['paid', 'completed'])->sum('total');
        $monthRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['paid', 'completed'])->sum('total');
        $totalProducts = Product::active()->count();
        $totalCategories = Category::active()->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $recentOrders = Order::with('items')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'todayOrders', 'todayRevenue', 'monthRevenue',
            'totalProducts', 'totalCategories', 'pendingOrders', 'recentOrders'
        ));
    }
}
