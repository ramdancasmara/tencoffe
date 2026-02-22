@extends('layouts.admin')
@section('page-title', 'Detail Pesanan')
@section('content')

<div class="max-w-3xl">
    <div class="mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-coffee-600 hover:text-coffee-800 text-sm">← Kembali ke Daftar Pesanan</a>
    </div>

    <div class="card mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-coffee-800">{{ $order->order_number }}</h2>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-bold {{ $order->status_color }}">{{ $order->status_label }}</span>
        </div>

        {{-- Customer Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-xl">
                <h3 class="font-bold text-coffee-800 mb-2">Pelanggan</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p><strong>HP:</strong> {{ $order->customer_phone }}</p>
                    @if($order->customer_email)<p><strong>Email:</strong> {{ $order->customer_email }}</p>@endif
                </div>
            </div>
            <div class="bg-gray-50 p-4 rounded-xl">
                <h3 class="font-bold text-coffee-800 mb-2">Detail Pesanan</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Tipe:</strong> {{ ucfirst(str_replace('-', ' ', $order->order_type)) }}</p>
                    <p><strong>Bayar:</strong> {{ ucfirst($order->payment_method) }}</p>
                    @if($order->customer_address)<p><strong>Alamat:</strong> {{ $order->customer_address }}</p>@endif
                    @if($order->notes)<p><strong>Catatan:</strong> {{ $order->notes }}</p>@endif
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="mb-6">
            <h3 class="font-bold text-coffee-800 mb-3">Item Pesanan</h3>
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-4 text-gray-500 font-medium">Produk</th>
                            <th class="text-center py-3 px-4 text-gray-500 font-medium">Harga</th>
                            <th class="text-center py-3 px-4 text-gray-500 font-medium">Qty</th>
                            <th class="text-right py-3 px-4 text-gray-500 font-medium">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-t border-gray-50">
                                <td class="py-3 px-4 font-medium">{{ $item->product_name }}</td>
                                <td class="py-3 px-4 text-center">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 px-4 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Totals --}}
        <div class="bg-gray-50 p-4 rounded-xl mb-6">
            <div class="flex justify-between text-sm mb-1"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
            @if($order->delivery_fee > 0)<div class="flex justify-between text-sm mb-1"><span>Ongkir</span><span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span></div>@endif
            <div class="border-t border-gray-200 my-2"></div>
            <div class="flex justify-between font-bold text-lg text-coffee-800"><span>Total</span><span>{{ $order->formatted_total }}</span></div>
        </div>

        {{-- Update Status --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="flex gap-2 flex-1">
                @csrf @method('PUT')
                <select name="status" class="input-field flex-1">
                    @foreach(['pending','paid','processing','ready','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary px-6 rounded-xl">Update Status</button>
            </form>
            <a href="https://wa.me/{{ $order->customer_phone }}" target="_blank" class="btn-outline px-4 rounded-xl text-center">WhatsApp</a>
        </div>
    </div>

    {{-- Delete --}}
    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini? Data tidak bisa dikembalikan.')">
        @csrf @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">🗑️ Hapus Pesanan</button>
    </form>
</div>

@endsection
