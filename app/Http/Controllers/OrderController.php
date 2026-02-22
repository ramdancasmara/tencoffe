<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function status(string $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        return view('order-status', compact('order'));
    }

    public function track()
    {
        return view('order-track');
    }

    public function trackSearch()
    {
        $validated = request()->validate(['order_number' => 'required|string']);

        $order = Order::with('items')
            ->where('order_number', $validated['order_number'])
            ->first();

        return view('order-track', compact('order'));
    }
}
