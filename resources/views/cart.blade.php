@extends('layouts.app')
@section('title', 'Keranjang - TenCoffe')
@section('content')

<div class="pt-20 pb-16 min-h-screen bg-coffee-50" x-data="cartPage()">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <h1 class="text-3xl font-extrabold text-coffee-800 mb-8">🛒 Keranjang Belanja</h1>

        @if(count($items) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $item)
                    <div class="bg-white rounded-2xl p-4 shadow-sm flex items-center gap-4" id="cart-item-{{ $item['key'] }}">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-coffee-800 text-sm">{{ $item['name'] }}</h3>
                            <div class="flex gap-1 mt-1">
                                @if($item['variant'])
                                    <span class="text-xs {{ $item['variant'] === 'hot' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} px-2 py-0.5 rounded-full font-bold">{{ ucfirst($item['variant']) }}</span>
                                @endif
                                @if($item['type'] === 'event')
                                    <span class="text-xs bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full font-bold">🌙 Paket Ramadhan</span>
                                @endif
                            </div>
                            <p class="text-coffee-600 font-bold mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="updateQty('{{ $item['key'] }}', {{ $item['quantity'] - 1 }})" class="w-8 h-8 bg-coffee-100 hover:bg-coffee-200 rounded-full flex items-center justify-center text-coffee-700 font-bold transition">-</button>
                            <span class="w-8 text-center font-bold text-coffee-800">{{ $item['quantity'] }}</span>
                            <button @click="updateQty('{{ $item['key'] }}', {{ $item['quantity'] + 1 }})" class="w-8 h-8 bg-coffee-100 hover:bg-coffee-200 rounded-full flex items-center justify-center text-coffee-700 font-bold transition">+</button>
                        </div>
                        <button @click="removeItem('{{ $item['key'] }}')" class="text-red-400 hover:text-red-600 transition p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 shadow-sm sticky top-24">
                    <h3 class="font-bold text-coffee-800 text-lg mb-4">Ringkasan</h3>
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Total Item</span>
                        <span>{{ collect($items)->sum('quantity') }}</span>
                    </div>
                    <div class="border-t border-gray-100 my-3"></div>
                    <div class="flex justify-between font-bold text-coffee-800 text-lg">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn-primary w-full mt-6 py-3 text-center rounded-xl block">Checkout →</a>
                    <a href="{{ route('menu') }}" class="btn-outline w-full mt-3 py-3 text-center rounded-xl block">← Lanjut Belanja</a>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-20">
            <p class="text-6xl mb-4">🛒</p>
            <h3 class="text-xl font-bold text-coffee-700 mb-2">Keranjang kosong</h3>
            <p class="text-coffee-400 mb-6">Yuk, pilih menu favoritmu!</p>
            <a href="{{ route('menu') }}" class="btn-primary px-8 py-3 rounded-xl">Lihat Menu</a>
        </div>
        @endif
    </div>
</div>

<script>
function cartPage() {
    return {
        updateQty(key, qty) {
            fetch('/cart/update', {
                method: 'PUT',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                body: JSON.stringify({cart_key: key, quantity: qty})
            }).then(r => r.json()).then(d => {
                if(d.success) {
                    window.dispatchEvent(new CustomEvent('cart-updated', {detail: {count: d.count}}));
                    location.reload();
                }
            });
        },
        removeItem(key) {
            fetch('/cart/remove', {
                method: 'DELETE',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                body: JSON.stringify({cart_key: key})
            }).then(r => r.json()).then(d => {
                if(d.success) {
                    window.dispatchEvent(new CustomEvent('cart-updated', {detail: {count: d.count}}));
                    location.reload();
                }
            });
        }
    }
}
</script>
@endsection
