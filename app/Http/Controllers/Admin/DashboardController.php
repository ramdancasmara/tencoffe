<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
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

        $perPage = $request->input('per_page', 10);
        $query = Order::with('items')->latest();

        if ($perPage === 'all') {
            $recentOrders = $query->get();
        } else {
            $recentOrders = $query->paginate((int) $perPage)->withQueryString();
        }

        return view('admin.dashboard', compact(
            'todayOrders', 'todayRevenue', 'monthRevenue',
            'totalProducts', 'totalCategories', 'pendingOrders', 'recentOrders', 'perPage'
        ));
    }
}
