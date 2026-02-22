<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);

        if ($perPage === 'all') {
            $orders = $query->latest()->get();
        } else {
            $orders = $query->latest()->paginate((int) $perPage)->withQueryString();
        }

        return view('admin.orders.index', compact('orders', 'perPage'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,paid,processing,ready,completed,cancelled']);

        $order->status = $request->status;

        if ($request->status === 'paid' && !$order->paid_at) {
            $order->paid_at = now();
            $order->payment_status = 'paid';
        }

        if ($request->status === 'cancelled') {
            $order->payment_status = 'cancelled';
        }

        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Status pesanan diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
