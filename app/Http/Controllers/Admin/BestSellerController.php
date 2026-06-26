<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class BestSellerController extends Controller
{
    public function index(Request $request)
    {
        $featured = Product::with('category')->active()->featured()->ordered()->get();

        $search = $request->input('search');
        $query = Product::with('category')->active()->where('is_featured', false);
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        $available = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.best-sellers.index', compact('featured', 'available', 'search'));
    }

    public function add(Product $product)
    {
        $maxOrder = Product::featured()->max('sort_order') ?? 0;
        $product->update(['is_featured' => true, 'sort_order' => $maxOrder + 1]);
        return back()->with('success', $product->name . ' berhasil ditambahkan ke Best Seller.');
    }

    public function remove(Product $product)
    {
        $product->update(['is_featured' => false]);
        return back()->with('success', $product->name . ' dihapus dari Best Seller.');
    }

    public function updateOrder(Request $request)
    {
        $orders = $request->input('orders', []);
        foreach ($orders as $id => $order) {
            Product::where('id', $id)->update(['sort_order' => (int) $order]);
        }
        return back()->with('success', 'Urutan Best Seller berhasil disimpan.');
    }
}
