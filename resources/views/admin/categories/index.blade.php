@extends('layouts.admin')
@section('page-title', 'Kategori')
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Form --}}
    <div class="lg:col-span-1">
        <div class="card">
            <h3 class="font-bold text-coffee-800 mb-4">{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
            <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                  method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @if(isset($category)) @method('PUT') @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" class="input-field">{{ old('description', $category->description ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                    <input type="file" name="image" accept="image/*" class="input-field">
                </div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} class="rounded border-coffee-300 text-coffee-600">
                    <span class="text-sm">Aktif</span>
                </label>
                <button type="submit" class="btn-primary w-full py-2 rounded-xl">{{ isset($category) ? 'Simpan' : 'Tambah' }}</button>
                @if(isset($category))
                    <a href="{{ route('admin.categories.index') }}" class="btn-outline w-full py-2 rounded-xl text-center block">Batal</a>
                @endif
            </form>
        </div>
    </div>

    {{-- List --}}
    <div class="lg:col-span-2">
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-coffee-800">Daftar Kategori</h3>
                <form method="GET" class="flex items-center gap-2">
                    <label class="text-sm text-gray-500">Tampilkan:</label>
                    <select name="per_page" onchange="this.form.submit()" class="input-field w-auto text-sm py-1 px-2">
                        @foreach([10, 25, 50] as $pp)
                            <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                        @endforeach
                        <option value="all" {{ $perPage === 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                </form>
            </div>
            <div class="space-y-3">
                @forelse($categories as $cat)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <img src="{{ $cat->image_url }}" class="w-10 h-10 rounded-lg object-cover">
                            <div>
                                <p class="font-medium text-coffee-800">{{ $cat->name }}</p>
                                <p class="text-xs text-gray-400">{{ $cat->products_count }} produk</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} px-2 py-1 rounded-full">{{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="text-coffee-600 hover:text-coffee-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">Belum ada kategori</p>
                @endforelse
            </div>

            @if($perPage !== 'all' && $categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-4">{{ $categories->links() }}</div>
            @endif
        </div>
    </div>
</div>

@endsection
