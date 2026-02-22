<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TenCoffe - Kafe & Coffee Shop')</title>
    <link rel="icon" href="{{ asset('images/logo.jpeg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-coffee-50 text-gray-800 font-sans" x-data="{ mobileMenu: false }">

    {{-- Toast Notification --}}
    <div x-data="{ show: false, message: '' }"
         x-on:toast.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-20 right-4 z-[100] bg-coffee-700 text-white px-6 py-3 rounded-xl shadow-lg"
         style="display: none;">
        <span x-text="message"></span>
    </div>

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
         x-data="{ scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
         :class="scrolled ? 'bg-white/95 backdrop-blur shadow-md' : 'bg-coffee-900/80 backdrop-blur'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="TenCoffe" class="w-10 h-10 rounded-full border-2 border-coffee-300 shadow">
                    <span class="font-bold text-lg" :class="scrolled ? 'text-coffee-800' : 'text-white'">TEN COFFE</span>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="font-medium transition" :class="scrolled ? 'text-coffee-700 hover:text-coffee-900' : 'text-white/90 hover:text-white'">Home</a>
                    <a href="{{ route('menu') }}" class="font-medium transition" :class="scrolled ? 'text-coffee-700 hover:text-coffee-900' : 'text-white/90 hover:text-white'">Menu</a>
                    <a href="{{ route('order.track') }}" class="font-medium transition" :class="scrolled ? 'text-coffee-700 hover:text-coffee-900' : 'text-white/90 hover:text-white'">Lacak</a>
                    <a href="{{ route('contact') }}" class="font-medium transition" :class="scrolled ? 'text-coffee-700 hover:text-coffee-900' : 'text-white/90 hover:text-white'">Kontak</a>

                    {{-- Cart Button --}}
                    <a href="{{ route('cart') }}" class="relative" x-data="{ count: 0 }"
                       x-init="fetch('/cart/count').then(r=>r.json()).then(d=>count=d.count)"
                       x-on:cart-updated.window="count = $event.detail.count">
                        <div class="p-2 rounded-full transition" :class="scrolled ? 'bg-coffee-100 text-coffee-700' : 'bg-white/20 text-white'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        </div>
                        <span x-show="count > 0" x-text="count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-bold"></span>
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="flex items-center gap-3 md:hidden">
                    <a href="{{ route('cart') }}" class="relative" x-data="{ count: 0 }"
                       x-init="fetch('/cart/count').then(r=>r.json()).then(d=>count=d.count)"
                       x-on:cart-updated.window="count = $event.detail.count">
                        <div class="p-2 rounded-full" :class="scrolled ? 'text-coffee-700' : 'text-white'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        </div>
                        <span x-show="count > 0" x-text="count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-bold"></span>
                    </a>
                    <button @click="mobileMenu = !mobileMenu" :class="scrolled ? 'text-coffee-700' : 'text-white'">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div x-show="mobileMenu" x-transition class="md:hidden pb-4 border-t" :class="scrolled ? 'border-coffee-100' : 'border-white/20'">
                <div class="flex flex-col gap-2 pt-3">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg font-medium" :class="scrolled ? 'text-coffee-700 hover:bg-coffee-100' : 'text-white hover:bg-white/10'">Home</a>
                    <a href="{{ route('menu') }}" class="px-3 py-2 rounded-lg font-medium" :class="scrolled ? 'text-coffee-700 hover:bg-coffee-100' : 'text-white hover:bg-white/10'">Menu</a>
                    <a href="{{ route('order.track') }}" class="px-3 py-2 rounded-lg font-medium" :class="scrolled ? 'text-coffee-700 hover:bg-coffee-100' : 'text-white hover:bg-white/10'">Lacak Pesanan</a>
                    <a href="{{ route('contact') }}" class="px-3 py-2 rounded-lg font-medium" :class="scrolled ? 'text-coffee-700 hover:bg-coffee-100' : 'text-white hover:bg-white/10'">Kontak</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-coffee-900 text-white pt-12 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                {{-- About --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="TenCoffe" class="w-12 h-12 rounded-full border-2 border-coffee-400">
                        <span class="font-bold text-xl">TEN COFFE</span>
                    </div>
                    <p class="text-coffee-200 text-sm leading-relaxed">Nikmati kopi berkualitas tinggi dan makanan lezat di TenCoffe. Tempat nongkrong terbaik dengan suasana yang nyaman.</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="font-bold text-lg mb-4">Quick Links</h3>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('home') }}" class="text-coffee-200 hover:text-white transition text-sm">Home</a>
                        <a href="{{ route('menu') }}" class="text-coffee-200 hover:text-white transition text-sm">Menu</a>
                        <a href="{{ route('order.track') }}" class="text-coffee-200 hover:text-white transition text-sm">Lacak Pesanan</a>
                        <a href="{{ route('contact') }}" class="text-coffee-200 hover:text-white transition text-sm">Kontak</a>
                    </div>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="font-bold text-lg mb-4">Hubungi Kami</h3>
                    <div class="flex flex-col gap-2 text-coffee-200 text-sm">
                        @php $settings = \App\Models\Setting::getGroup('general'); @endphp
                        @if(!empty($settings['phone']))
                            <p>📞 {{ $settings['phone'] }}</p>
                        @endif
                        @if(!empty($settings['email']))
                            <p>📧 {{ $settings['email'] }}</p>
                        @endif
                        @if(!empty($settings['address']))
                            <p>📍 {{ $settings['address'] }}</p>
                        @endif
                        @if(!empty($settings['operating_hours']))
                            <p>🕐 {{ $settings['operating_hours'] }}</p>
                        @endif
                    </div>
                    @php $social = \App\Models\Setting::getGroup('social'); @endphp
                    <div class="flex gap-3 mt-4">
                        @if(!empty($social['instagram']))
                            <a href="{{ $social['instagram'] }}" target="_blank" class="w-9 h-9 bg-coffee-700 hover:bg-coffee-600 rounded-full flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                        @endif
                        @if(!empty($social['tiktok']))
                            <a href="{{ $social['tiktok'] }}" target="_blank" class="w-9 h-9 bg-coffee-700 hover:bg-coffee-600 rounded-full flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.88-2.89 2.89 2.89 0 012.88-2.89c.28 0 .54.04.79.11V9.02a6.26 6.26 0 00-.79-.05A6.34 6.34 0 003.15 15.3a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V9.01a8.22 8.22 0 004.76 1.46V7.02a4.84 4.84 0 01-1-.33z"/></svg>
                            </a>
                        @endif
                        @if(!empty($social['whatsapp']))
                            <a href="https://wa.me/{{ $social['whatsapp'] }}" target="_blank" class="w-9 h-9 bg-coffee-700 hover:bg-coffee-600 rounded-full flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-coffee-700 pt-6 text-center text-coffee-300 text-sm">
                &copy; {{ date('Y') }} TenCoffe. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
