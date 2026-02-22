@extends('layouts.app')
@section('title', 'Checkout - TenCoffe')
@section('content')

<div class="pt-20 pb-16 min-h-screen bg-coffee-50" x-data="{ orderType: 'dine-in', paymentMethod: 'manual' }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <h1 class="text-3xl font-extrabold text-coffee-800 mb-8">📝 Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Form --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Customer Info --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="font-bold text-coffee-800 text-lg mb-4">Informasi Pelanggan</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="input-field">
                                @error('customer_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp / No HP *</label>
                                <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required placeholder="08xxxxxxxxxx" class="input-field">
                                @error('customer_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email (opsional)</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="input-field">
                            </div>
                        </div>
                    </div>

                    {{-- Order Type --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="font-bold text-coffee-800 text-lg mb-4">Tipe Pesanan</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <label @click="orderType='dine-in'" :class="orderType==='dine-in' ? 'border-coffee-600 bg-coffee-50' : 'border-gray-200'" class="cursor-pointer border-2 rounded-xl p-4 text-center transition">
                                <input type="radio" name="order_type" value="dine-in" x-model="orderType" class="hidden">
                                <p class="text-2xl mb-1">🍽️</p>
                                <p class="font-bold text-sm text-coffee-800">Dine In</p>
                            </label>
                            <label @click="orderType='pickup'" :class="orderType==='pickup' ? 'border-coffee-600 bg-coffee-50' : 'border-gray-200'" class="cursor-pointer border-2 rounded-xl p-4 text-center transition">
                                <input type="radio" name="order_type" value="pickup" x-model="orderType" class="hidden">
                                <p class="text-2xl mb-1">🥡</p>
                                <p class="font-bold text-sm text-coffee-800">Take Away</p>
                            </label>
                            <label @click="orderType='delivery'" :class="orderType==='delivery' ? 'border-coffee-600 bg-coffee-50' : 'border-gray-200'" class="cursor-pointer border-2 rounded-xl p-4 text-center transition">
                                <input type="radio" name="order_type" value="delivery" x-model="orderType" class="hidden">
                                <p class="text-2xl mb-1">🚗</p>
                                <p class="font-bold text-sm text-coffee-800">Delivery</p>
                            </label>
                        </div>

                        {{-- Address (delivery only) --}}
                        <div x-show="orderType==='delivery'" x-transition class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman *</label>
                            <textarea name="customer_address" rows="3" class="input-field" placeholder="Alamat lengkap...">{{ old('customer_address') }}</textarea>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pesanan (opsional)</label>
                        <textarea name="notes" rows="3" class="input-field" placeholder="Contoh: gula sedikit, es banyak...">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Payment Method --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="font-bold text-coffee-800 text-lg mb-4">Metode Pembayaran</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label @click="paymentMethod='manual'" :class="paymentMethod==='manual' ? 'border-coffee-600 bg-coffee-50' : 'border-gray-200'" class="cursor-pointer border-2 rounded-xl p-4 transition">
                                <input type="radio" name="payment_method" value="manual" x-model="paymentMethod" class="hidden">
                                <p class="font-bold text-sm text-coffee-800">💰 Bayar di Kasir</p>
                                <p class="text-xs text-gray-500 mt-1">Bayar langsung di tempat</p>
                            </label>
                            <label @click="paymentMethod='whatsapp'" :class="paymentMethod==='whatsapp' ? 'border-coffee-600 bg-coffee-50' : 'border-gray-200'" class="cursor-pointer border-2 rounded-xl p-4 transition">
                                <input type="radio" name="payment_method" value="whatsapp" x-model="paymentMethod" class="hidden">
                                <p class="font-bold text-sm text-coffee-800">📱 Via WhatsApp</p>
                                <p class="text-xs text-gray-500 mt-1">Pesan dikirim via WhatsApp</p>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 shadow-sm sticky top-24">
                        <h3 class="font-bold text-coffee-800 text-lg mb-4">Ringkasan Pesanan</h3>
                        <div class="space-y-3 max-h-60 overflow-y-auto">
                            @foreach($items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $item['name'] }}@if($item['variant']) ({{ ucfirst($item['variant']) }})@endif x{{ $item['quantity'] }}</span>
                                    <span class="font-medium text-coffee-800">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t border-gray-100 my-3"></div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div x-show="orderType==='delivery'" class="flex justify-between text-sm text-gray-600 mt-1">
                            <span>Ongkir</span>
                            <span>Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-100 my-3"></div>
                        <div class="flex justify-between font-bold text-coffee-800 text-lg">
                            <span>Total</span>
                            <span x-text="orderType==='delivery' ? 'Rp {{ number_format($subtotal + $deliveryFee, 0, ',', '.') }}' : 'Rp {{ number_format($subtotal, 0, ',', '.') }}'"></span>
                        </div>
                        <button type="submit" class="btn-primary w-full mt-6 py-3 text-center rounded-xl">
                            <span x-show="paymentMethod==='whatsapp'">Kirim via WhatsApp</span>
                            <span x-show="paymentMethod==='manual'">Buat Pesanan</span>
                        </button>
                        <a href="{{ route('cart') }}" class="block text-center text-sm text-coffee-500 hover:text-coffee-700 mt-3">← Kembali ke Keranjang</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
