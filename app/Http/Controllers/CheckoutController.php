<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('menu');

        $items = $this->resolveCart($cart);
        $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);
        $deliveryFee = (int) Setting::get('delivery_fee', 10000);
        $storeWhatsapp = Setting::get('store_whatsapp', '');

        return view('checkout', compact('items', 'subtotal', 'deliveryFee', 'storeWhatsapp'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'order_type' => 'required|in:dine-in,pickup,delivery',
            'customer_address' => 'required_if:order_type,delivery|nullable|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:manual,whatsapp',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('menu');

        $items = $this->resolveCart($cart);
        $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);

        $deliveryFee = 0;
        if ($validated['order_type'] === 'delivery') {
            $deliveryFee = (int) Setting::get('delivery_fee', 10000);
        }

        $total = $subtotal + $deliveryFee;

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'order_type' => $validated['order_type'],
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $item['name'] . ($item['variant'] ? ' (' . ucfirst($item['variant']) . ')' : ''),
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');

        if ($validated['payment_method'] === 'whatsapp') {
            $whatsappNumber = Setting::get('store_whatsapp', '');
            $message = $this->buildWhatsAppMessage($order);
            return redirect("https://wa.me/{$whatsappNumber}?text=" . urlencode($message));
        }

        return redirect()->route('order.status', $order->order_number);
    }

    private function resolveCart(array $cart): array
    {
        $items = [];

        foreach ($cart as $key => $item) {
            if (str_starts_with($key, 'event_')) {
                $galleryId = str_replace('event_', '', $key);
                $gallery = Gallery::find($galleryId);
                $items[] = [
                    'key' => $key,
                    'product_id' => null,
                    'name' => $item['name'] ?? ($gallery?->title ?? 'Paket Spesial'),
                    'price' => $item['price'] ?? ($gallery?->price ?? 0),
                    'quantity' => $item['quantity'],
                    'image' => $item['image'] ?? ($gallery?->image_url ?? ''),
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
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'image' => $product->image_url,
                    'variant' => $variant,
                    'type' => 'product',
                ];
            }
        }

        return $items;
    }

    private function buildWhatsAppMessage(Order $order): string
    {
        $order->load('items');

        $typeLabels = ['dine-in' => 'Dine In', 'pickup' => 'Pickup', 'delivery' => 'Delivery'];

        $msg = "🛒 *PESANAN BARU - TENCOFFE*\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "📋 No. Pesanan: *{$order->order_number}*\n";
        $msg .= "👤 Nama: {$order->customer_name}\n";
        $msg .= "📱 HP: {$order->customer_phone}\n";
        if ($order->customer_email) $msg .= "📧 Email: {$order->customer_email}\n";
        $msg .= "📍 Tipe: " . ($typeLabels[$order->order_type] ?? $order->order_type) . "\n";
        if ($order->customer_address) $msg .= "📍 Alamat: {$order->customer_address}\n";

        $msg .= "\n*Detail Pesanan:*\n";
        foreach ($order->items as $item) {
            $sub = 'Rp ' . number_format($item->subtotal, 0, ',', '.');
            $msg .= "• {$item->product_name} x{$item->quantity} = {$sub}\n";
        }

        $msg .= "\n━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "Subtotal: Rp " . number_format($order->subtotal, 0, ',', '.') . "\n";
        if ($order->delivery_fee > 0) {
            $msg .= "Ongkir: Rp " . number_format($order->delivery_fee, 0, ',', '.') . "\n";
        }
        $msg .= "*TOTAL: Rp " . number_format($order->total, 0, ',', '.') . "*\n";

        if ($order->notes) $msg .= "\n📝 Catatan: {$order->notes}\n";
        $msg .= "\nTerima kasih! 🙏";

        return $msg;
    }
}
