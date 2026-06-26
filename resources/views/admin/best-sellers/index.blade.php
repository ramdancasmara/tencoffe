@extends('layouts.admin')
@section('page-title', 'Best Seller')
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Panel kiri: Best Seller aktif --}}
    <div class="card">
        <h3 class="font-bold text-coffee-800 text-lg mb-1">⭐ Best Seller Saat Ini</h3>
        <p class="text-sm text-gray-500 mb-4">Atur urutan dengan mengubah angka, lalu klik Simpan Urutan.</p>

        @if($featured->isEmpty())
            <p class="text-center text-gray-400 py-8">Belum ada produk Best Seller.</p>
        @else
            <form action="{{ route('admin.best-sellers.order') }}" method="POST">
                @csrf
                <div class="space-y-2 mb-4">
                    @foreach($featured as $product)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <input type="number" name="orders[{{ $product->id }}]"
                                   value="{{ $product->sort_order }}" min="0"
                                   class="w-16 text-center text-sm border border-gray-200 rounded-lg py-1 px-2 focus:outline-none focus:ring-1 focus:ring-coffee-400">
                            <img src="{{ $product->image_url }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-coffee-800 text-sm truncate">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $product->category->name ?? '-' }} · {{ $product->formatted_price }}</p>
                            </div>
                            <form action="{{ route('admin.best-sellers.remove', $product) }}" method="POST"
                                  onsubmit="return confirm('Hapus {{ $product->name }} dari Best Seller?')">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Hapus dari Best Seller">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="btn-primary w-full py-2 rounded-xl text-sm">💾 Simpan Urutan</button>
            </form>
        @endif
    </div>

    {{-- Panel kanan: Tambah produk ke Best Seller --}}
    <div class="card">
        <h3 class="font-bold text-coffee-800 text-lg mb-1">➕ Tambah ke Best Seller</h3>
        <p class="text-sm text-gray-500 mb-4">Cari produk dan tambahkan ke daftar Best Seller.</p>

        <form method="GET" class="flex gap-2 mb-4">
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari nama produk..." class="input-field flex-1">
            <button type="submit" class="btn-primary px-4 rounded-lg text-sm">Cari</button>
            @if($search)
                <a href="{{ route('admin.best-sellers.index') }}" class="btn-outline px-3 rounded-lg text-sm">✕</a>
            @endif
        </form>

        <div class="space-y-2">
            @forelse($available as $product)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <img src="{{ $product->image_url }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-coffee-800 text-sm truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $product->category->name ?? '-' }} · {{ $product->formatted_price }}</p>
                    </div>
                    <form action="{{ route('admin.best-sellers.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="bg-coffee-600 hover:bg-coffee-700 text-white text-xs px-3 py-1.5 rounded-lg transition">
                            + Tambah
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-center text-gray-400 py-6">
                    {{ $search ? 'Produk tidak ditemukan.' : 'Semua produk sudah masuk Best Seller.' }}
                </p>
            @endforelse
        </div>

        @if($available->hasPages())
            <div class="mt-4">{{ $available->links() }}</div>
        @endif
    </div>

</div>

@endsection
