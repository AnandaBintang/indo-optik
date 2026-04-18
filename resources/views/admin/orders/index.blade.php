@extends('layouts.admin')

@section('title', 'Daftar Pesanan')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Pesanan</span>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col">
  
  <!-- Status Tabs -->
  <div class="border-b border-zinc-100 px-6 pt-4 flex gap-6 overflow-x-auto no-scrollbar">
    <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" 
       class="pb-4 text-sm font-bold whitespace-nowrap {{ !request('status') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-neutral-900' }}">
      Semua Pesanan 
      <span class="ml-1.5 inline-flex items-center justify-center bg-gray-100 text-gray-600 text-[10px] w-5 h-5 rounded-full">{{ $statusCounts['all'] ?? 0 }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" 
       class="pb-4 text-sm font-bold whitespace-nowrap {{ request('status') === 'pending' ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-neutral-900' }}">
      Pending 
      <span class="ml-1.5 inline-flex items-center justify-center bg-amber-100 text-amber-700 text-[10px] w-5 h-5 rounded-full">{{ $statusCounts['pending'] ?? 0 }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'processing']) }}" 
       class="pb-4 text-sm font-bold whitespace-nowrap {{ request('status') === 'processing' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-neutral-900' }}">
      Diproses 
      <span class="ml-1.5 inline-flex items-center justify-center bg-blue-100 text-blue-700 text-[10px] w-5 h-5 rounded-full">{{ $statusCounts['processing'] ?? 0 }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}" 
       class="pb-4 text-sm font-bold whitespace-nowrap {{ request('status') === 'completed' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-neutral-900' }}">
      Selesai 
      <span class="ml-1.5 inline-flex items-center justify-center bg-green-100 text-green-700 text-[10px] w-5 h-5 rounded-full">{{ $statusCounts['completed'] ?? 0 }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}" 
       class="pb-4 text-sm font-bold whitespace-nowrap {{ request('status') === 'cancelled' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-neutral-900' }}">
      Dibatalkan 
      <span class="ml-1.5 inline-flex items-center justify-center bg-red-100 text-red-700 text-[10px] w-5 h-5 rounded-full">{{ $statusCounts['cancelled'] ?? 0 }}</span>
    </a>
  </div>

  <div class="px-6 py-5 border-b border-zinc-100 flex flex-col lg:flex-row gap-4 justify-between bg-neutral-50/50">
    <!-- Filter & Search Form -->
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3 w-full">
      @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
      @endif
      
      <!-- Search -->
      <div class="relative w-full sm:max-w-xs flex-shrink-0">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </div>
        <input 
          type="text" 
          name="search" 
          value="{{ request('search') }}"
          placeholder="Cari nama, telp, ID..." 
          class="w-full bg-white text-neutral-900 rounded-xl py-2.5 pl-9 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all placeholder:text-gray-400"
        >
      </div>

      <!-- Tipe Pengiriman -->
      <select name="delivery_type" class="bg-white text-neutral-900 rounded-xl py-2.5 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all sm:w-[160px]" onchange="this.form.submit()">
        <option value="">Semua Metode</option>
        <option value="pickup" {{ request('delivery_type') === 'pickup' ? 'selected' : '' }}>Ambil di Toko</option>
        <option value="delivery" {{ request('delivery_type') === 'delivery' ? 'selected' : '' }}>Pengiriman</option>
      </select>

      <!-- Tanggal -->
      <div class="flex items-center gap-2">
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-white text-neutral-900 rounded-xl py-2.5 px-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all w-36">
        <span class="text-gray-400 text-xs">-</span>
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-white text-neutral-900 rounded-xl py-2.5 px-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all w-36">
      </div>

      <button type="submit" class="btn btn-outline btn-sm bg-white border-zinc-200 text-gray-600 hover:bg-gray-50 flex-shrink-0 shrink">
        Filter
      </button>
      
      @if(request()->hasAny(['search', 'delivery_type', 'date_from', 'date_to', 'status']))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm text-red-500 hover:bg-red-50 hover:text-red-600 border-transparent shadow-none px-3 flex-shrink-0">
          Reset
        </a>
      @endif
    </form>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-white text-xs font-bold text-gray-500 uppercase tracking-wider">
          <th class="px-6 py-4 border-b border-zinc-100">ID Pesanan</th>
          <th class="px-6 py-4 border-b border-zinc-100">Pelanggan</th>
          <th class="px-6 py-4 border-b border-zinc-100">Item</th>
          <th class="px-6 py-4 border-b border-zinc-100">Metode</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-center">Status</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-right">Total (Rp)</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-sm font-medium text-neutral-800 divide-y divide-zinc-100">
        @forelse($orders as $order)
          <tr class="hover:bg-neutral-50/50 transition">
            <td class="px-6 py-4">
              <a href="{{ route('admin.orders.show', $order->id) }}" class="font-bold text-indigo-600 hover:underline">
                #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
              </a>
              <p class="text-[11px] text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </td>
            
            <td class="px-6 py-4">
              <p class="font-bold text-neutral-900">{{ $order->customer_name }}</p>
              <div class="flex items-center gap-2 mt-0.5">
                 <i class="fa-brands fa-whatsapp text-green-500 text-xs"></i>
                 <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="text-xs text-gray-500 hover:text-green-600">{{ $order->customer_phone }}</a>
              </div>
            </td>

            <td class="px-6 py-4">
               <span class="inline-flex items-center justify-center bg-gray-100 text-gray-700 w-6 h-6 rounded-md font-bold text-xs mr-2">
                 {{ $order->items->sum('quantity') }}
               </span>
               <span class="text-gray-500 text-[13px]">Benda</span>
            </td>

            <td class="px-6 py-4">
              @if($order->delivery_type === 'pickup')
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-violet-700 bg-violet-100 px-2 py-1 rounded-md">
                  <i class="fa-solid fa-store"></i> Ambil Toko
                </span>
              @else
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-700 bg-sky-100 px-2 py-1 rounded-md">
                  <i class="fa-solid fa-truck"></i> Kirim Kurir
                </span>
              @endif
            </td>

            <td class="px-6 py-4 text-center">
              @php
                $statusColor = match($order->status) {
                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'completed' => 'bg-green-100 text-green-700 border-green-200',
                    'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                    default => 'bg-gray-100 text-gray-700 border-gray-200'
                };
                
                $statusLabel = match($order->status) {
                    'pending' => 'Pending',
                    'processing' => 'Diproses',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => ucfirst($order->status)
                };
              @endphp
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider border {{ $statusColor }}">
                {{ $statusLabel }}
              </span>
            </td>

            <td class="px-6 py-4 text-right">
              <span class="font-extrabold text-neutral-900">
                {{ number_format($order->total, 0, ',', '.') }}
              </span>
            </td>

            <td class="px-6 py-4 text-right">
              <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-colors" title="Lihat Detail">
                <i class="fa-solid fa-eye"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
             <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-3 text-2xl">
                  <i class="fa-solid fa-box-open"></i>
                </div>
                <p class="font-bold text-neutral-900 mb-1">Pencarian Tidak Ditemukan</p>
                <p class="text-sm">Silakan ubah filter pencarian pesanan Anda.</p>
             </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100 bg-neutral-50/30">
      {{ $orders->links() }}
    </div>
  @endif
</div>
@endsection
