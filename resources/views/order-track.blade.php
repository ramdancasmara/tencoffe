@extends('layouts.app')
@section('title', 'Lacak Pesanan - TenCoffe')
@section('content')

<div class="pt-20 pb-16 min-h-screen bg-coffee-50">
    <div class="max-w-xl mx-auto px-4 sm:px-6 pt-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-coffee-800">🔍 Lacak Pesanan</h1>
            <p class="text-coffee-500 mt-2">Masukkan nomor pesanan untuk melihat status</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <form action="{{ route('order.track.search') }}" method="POST">
                @csrf
                <div class="flex gap-3">
                    <input type="text" name="order_number" placeholder="Contoh: TEN260222-0001" value="{{ old('order_number') }}" required class="input-field flex-1">
                    <button type="submit" class="btn-primary px-6 rounded-xl">Cari</button>
                </div>
            </form>
        </div>

        @if(isset($order) && $order)
            <div class="mt-8 bg-white rounded-2xl p-6 shadow-sm">
                <div class="text-center mb-4">
                    <p class="text-lg font-bold text-coffee-800">{{ $order->order_number }}</p>
                    <span class="inline-block px-4 py-1 rounded-full text-sm font-bold {{ $order->status_color }} mt-2">{{ $order->status_label }}</span>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Total:</strong> {{ $order->formatted_total }}</p>
                </div>
                <div class="mt-4 border-t border-gray-100 pt-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm mb-1">
                            <span>{{ $item->product_name }} x{{ $item->quantity }}</span>
                            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif(isset($order) && !$order)
            <div class="mt-8 bg-red-50 rounded-2xl p-6 text-center">
                <p class="text-red-600 font-medium">Pesanan tidak ditemukan</p>
            </div>
        @endif
    </div>
</div>
@endsection
