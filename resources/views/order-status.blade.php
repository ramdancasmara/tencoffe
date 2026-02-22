@extends('layouts.app')
@section('title', 'Status Pesanan - TenCoffe')
@section('content')

<div class="pt-20 pb-16 min-h-screen bg-coffee-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 pt-4">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-coffee-800">Pesanan Berhasil!</h1>
            <p class="text-coffee-500 mt-1">Nomor pesanan Anda:</p>
            <p class="text-2xl font-bold text-coffee-600 mt-1">{{ $order->order_number }}</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm space-y-4">
            {{-- Status --}}
            <div class="text-center">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold {{ $order->status_color }}">{{ $order->status_label }}</span>
            </div>

            {{-- Customer Info --}}
            <div class="border-t border-gray-100 pt-4">
                <h3 class="font-bold text-coffee-800 mb-2">Informasi Pelanggan</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p><strong>HP:</strong> {{ $order->customer_phone }}</p>
                    @if($order->customer_email)<p><strong>Email:</strong> {{ $order->customer_email }}</p>@endif
                    <p><strong>Tipe:</strong> {{ ucfirst(str_replace('-', ' ', $order->order_type)) }}</p>
                    @if($order->customer_address)<p><strong>Alamat:</strong> {{ $order->customer_address }}</p>@endif
                    @if($order->notes)<p><strong>Catatan:</strong> {{ $order->notes }}</p>@endif
                </div>
            </div>

            {{-- Items --}}
            <div class="border-t border-gray-100 pt-4">
                <h3 class="font-bold text-coffee-800 mb-2">Detail Pesanan</h3>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $item->product_name }} x{{ $item->quantity }}</span>
                            <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Total --}}
            <div class="border-t border-gray-100 pt-4">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->delivery_fee > 0)
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>Ongkir</span><span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-coffee-800 text-lg mt-2">
                    <span>Total</span><span>{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('menu') }}" class="btn-primary px-8 py-3 rounded-xl">Pesan Lagi</a>
        </div>
    </div>
</div>
@endsection
