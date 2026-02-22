@extends('layouts.admin')
@section('page-title', 'Produk')
@section('content')

<div class="card mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <form class="flex flex-wrap gap-2" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="input-field w-48">
            <select name="category_id" class="input-field w-40" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary px-4 rounded-lg text-sm">Cari</button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn-primary px-4 py-2 rounded-lg text-sm">+ Tambah Produk</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Gambar</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Nama</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Kategori</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Harga</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Status</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $productList = is_a($products, 'Illuminate\Pagination\LengthAwarePaginator') ? $products : $products; @endphp
                @forelse($productList as $product)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-2"><img src="{{ $product->image_url }}" class="w-12 h-12 rounded-lg object-cover"></td>
                        <td class="py-3 px-2 font-medium text-coffee-800">{{ $product->name }}</td>
                        <td class="py-3 px-2 text-gray-500">{{ $product->category->name ?? '-' }}</td>
                        <td class="py-3 px-2">
                            @if($product->has_variants)
                                <span class="text-red-500">H: Rp {{ number_format($product->price_hot, 0, ',', '.') }}</span><br>
                                <span class="text-blue-500">C: Rp {{ number_format($product->price_cold, 0, ',', '.') }}</span>
                            @else
                                {{ $product->formatted_price }}
                            @endif
                        </td>
                        <td class="py-3 px-2">
                            <div class="flex flex-wrap gap-1">
                                @if($product->is_active)<span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">Aktif</span>@else<span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">Nonaktif</span>@endif
                                @if($product->is_featured)<span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">Featured</span>@endif
                                @if($product->is_new)<span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">Baru</span>@endif
                                @if($product->is_promo)<span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">Promo</span>@endif
                            </div>
                        </td>
                        <td class="py-3 px-2">
                            <div class="flex gap-1">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-coffee-600 hover:text-coffee-800 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-gray-400">Belum ada produk</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(is_a($products, 'Illuminate\Pagination\LengthAwarePaginator'))
        <div class="mt-4">{{ $products->links() }}</div>
    @endif
</div>

@endsection
