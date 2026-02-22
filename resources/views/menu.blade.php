@extends('layouts.app')
@section('title', 'Menu - TenCoffe')
@section('content')

<div class="pt-20 pb-16 min-h-screen bg-coffee-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-10 pt-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-coffee-800">Menu Kami</h1>
            <p class="text-coffee-500 mt-2">Pilih menu favorit Anda</p>
        </div>

        {{-- Category Filter --}}
        <div class="flex flex-wrap justify-center gap-2 mb-10">
            <a href="{{ route('menu') }}" class="px-5 py-2 rounded-full text-sm font-bold transition {{ !isset($category) ? 'bg-coffee-600 text-white' : 'bg-white text-coffee-600 hover:bg-coffee-100' }}">Semua</a>
            @foreach($categories as $cat)
                <a href="{{ route('menu.category', $cat->slug) }}" class="px-5 py-2 rounded-full text-sm font-bold transition {{ isset($category) && $category->id === $cat->id ? 'bg-coffee-600 text-white' : 'bg-white text-coffee-600 hover:bg-coffee-100' }}">{{ $cat->name }}</a>
            @endforeach
        </div>

        {{-- Products Grid with Gradual Loading --}}
        <div x-data="{ visibleCount: 8, total: {{ $products->count() }} }">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $index => $product)
                    <div x-show="{{ $index }} < visibleCount" x-transition>
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            {{-- Load More --}}
            <div x-show="visibleCount < total" class="text-center py-16">
                <button @click="visibleCount += 8" class="btn-primary px-8 py-3 rounded-2xl text-lg">
                    Lihat Lainnya (<span x-text="Math.max(0, total - visibleCount)"></span> menu lagi)
                </button>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-20">
                    <p class="text-6xl mb-4">☕</p>
                    <h3 class="text-xl font-bold text-coffee-700 mb-2">Belum ada menu</h3>
                    <p class="text-coffee-400">Menu akan segera tersedia</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
