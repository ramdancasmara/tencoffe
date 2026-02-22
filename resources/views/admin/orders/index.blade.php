@extends('layouts.admin')
@section('page-title', 'Pesanan')
@section('content')

<div class="card">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <form class="flex flex-wrap gap-2" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesanan..." class="input-field w-52">
            <select name="status" class="input-field w-36" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['pending','paid','processing','ready','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary px-4 rounded-lg text-sm">Cari</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">No. Pesanan</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Pelanggan</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Tipe</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Total</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Bayar</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Status</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Tanggal</th>
                    <th class="text-left py-3 px-2 text-gray-500 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-2"><a href="{{ route('admin.orders.show', $order) }}" class="text-coffee-600 font-medium hover:underline">{{ $order->order_number }}</a></td>
                        <td class="py-3 px-2">
                            <p class="font-medium">{{ $order->customer_name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->customer_phone }}</p>
                        </td>
                        <td class="py-3 px-2 text-xs">{{ ucfirst(str_replace('-', ' ', $order->order_type)) }}</td>
                        <td class="py-3 px-2 font-medium">{{ $order->formatted_total }}</td>
                        <td class="py-3 px-2 text-xs">{{ ucfirst($order->payment_method) }}</td>
                        <td class="py-3 px-2"><span class="px-2 py-1 rounded-full text-xs font-bold {{ $order->status_color }}">{{ $order->status_label }}</span></td>
                        <td class="py-3 px-2 text-gray-500 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-2">
                            <div class="flex gap-1">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-coffee-600 hover:text-coffee-800 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-8 text-center text-gray-400">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>

@endsection
