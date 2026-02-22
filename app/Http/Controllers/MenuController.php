<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::active()->ordered()->get();
        $products = Product::with('category')->active()->ordered()->get();

        return view('menu', compact('categories', 'products'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categories = Category::active()->ordered()->get();
        $products = Product::with('category')->active()->ordered()
            ->where('category_id', $category->id)->get();

        return view('menu', compact('categories', 'products', 'category'));
    }
}
