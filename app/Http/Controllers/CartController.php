<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $items = [];

        foreach ($cart as $key => $item) {
            if (str_starts_with($key, 'event_')) {
                $items[] = [
                    'key' => $key,
                    'name' => $item['name'] ?? 'Paket Spesial',
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'image' => $item['image'] ?? null,
                    'variant' => null,
                    'type' => 'event',
                ];
            } else {
                $parts = explode('_', $key);
                $productId = $parts[0];
                $variant = $parts[1] ?? null;
                $product = Product::find($productId);
                if (!$product) continue;

                $price = $product->getPriceForVariant($variant);

                $items[] = [
                    'key' => $key,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'image' => $product->image_url,
                    'variant' => $variant,
                    'type' => 'product',
                ];
            }
        }

        $total = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);

        return view('cart', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
            'variant' => 'nullable|in:hot,cold',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant;
        $quantity = $request->quantity ?? 1;

        $key = $product->id;
        if ($variant) $key .= '_' . $variant;
        $key = (string) $key;

        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'quantity' => $quantity,
                'variant' => $variant,
            ];
        }

        session(['cart' => $cart]);
        $count = collect($cart)->sum('quantity');

        $variantLabel = $variant ? ' (' . ucfirst($variant) . ')' : '';

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => $product->name . $variantLabel . ' ditambahkan ke keranjang',
        ]);
    }

    public function addEvent(Request $request)
    {
        $request->validate([
            'gallery_id' => 'required|exists:galleries,id',
            'quantity' => 'integer|min:1',
        ]);

        $gallery = Gallery::findOrFail($request->gallery_id);
        $quantity = $request->quantity ?? 1;

        $key = 'event_' . $gallery->id;
        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'quantity' => $quantity,
                'name' => $gallery->title ?? 'Paket Ramadhan',
                'price' => $gallery->price,
                'image' => $gallery->image_url,
                'type' => 'event',
            ];
        }

        session(['cart' => $cart]);
        $count = collect($cart)->sum('quantity');

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => ($gallery->title ?? 'Paket Spesial') . ' ditambahkan ke keranjang',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session('cart', []);

        if ($request->quantity <= 0) {
            unset($cart[$request->cart_key]);
        } elseif (isset($cart[$request->cart_key])) {
            $cart[$request->cart_key]['quantity'] = $request->quantity;
        }

        session(['cart' => $cart]);
        $count = collect($cart)->sum('quantity');

        return response()->json(['success' => true, 'count' => $count]);
    }

    public function remove(Request $request)
    {
        $request->validate(['cart_key' => 'required|string']);

        $cart = session('cart', []);
        unset($cart[$request->cart_key]);
        session(['cart' => $cart]);

        $count = collect($cart)->sum('quantity');

        return response()->json(['success' => true, 'count' => $count]);
    }

    public function count()
    {
        $cart = session('cart', []);
        $count = collect($cart)->sum('quantity');

        return response()->json(['count' => $count]);
    }
}
