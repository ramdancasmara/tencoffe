@extends('layouts.admin')
@section('page-title', 'Pengaturan')
@section('content')

<form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6 max-w-3xl">
    @csrf

    {{-- General --}}
    <div class="card">
        <h3 class="font-bold text-coffee-800 text-lg mb-4">🏪 Umum</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                <input type="text" name="site_name" value="{{ $general['site_name'] ?? 'TenCoffe' }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                <input type="text" name="tagline" value="{{ $general['tagline'] ?? '' }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ $general['email'] ?? '' }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="phone" value="{{ $general['phone'] ?? '' }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon 2</label>
                <input type="text" name="phone2" value="{{ $general['phone2'] ?? '' }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                <input type="text" name="operating_hours" value="{{ $general['operating_hours'] ?? '' }}" class="input-field">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" rows="2" class="input-field">{{ $general['address'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Social --}}
    <div class="card">
        <h3 class="font-bold text-coffee-800 text-lg mb-4">📱 Sosial Media</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                <input type="text" name="instagram" value="{{ $social['instagram'] ?? '' }}" class="input-field" placeholder="https://instagram.com/...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">TikTok URL</label>
                <input type="text" name="tiktok" value="{{ $social['tiktok'] ?? '' }}" class="input-field" placeholder="https://tiktok.com/...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                <input type="text" name="whatsapp" value="{{ $social['whatsapp'] ?? '' }}" class="input-field" placeholder="628xxxxxxxxxx">
            </div>
        </div>
    </div>

    {{-- Order --}}
    <div class="card">
        <h3 class="font-bold text-coffee-800 text-lg mb-4">📦 Pesanan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Pesanan</label>
                <input type="text" name="store_whatsapp" value="{{ $order['store_whatsapp'] ?? '' }}" class="input-field" placeholder="628xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Min. Order (Rp)</label>
                <input type="number" name="min_order" value="{{ $order['min_order'] ?? 0 }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ongkos Kirim (Rp)</label>
                <input type="number" name="delivery_fee" value="{{ $order['delivery_fee'] ?? 10000 }}" class="input-field">
            </div>
        </div>
    </div>

    {{-- Payment --}}
    <div class="card">
        <h3 class="font-bold text-coffee-800 text-lg mb-4">💳 Payment Gateway (Duitku)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mode</label>
                <select name="duitku_mode" class="input-field">
                    <option value="sandbox" {{ ($payment['duitku_mode'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                    <option value="production" {{ ($payment['duitku_mode'] ?? '') === 'production' ? 'selected' : '' }}>Production</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Merchant Code</label>
                <input type="text" name="duitku_merchant_code" value="{{ $payment['duitku_merchant_code'] ?? '' }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                <input type="text" name="duitku_api_key" value="{{ $payment['duitku_api_key'] ?? '' }}" class="input-field">
            </div>
            <div class="flex items-end">
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl w-full">
                    <input type="checkbox" name="duitku_enabled" value="1" {{ ($payment['duitku_enabled'] ?? false) ? 'checked' : '' }} class="rounded border-coffee-300 text-coffee-600">
                    <span class="text-sm font-medium">Aktifkan Duitku</span>
                </label>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-primary px-8 py-3 rounded-xl text-lg">Simpan Semua Pengaturan</button>
</form>

@endsection
