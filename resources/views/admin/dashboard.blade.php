@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Dashboard</span>
@endsection

@section('content')
<div class="space-y-6">

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total Revenue -->
    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
        <i class="fa-solid fa-money-bill-wave"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-0.5 sm:mb-1 truncate">Total Pendapatan</p>
        <h3 class="text-lg sm:text-2xl font-bold text-neutral-900 leading-none truncate">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
      </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
        <i class="fa-solid fa-cart-shopping"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-0.5 sm:mb-1 truncate">Total Pesanan</p>
        <h3 class="text-lg sm:text-2xl font-bold text-neutral-900 leading-none truncate">{{ number_format($totalOrders, 0, ',', '.') }}</h3>
      </div>
    </div>

    <!-- Total Products -->
    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
        <i class="fa-solid fa-glasses"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-0.5 sm:mb-1 truncate">Katalog Produk</p>
        <h3 class="text-lg sm:text-2xl font-bold text-neutral-900 leading-none truncate">{{ number_format($totalProducts, 0, ',', '.') }}</h3>
      </div>
    </div>

    <!-- Total Users -->
    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
        <i class="fa-solid fa-users"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-0.5 sm:mb-1 truncate">Total Pelanggan</p>
        <h3 class="text-lg sm:text-2xl font-bold text-neutral-900 leading-none truncate">{{ number_format($totalUsers, 0, ',', '.') }}</h3>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Orders Table -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col">
      <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-neutral-900">Pesanan Terbaru</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">Lihat Semua</a>
      </div>
      <div class="overflow-x-auto flex-1">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-neutral-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider">
              <th class="px-6 py-4 border-b border-zinc-100">ID Pesanan</th>
              <th class="px-6 py-4 border-b border-zinc-100">Pelanggan</th>
              <th class="px-6 py-4 border-b border-zinc-100 hidden md:table-cell">Tanggal</th>
              <th class="px-6 py-4 border-b border-zinc-100">Status</th>
              <th class="px-6 py-4 border-b border-zinc-100 text-right">Total</th>
            </tr>
          </thead>
          <tbody class="text-sm font-medium text-neutral-800 divide-y divide-zinc-100">
            @forelse($recentOrders as $order)
              <tr class="hover:bg-neutral-50/50 transition">
                <td class="px-6 py-4">
                  <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:underline font-bold">
                    #{{ $order->order_number }}
                  </a>
                </td>
                <td class="px-6 py-4">
                  {{ $order->customer_name }}
                  @if($order->user)
                    <div class="text-xs text-gray-400 font-normal">{{ $order->user->email }}</div>
                  @endif
                </td>
                <td class="px-6 py-4 hidden md:table-cell text-gray-500">
                  {{ $order->created_at->format('d M Y') }}
                </td>
                <td class="px-6 py-4">
                  @php
                    $statusColor = match($order->status) {
                        'pending' => 'bg-amber-100 text-amber-700',
                        'processing' => 'bg-blue-100 text-blue-700',
                        'completed' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                        default => 'bg-gray-100 text-gray-700'
                    };
                    
                    $statusLabel = match($order->status) {
                        'pending' => 'Pending',
                        'processing' => 'Diproses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($order->status)
                    };
                  @endphp
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $statusColor }}">
                    {{ $statusLabel }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right font-bold text-neutral-900">
                  Rp {{ number_format($order->total, 0, ',', '.') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                  Belum ada pesanan terbaru.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Order Status Summary -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm flex flex-col">
      <div class="px-6 py-5 border-b border-zinc-100">
        <h2 class="text-lg font-bold text-neutral-900">Ringkasan Status</h2>
      </div>
      <div class="p-6 flex-1 flex flex-col justify-center">
        <div class="space-y-5">
          <div>
            <div class="flex justify-between text-sm font-bold mb-1.5">
              <span class="text-amber-600">Pending</span>
              <span class="text-neutral-900">{{ $pendingOrders }}</span>
            </div>
            <div class="w-full bg-amber-100 rounded-full h-2">
              <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $totalOrders > 0 ? ($pendingOrders / $totalOrders) * 100 : 0 }}%"></div>
            </div>
          </div>
          
          <div>
            <div class="flex justify-between text-sm font-bold mb-1.5">
              <span class="text-blue-600">Diproses</span>
              <span class="text-neutral-900">{{ $processingOrders }}</span>
            </div>
            <div class="w-full bg-blue-100 rounded-full h-2">
              <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalOrders > 0 ? ($processingOrders / $totalOrders) * 100 : 0 }}%"></div>
            </div>
          </div>
          
          <div>
            <div class="flex justify-between text-sm font-bold mb-1.5">
              <span class="text-green-600">Selesai</span>
              <span class="text-neutral-900">{{ $completedOrders }}</span>
            </div>
            <div class="w-full bg-green-100 rounded-full h-2">
              <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0 }}%"></div>
            </div>
          </div>
          
          <div>
            <div class="flex justify-between text-sm font-bold mb-1.5">
              <span class="text-red-600">Dibatalkan</span>
              <span class="text-neutral-900">{{ $cancelledOrders }}</span>
            </div>
            <div class="w-full bg-red-100 rounded-full h-2">
              <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalOrders > 0 ? ($cancelledOrders / $totalOrders) * 100 : 0 }}%"></div>
            </div>
          </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-zinc-100 grid grid-cols-2 gap-4">
          <div class="text-center">
            <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider mb-1">Testimoni</p>
            <p class="text-xl font-bold text-neutral-900">{{ $totalTestimonials }}</p>
          </div>
          <div class="text-center border-l border-zinc-100">
            <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider mb-1">Total Pesanan</p>
            <p class="text-xl font-bold text-indigo-600">{{ $totalOrders }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
