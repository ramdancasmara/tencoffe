@extends('layouts.app')
@section('title', 'TenCoffe - Kafe & Coffee Shop')
@section('content')

{{-- Hero Section --}}
<section class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-coffee-900 via-coffee-800 to-coffee-700 overflow-hidden">
    <div class="absolute inset-0 bg-black/30"></div>
    <div class="relative z-10 text-center px-4">
        <img src="{{ asset('images/logo.jpeg') }}" alt="TenCoffe" class="w-28 h-28 mx-auto rounded-full border-4 border-coffee-300 shadow-2xl mb-6">
        <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-4 tracking-tight">TEN COFFE</h1>
        <p class="text-xl md:text-2xl text-coffee-200 mb-2 font-medium">Brewing Happiness, One Cup at a Time</p>
        <p class="text-coffee-300 mb-8 max-w-xl mx-auto">Nikmati kopi berkualitas tinggi dan makanan lezat. Pesan langsung dari meja atau delivery ke lokasi Anda.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('menu') }}" class="btn-primary text-lg px-8 py-4 rounded-2xl shadow-lg">☕ Lihat Menu</a>
            <a href="#bestsellers" class="btn-outline text-lg px-8 py-4 rounded-2xl border-white text-white hover:bg-white hover:text-coffee-800">⭐ Best Sellers</a>
        </div>
    </div>
    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
    </div>
</section>

{{-- Banner Carousel --}}
@if($banners->count())
<section class="py-8 bg-white" x-data="{ current: 0, total: {{ $banners->count() }} }"
         x-init="setInterval(() => { current = (current + 1) % total }, 4000)">
    <div class="max-w-6xl mx-auto px-4 relative">
        <div class="overflow-hidden rounded-2xl shadow-lg">
            <div class="relative" style="aspect-ratio: 21/9;">
                @foreach($banners as $i => $banner)
                    <div x-show="current === {{ $i }}" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                        @if($banner->title)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6 md:p-10">
                            <div class="text-white">
                                <h3 class="text-2xl md:text-3xl font-bold">{{ $banner->title }}</h3>
                                @if($banner->subtitle)<p class="text-white/80 mt-1">{{ $banner->subtitle }}</p>@endif
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        {{-- Dots --}}
        <div class="flex justify-center gap-2 mt-4">
            @foreach($banners as $i => $banner)
                <button @click="current = {{ $i }}" :class="current === {{ $i }} ? 'bg-coffee-600 w-8' : 'bg-coffee-200 w-3'" class="h-3 rounded-full transition-all duration-300"></button>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Best Sellers / Featured Products --}}
@if($featuredProducts->count())
<section id="bestsellers" class="py-16 bg-coffee-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-coffee-800">⭐ Best Sellers</h2>
            <p class="text-coffee-500 mt-2">Menu favorit pelanggan kami</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Kategori Showcase --}}
@if($categories->count())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-coffee-800">📋 Kategori Menu</h2>
            <p class="text-coffee-500 mt-2">Jelajahi menu kami berdasarkan kategori</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('menu.category', $cat->slug) }}" class="group bg-coffee-50 hover:bg-coffee-100 rounded-2xl p-6 text-center transition-all hover:shadow-lg hover:-translate-y-1">
                    <div class="w-16 h-16 bg-coffee-200 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-coffee-300 transition">
                        <span class="text-2xl">☕</span>
                    </div>
                    <h3 class="font-bold text-coffee-800 text-sm">{{ $cat->name }}</h3>
                    <p class="text-coffee-400 text-xs mt-1">{{ $cat->products_count }} menu</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Seasonal Menu --}}
@if($seasonalProducts->count())
<section class="py-16 bg-gradient-to-br from-amber-50 to-orange-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-amber-800">🌙 Menu Spesial</h2>
            <p class="text-amber-600 mt-2">Menu musiman yang sayang untuk dilewatkan</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($seasonalProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Event Spesial --}}
@if($specialEventEnabled && $specialEventImages->count())
<section class="py-16 bg-gradient-to-br from-coffee-800 to-coffee-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <span class="inline-block bg-amber-500 text-white px-4 py-1 rounded-full text-sm font-bold mb-3">
                {{ $specialEventSettings['special_event_emoji'] ?? '🌙' }} {{ $specialEventSettings['special_event_badge'] ?? 'Event Spesial' }}
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold">{{ $specialEventSettings['special_event_title'] ?? 'Menu Spesial' }}</h2>
            <p class="text-coffee-200 mt-2">{{ $specialEventSettings['special_event_subtitle'] ?? '' }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-data="{ lightbox: null }">
            @foreach($specialEventImages as $img)
                <div class="bg-white/10 backdrop-blur rounded-2xl overflow-hidden hover:bg-white/20 transition group">
                    @if($img->price)
                        <img src="{{ $img->image_url }}" alt="{{ $img->title }}" class="w-full aspect-square object-cover">
                        <div class="p-4">
                            <h4 class="font-bold text-lg">{{ $img->title ?? 'Paket Spesial' }}</h4>
                            @if($img->description)
                                <p class="text-coffee-200 text-sm mt-1 leading-relaxed">{{ $img->description }}</p>
                            @endif
                            <p class="text-amber-300 font-bold text-xl mt-2">{{ $img->formatted_price }}</p>
                            <button @click="fetch('/cart/add-event', {method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({gallery_id:{{ $img->id }}})}).then(r=>r.json()).then(d=>{if(d.success){window.dispatchEvent(new CustomEvent('cart-updated',{detail:{count:d.count}}));window.dispatchEvent(new CustomEvent('toast',{detail:{message:d.message}}))}})"
                                    class="mt-3 w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 rounded-xl transition">
                                + Tambah ke Keranjang
                            </button>
                        </div>
                    @else
                        <img src="{{ $img->image_url }}" alt="{{ $img->title }}" class="w-full aspect-square object-cover cursor-pointer" @click="lightbox = '{{ $img->image_url }}'">
                        @if($img->title)
                            <div class="p-4"><h4 class="font-bold">{{ $img->title }}</h4></div>
                        @endif
                    @endif
                </div>
            @endforeach

            {{-- Lightbox --}}
            <div x-show="lightbox" @click="lightbox=null" class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4" style="display:none;">
                <img :src="lightbox" class="max-w-full max-h-[90vh] rounded-lg">
                <button @click="lightbox=null" class="absolute top-4 right-4 text-white text-3xl">&times;</button>
            </div>
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('menu') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-3 rounded-2xl transition">Order Sekarang →</a>
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-16 bg-coffee-600">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Siap Untuk Memesan?</h2>
        <p class="text-coffee-100 mb-8">Pesan menu favorit Anda sekarang. Dine-in, take away, atau delivery!</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('menu') }}" class="bg-white text-coffee-700 font-bold px-8 py-4 rounded-2xl hover:bg-coffee-50 transition text-lg">Pesan Sekarang</a>
            <a href="{{ route('contact') }}" class="border-2 border-white text-white font-bold px-8 py-4 rounded-2xl hover:bg-white hover:text-coffee-700 transition text-lg">Hubungi Kami</a>
        </div>
    </div>
</section>

@endsection
