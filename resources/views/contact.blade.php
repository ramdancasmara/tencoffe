@extends('layouts.app')
@section('title', 'Kontak - TenCoffe')
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
                    @if(!empty($general['phone']))
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-coffee-100 rounded-xl flex items-center justify-center flex-shrink-0">📞</div>
                            <div><p class="font-medium text-coffee-800">Telepon</p><p class="text-gray-500 text-sm">{{ $general['phone'] }}</p></div>
                        </div>
                    @endif
                    @if(!empty($general['phone2']))
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-coffee-100 rounded-xl flex items-center justify-center flex-shrink-0">📱</div>
                            <div><p class="font-medium text-coffee-800">Telepon 2</p><p class="text-gray-500 text-sm">{{ $general['phone2'] }}</p></div>
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

            {{-- Reservasi --}}
            <div class="bg-gradient-to-br from-coffee-700 to-coffee-800 rounded-2xl p-8 shadow-sm text-white">
                <h3 class="font-bold text-xl mb-3">📋 Reservasi</h3>
                <p class="text-coffee-200 text-sm mb-5">Untuk informasi lebih lanjut atau untuk reservasi, hubungi kami:</p>
                <div class="space-y-3">
                    <a href="tel:+6281371635845" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 rounded-xl p-3 transition">
                        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">📞</div>
                        <div>
                            <p class="font-bold text-sm">0813-7163-5845</p>
                            <p class="text-coffee-200 text-xs">Telepon / WhatsApp</p>
                        </div>
                    </a>
                    <a href="tel:+6281170855555" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 rounded-xl p-3 transition">
                        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">📱</div>
                        <div>
                            <p class="font-bold text-sm">0811-7085-555</p>
                            <p class="text-coffee-200 text-xs">Telepon / WhatsApp</p>
                        </div>
                    </a>
                    <a href="mailto:tencoffeofficial@gmail.com" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 rounded-xl p-3 transition">
                        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">📧</div>
                        <div>
                            <p class="font-bold text-sm">tencoffeofficial@gmail.com</p>
                            <p class="text-coffee-200 text-xs">Email resmi</p>
                        </div>
                    </a>
                </div>
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
