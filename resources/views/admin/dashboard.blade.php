@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pesanan Hari Ini</p>
                <p class="text-3xl font-bold text-coffee-800 mt-1">{{ $todayOrders }}</p>
            </div>
            <div class="w-12 h-12 bg-coffee-100 rounded-2xl flex items-center justify-center text-2xl">📦</div>
        </div>
    </div>
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                <p class="text-3xl font-bold text-green-600 mt-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-2xl">💰</div>
        </div>
    </div>
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pendapatan Bulan Ini</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-2xl">📊</div>
        </div>
    </div>
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Produk Aktif</p>
                <p class="text-3xl font-bold text-coffee-800 mt-1">{{ $totalProducts }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-2xl">☕</div>
        </div>
    </div>
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Kategori</p>
                <p class="text-3xl font-bold text-coffee-800 mt-1">{{ $totalCategories }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-2xl">🏷️</div>
        </div>
    </div>
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pesanan Pending</p>
                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pendingOrders }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center text-2xl">⏳</div>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="card">
    <h3 class="font-bold text-coffee-800 text-lg mb-4">Pesanan Terbaru</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">No. Pesanan</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Pelanggan</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Total</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Status</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-2"><a href="{{ route('admin.orders.show', $order) }}" class="text-coffee-600 font-medium hover:underline">{{ $order->order_number }}</a></td>
                        <td class="py-3 px-2">{{ $order->customer_name }}</td>
                        <td class="py-3 px-2 font-medium">{{ $order->formatted_total }}</td>
                        <td class="py-3 px-2"><span class="px-2 py-1 rounded-full text-xs font-bold {{ $order->status_color }}">{{ $order->status_label }}</span></td>
                        <td class="py-3 px-2 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-gray-400">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
