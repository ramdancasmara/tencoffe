@extends('layouts.admin')
@section('page-title', isset($product) ? 'Edit Produk' : 'Tambah Produk')
@section('content')

<div class="max-w-3xl">
    <div class="card" x-data="{
        hasVariants: {{ isset($product) && $product->has_variants ? 'true' : 'false' }},
        isPromo: {{ isset($product) && $product->is_promo ? 'true' : 'false' }},
        isSeasonal: {{ isset($product) && $product->is_seasonal ? 'true' : 'false' }}
    }">
        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
              method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($product)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="input-field">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select name="category_id" required class="input-field">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="input-field">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            {{-- Variant Toggle --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="has_variants" value="1" x-model="hasVariants" id="has_variants" class="rounded border-coffee-300 text-coffee-600">
                <label for="has_variants" class="text-sm font-medium text-gray-700">Punya varian Hot/Cold</label>
            </div>

            {{-- Normal Price --}}
            <div x-show="!hasVariants">
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
                <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" class="input-field">
            </div>

            {{-- Variant Prices --}}
            <div x-show="hasVariants" class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Hot 🔥</label>
                    <input type="number" name="price_hot" value="{{ old('price_hot', $product->price_hot ?? '') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Cold ❄️</label>
                    <input type="number" name="price_cold" value="{{ old('price_cold', $product->price_cold ?? '') }}" class="input-field">
                </div>
            </div>

            {{-- Image --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar (Max 2MB)</label>
                @if(isset($product) && $product->image)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ $product->image_url }}" class="w-20 h-20 rounded-lg object-cover">
                        <label class="text-sm text-red-500 flex items-center gap-1">
                            <input type="checkbox" name="remove_image" value="1"> Hapus gambar
                        </label>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="input-field">
            </div>

            {{-- Checkboxes --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} class="rounded border-coffee-300 text-coffee-600">
                    <span class="text-sm font-medium">Aktif</span>
                </label>
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }} class="rounded border-coffee-300 text-coffee-600">
                    <span class="text-sm font-medium">Featured</span>
                </label>
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl">
                    <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new ?? false) ? 'checked' : '' }} class="rounded border-coffee-300 text-coffee-600">
                    <span class="text-sm font-medium">Baru</span>
                </label>
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl" @click="isPromo = !isPromo">
                    <input type="checkbox" name="is_promo" value="1" x-model="isPromo" class="rounded border-coffee-300 text-coffee-600">
                    <span class="text-sm font-medium">Promo</span>
                </label>
                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl" @click="isSeasonal = !isSeasonal">
                    <input type="checkbox" name="is_seasonal" value="1" x-model="isSeasonal" class="rounded border-coffee-300 text-coffee-600">
                    <span class="text-sm font-medium">Seasonal</span>
                </label>
            </div>

            {{-- Promo Price --}}
            <div x-show="isPromo">
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Promo</label>
                <input type="number" name="promo_price" value="{{ old('promo_price', $product->promo_price ?? '') }}" class="input-field">
            </div>

            {{-- Seasonal Label --}}
            <div x-show="isSeasonal">
                <label class="block text-sm font-medium text-gray-700 mb-1">Label Seasonal</label>
                <input type="text" name="seasonal_label" value="{{ old('seasonal_label', $product->seasonal_label ?? '') }}" placeholder="e.g., Ramadhan" class="input-field">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn-primary px-6 py-3 rounded-xl">{{ isset($product) ? 'Simpan Perubahan' : 'Tambah Produk' }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline px-6 py-3 rounded-xl">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
