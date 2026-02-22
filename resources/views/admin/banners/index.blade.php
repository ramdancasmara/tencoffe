@extends('layouts.admin')
@section('page-title', 'Banner')
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Form --}}
    <div class="lg:col-span-1">
        <div class="card">
            <h3 class="font-bold text-coffee-800 mb-4">{{ isset($banner) ? 'Edit Banner' : 'Tambah Banner' }}</h3>
            <form action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
                  method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @if(isset($banner)) @method('PUT') @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label>
                    <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar {{ isset($banner) ? '' : '*' }} (Max 4MB)</label>
                    @if(isset($banner) && $banner->image)
                        <img src="{{ $banner->image_url }}" class="w-full h-24 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="image" accept="image/*" {{ isset($banner) ? '' : 'required' }} class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                    <input type="text" name="link" value="{{ old('link', $banner->link ?? '') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="input-field">
                </div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }} class="rounded border-coffee-300 text-coffee-600">
                    <span class="text-sm">Aktif</span>
                </label>
                <button type="submit" class="btn-primary w-full py-2 rounded-xl">{{ isset($banner) ? 'Simpan' : 'Tambah' }}</button>
                @if(isset($banner))
                    <a href="{{ route('admin.banners.index') }}" class="btn-outline w-full py-2 rounded-xl text-center block">Batal</a>
                @endif
            </form>
        </div>
    </div>

    {{-- List --}}
    <div class="lg:col-span-2">
        <div class="card">
            <h3 class="font-bold text-coffee-800 mb-4">Daftar Banner</h3>
            <div class="space-y-3">
                @forelse($banners as $b)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <img src="{{ $b->image_url }}" class="w-24 h-12 rounded-lg object-cover">
                            <div>
                                <p class="font-medium text-coffee-800">{{ $b->title }}</p>
                                @if($b->subtitle)<p class="text-xs text-gray-400">{{ $b->subtitle }}</p>@endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs {{ $b->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} px-2 py-1 rounded-full">{{ $b->is_active ? 'Aktif' : 'Off' }}</span>
                            <a href="{{ route('admin.banners.edit', $b) }}" class="text-coffee-600 hover:text-coffee-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('admin.banners.destroy', $b) }}" method="POST" onsubmit="return confirm('Hapus banner?')">@csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">Belum ada banner</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
