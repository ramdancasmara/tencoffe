@extends('layouts.app')
@section('title', 'Kontak - TenCoffee')
@section('content')

<div class="pt-20 pb-16 min-h-screen bg-coffee-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-coffee-800">📍 Hubungi Kami</h1>
            <p class="text-coffee-500 mt-2">Kami senang mendengar dari Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Contact Info --}}
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h3 class="font-bold text-coffee-800 text-xl mb-6">Informasi Kontak</h3>
                <div class="space-y-4">
                    @if(!empty($social['whatsapp']))
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-coffee-100 rounded-xl flex items-center justify-center flex-shrink-0">📱</div>
                            <div>
                                <p class="font-medium text-coffee-800">WhatsApp</p>
                                <p class="text-gray-500 text-sm">{{ preg_replace('/^62/', '0', $social['whatsapp']) }}</p>
                            </div>
                        </div>
                    @endif
                    @if(!empty($general['email']))
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-coffee-100 rounded-xl flex items-center justify-center flex-shrink-0">📧</div>
                            <div><p class="font-medium text-coffee-800">Email</p><p class="text-gray-500 text-sm">{{ $general['email'] }}</p></div>
                        </div>
                    @endif
                    @if(!empty($general['address']))
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-coffee-100 rounded-xl flex items-center justify-center flex-shrink-0">📍</div>
                            <div><p class="font-medium text-coffee-800">Alamat</p><p class="text-gray-500 text-sm">{{ $general['address'] }}</p></div>
                        </div>
                    @endif
                    @if(!empty($general['operating_hours']))
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-coffee-100 rounded-xl flex items-center justify-center flex-shrink-0">🕐</div>
                            <div><p class="font-medium text-coffee-800">Jam Operasional</p><p class="text-gray-500 text-sm">{{ $general['operating_hours'] }}</p></div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Google Maps --}}
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <h3 class="font-bold text-coffee-800 text-xl">📍 Google Maps</h3>
                    @if(!empty($general['google_maps_url']))
                        <a href="{{ $general['google_maps_url'] }}" target="_blank" class="text-sm font-medium text-coffee-600 hover:text-coffee-800">Buka di Maps</a>
                    @endif
                </div>

                @if(!empty($general['google_maps_url']))
                    <div class="overflow-hidden rounded-2xl border border-coffee-100">
                        <iframe
                            src="{{ $general['google_maps_url'] }}"
                            width="100%"
                            height="360"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                @else
                    <div class="h-[360px] rounded-2xl border border-dashed border-coffee-200 bg-coffee-50 flex items-center justify-center text-center px-6">
                        <div>
                            <p class="font-semibold text-coffee-700">Link Google Maps belum diatur</p>
                            <p class="text-sm text-gray-500 mt-1">Tambahkan link embed Google Maps di admin panel untuk menampilkan peta.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Social Media --}}
        <div class="bg-white rounded-2xl p-8 shadow-sm mt-8">
            <h3 class="font-bold text-coffee-800 text-xl mb-6">Sosial Media</h3>
                <div class="space-y-4">
                    @if(!empty($social['whatsapp']))
                        <a href="https://wa.me/{{ $social['whatsapp'] }}" target="_blank" class="flex items-center gap-4 p-4 bg-green-50 rounded-xl hover:bg-green-100 transition group">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center text-white text-xl">💬</div>
                            <div>
                                <p class="font-bold text-green-700">WhatsApp</p>
                                <p class="text-green-600 text-sm">Chat langsung dengan kami</p>
                            </div>
                        </a>
                    @endif
                    @if(!empty($social['instagram']))
                        <a href="{{ $social['instagram'] }}" target="_blank" class="flex items-center gap-4 p-4 bg-pink-50 rounded-xl hover:bg-pink-100 transition group">
                            <div class="w-12 h-12 bg-gradient-to-tr from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white text-xl">📸</div>
                            <div>
                                <p class="font-bold text-pink-700">Instagram</p>
                                <p class="text-pink-600 text-sm">Follow kami di Instagram</p>
                            </div>
                        </a>
                    @endif
                    @if(!empty($social['tiktok']))
                        <a href="{{ $social['tiktok'] }}" target="_blank" class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition group">
                            <div class="w-12 h-12 bg-black rounded-xl flex items-center justify-center text-white text-xl">🎵</div>
                            <div>
                                <p class="font-bold text-gray-800">TikTok</p>
                                <p class="text-gray-600 text-sm">Follow kami di TikTok</p>
                            </div>
                        </a>
                    @endif
                </div>
        </div>
    </div>
</div>
@endsection
