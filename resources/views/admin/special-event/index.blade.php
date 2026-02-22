@extends('layouts.admin')
@section('page-title', 'Event Spesial')
@section('content')

<div class="space-y-6">
    {{-- Settings --}}
    <div class="card">
        <h3 class="font-bold text-coffee-800 text-lg mb-4">⚙️ Pengaturan Event</h3>
        <form action="{{ route('admin.special-event.settings') }}" method="POST" class="space-y-4">
            @csrf
            <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <input type="checkbox" name="enabled" value="1" {{ ($settings['special_event_enabled'] ?? false) ? 'checked' : '' }} class="rounded border-coffee-300 text-coffee-600 w-5 h-5">
                <span class="font-medium">Tampilkan Event Spesial di Home</span>
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Emoji</label>
                    <input type="text" name="emoji" value="{{ $settings['special_event_emoji'] ?? '🌙' }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teks Badge</label>
                    <input type="text" name="badge" value="{{ $settings['special_event_badge'] ?? 'Ramadhan Kareem' }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Event</label>
                    <input type="text" name="title" value="{{ $settings['special_event_title'] ?? 'Menu Ramadhan' }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <input type="text" name="subtitle" value="{{ $settings['special_event_subtitle'] ?? '' }}" class="input-field">
                </div>
            </div>
            <button type="submit" class="btn-primary px-6 py-2 rounded-xl">Simpan Pengaturan</button>
        </form>
    </div>

    {{-- Upload --}}
    <div class="card">
        <h3 class="font-bold text-coffee-800 text-lg mb-2">➕ Tambah Menu Baru</h3>
        <p class="text-gray-500 text-sm mb-4">Upload gambar untuk menambahkan menu event baru. Setiap gambar akan menjadi satu item menu.</p>
        <form action="{{ route('admin.special-event.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="{ previews: [] }">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar (multiple)</label>
                    <input type="file" name="images[]" multiple accept="image/*" required class="input-field" @change="previews = Array.from($event.target.files).map(f => URL.createObjectURL(f))">
                    <div x-show="previews.length" class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(src, i) in previews" :key="i">
                            <img :src="src" class="w-16 h-16 rounded-lg object-cover border-2 border-coffee-200">
                        </template>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul / Nama Paket</label>
                    <input type="text" name="title" placeholder="Paket Ramadhan 1" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Isi Paket</label>
                    <textarea name="description" rows="2" placeholder="Nasi Putih, Ayam Goreng, dll" class="input-field"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (opsional)</label>
                    <input type="number" name="price" placeholder="0 = tanpa harga" class="input-field">
                </div>
            </div>
            <button type="submit" class="btn-primary px-6 py-2 rounded-xl">Upload</button>
        </form>
    </div>

    {{-- Gallery --}}
    <div class="card">
        <h3 class="font-bold text-coffee-800 text-lg mb-2">🖼️ Menu Event Saat Ini</h3>
        <p class="text-gray-500 text-sm mb-4">Kelola gambar, judul, deskripsi, dan harga menu yang sudah ada.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($galleries as $gallery)
                <div class="bg-gray-50 rounded-xl overflow-hidden" x-data="{ preview: null, removeImage: false }">
                    <form action="{{ route('admin.special-event.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="relative">
                            {{-- Preview: show new image or existing --}}
                            <template x-if="preview">
                                <img :src="preview" class="w-full aspect-square object-cover">
                            </template>
                            <template x-if="!preview">
                                <img src="{{ $gallery->image_url }}" class="w-full aspect-square object-cover">
                            </template>

                            {{-- Badge indicators --}}
                            <div x-show="preview" class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-lg" style="display:none;">✓ Foto baru</div>
                            <div x-show="removeImage && !preview" class="absolute inset-0 bg-black/50 flex items-center justify-center" style="display:none;">
                                <span class="text-white font-bold text-sm">Foto akan dihapus</span>
                            </div>

                            {{-- Buttons --}}
                            <div class="absolute bottom-2 right-2 flex gap-1">
                                <label class="bg-white/90 hover:bg-white text-coffee-700 text-xs font-medium px-3 py-1.5 rounded-lg cursor-pointer shadow transition">
                                    📷 Ganti
                                    <input type="file" name="image" accept="image/*" class="hidden" @change="preview=URL.createObjectURL($event.target.files[0]); removeImage=false">
                                </label>
                                @if($gallery->image)
                                <button type="button" @click="removeImage=!removeImage; if(removeImage) preview=null" :class="removeImage ? 'bg-red-500 text-white' : 'bg-white/90 text-red-500'" class="hover:bg-red-100 text-xs font-medium px-3 py-1.5 rounded-lg shadow transition">
                                    🗑️
                                </button>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="remove_image" :value="removeImage ? '1' : '0'">
                        <div class="p-3 space-y-2">
                            <input type="text" name="title" value="{{ $gallery->title }}" placeholder="Judul" class="input-field text-sm">
                            <textarea name="description" rows="2" placeholder="Deskripsi / isi paket" class="input-field text-xs">{{ $gallery->description }}</textarea>
                            <input type="number" name="price" value="{{ $gallery->price }}" placeholder="Harga" class="input-field text-sm">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_active" value="1" {{ $gallery->is_active ? 'checked' : '' }} class="rounded border-coffee-300 text-coffee-600">
                                <span class="text-xs text-gray-600">Aktif</span>
                            </label>
                            <div class="flex gap-2 pt-1">
                                <button type="submit" class="flex-1 text-center text-xs text-white bg-coffee-600 hover:bg-coffee-700 py-1.5 rounded-lg transition">Simpan</button>
                            </div>
                        </div>
                    </form>
                    <div class="px-3 pb-3">
                        <form action="{{ route('admin.special-event.destroy', $gallery) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                            @csrf @method('DELETE')
                            <button class="w-full text-center text-xs text-red-500 hover:text-red-700 py-1.5 border border-red-200 rounded-lg">Hapus Menu</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm col-span-full text-center py-4">Belum ada menu event</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
