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
        <h3 class="font-bold text-coffee-800 text-lg mb-4">📤 Upload Gambar</h3>
        <form action="{{ route('admin.special-event.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar (multiple)</label>
                    <input type="file" name="images[]" multiple accept="image/*" required class="input-field">
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
        <h3 class="font-bold text-coffee-800 text-lg mb-4">🖼️ Gallery Gambar</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($galleries as $gallery)
                <div class="bg-gray-50 rounded-xl overflow-hidden">
                    <img src="{{ $gallery->image_url }}" class="w-full aspect-square object-cover">
                    <div class="p-3">
                        <p class="font-medium text-coffee-800 text-sm">{{ $gallery->title ?? 'Tanpa judul' }}</p>
                        @if($gallery->description)
                            <p class="text-gray-500 text-xs mt-1 line-clamp-2">{{ $gallery->description }}</p>
                        @endif
                        @if($gallery->price)<p class="text-coffee-600 font-bold text-sm mt-1">{{ $gallery->formatted_price }}</p>@endif
                        <span class="text-xs {{ $gallery->is_active ? 'text-green-600' : 'text-gray-400' }}">{{ $gallery->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        <div class="flex gap-2 mt-2">
                            <form action="{{ route('admin.special-event.destroy', $gallery) }}" method="POST" onsubmit="return confirm('Hapus gambar ini?')" class="flex-1">
                                @csrf @method('DELETE')
                                <button class="w-full text-center text-xs text-red-500 hover:text-red-700 py-1 border border-red-200 rounded-lg">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm col-span-full text-center py-4">Belum ada gambar</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
