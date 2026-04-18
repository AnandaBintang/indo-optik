@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . str_pad($order->id, 5, '0', STR_PAD_LEFT))

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <a href="{{ route('admin.orders.index') }}" class="hover:text-indigo-600 transition-colors">Pesanan</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  <!-- Kolom Kiri: Info Pesanan & Item -->
  <div class="lg:col-span-2 space-y-6">
    
    <!-- Detail Item -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden text-sm relative">
      <!-- Decor -->
      <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
        <i class="fa-solid fa-bag-shopping text-9xl"></i>
      </div>

      <div class="p-6 md:p-8 border-b border-zinc-100 flex items-center justify-between relative z-10">
        <div>
          <h2 class="text-lg font-bold text-neutral-900 mb-1">Rincian Pembelian</h2>
          <p class="text-gray-500 font-medium">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="text-right">
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
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusColor }}">
              {{ $statusLabel }}
            </span>
        </div>
      </div>

      <div class="p-6 md:p-8">
        <div class="space-y-4">
          @foreach($order->items as $item)
            <div class="flex gap-4 items-center">
              <div class="w-16 h-16 bg-neutral-50 rounded-xl border border-zinc-100 overflow-hidden shrink-0 flex items-center justify-center">
                @if($item->product && $item->product->image)
                  <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                @else
                  <i class="fa-solid fa-image text-gray-300 text-xl"></i>
                @endif
              </div>
              <div class="flex-1">
                <p class="font-bold text-neutral-900 leading-tight">{{ $item->product_name }}</p>
                @if($item->product && $item->product->sku)
                  <p class="text-[11px] text-gray-400 font-mono mt-0.5">{{ $item->product->sku }}</p>
                @endif
              </div>
              <div class="text-right">
                <p class="font-medium text-gray-500 mb-1">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                <p class="font-extrabold text-neutral-900">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
              </div>
            </div>
          @endforeach
        </div>

        <hr class="my-6 border-zinc-100">

        <!-- Kalkulasi -->
        <div class="space-y-3 ms-auto md:w-2/3 lg:w-1/2 text-sm font-medium">
          <div class="flex justify-between text-gray-600">
            <span>Subtotal</span>
            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
          </div>
          @if($order->discount > 0)
            <div class="flex justify-between text-indigo-500 font-bold border-b border-zinc-100 pb-3">
              <span>Diskon @if($order->promoCode) ({{ $order->promoCode->code }}) @endif</span>
              <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
            </div>
          @endif
          <div class="flex justify-between text-base font-extrabold text-neutral-900 pt-1">
            <span>Total Bayar</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>
    </div>
    
  </div>

  <!-- Kolom Kanan: Info Pelanggan & Form Status -->
  <div class="space-y-6">

    <!-- Update Status Card -->
    <div class="bg-indigo-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden">
      <!-- Decor -->
      <div class="absolute -bottom-6 -right-6 text-indigo-800 opacity-50 z-0">
        <i class="fa-solid fa-truck-fast text-9xl"></i>
      </div>

      <h2 class="text-lg font-bold mb-4 relative z-10 flex items-center gap-2">
        <i class="fa-solid fa-list-check opacity-70"></i> Update Status
      </h2>
      
      <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="relative z-10">
        @csrf
        @method('PATCH')
        
        <div class="space-y-4">
          <div>
            <select name="status" class="w-full bg-indigo-950/50 text-white rounded-xl py-3 px-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-400 border border-indigo-700/50 appearance-none">
              <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses (Preparing)</option>
              <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
              <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
          </div>
          
          <div>
            <textarea name="notes" rows="2" placeholder="Catatan internal (opsional)..." class="w-full bg-indigo-950/50 text-white rounded-xl py-3 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-400 border border-indigo-700/50 placeholder:text-indigo-300"></textarea>
          </div>
          
          <button type="submit" class="w-full bg-white text-indigo-900 hover:bg-indigo-50 font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-900/50">
            Perbarui Status
          </button>
        </div>
      </form>
    </div>

    <!-- Info Pelanggan -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
      <h2 class="text-base font-bold text-neutral-900 mb-5 flex items-center gap-2 pb-4 border-b border-zinc-100">
        <i class="fa-solid fa-user text-gray-400"></i> Info Pelanggan
      </h2>
      
      <div class="space-y-4 text-sm">
        <div>
          <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Nama</p>
          <p class="font-bold text-neutral-900">{{ $order->customer_name }}</p>
          @if($order->user)
            <p class="text-indigo-600 text-xs mt-0.5"><i class="fa-solid fa-circle-check"></i> Member Terdaftar</p>
          @endif
        </div>
        
        <div>
          <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Telepon / WhatsApp</p>
          <div class="flex items-center gap-2">
            <p class="font-bold text-neutral-900">{{ $order->customer_phone }}</p>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="w-6 h-6 rounded bg-[#25D366]/10 text-[#25D366] flex items-center justify-center hover:bg-[#25D366] hover:text-white transition">
              <i class="fa-brands fa-whatsapp"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Info Pengiriman -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
      <h2 class="text-base font-bold text-neutral-900 mb-5 flex items-center gap-2 pb-4 border-b border-zinc-100">
        <i class="fa-solid fa-truck-fast text-gray-400"></i> Metode & Pengiriman
      </h2>
      
      <div class="space-y-4 text-sm">
        <div>
          <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-2">Tipe</p>
          @if($order->delivery_type === 'pickup')
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-violet-700 bg-violet-100 px-3 py-1.5 rounded-lg border border-violet-200">
              <i class="fa-solid fa-store"></i> Ambil di Toko
            </span>
          @else
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-700 bg-sky-100 px-3 py-1.5 rounded-lg border border-sky-200">
              <i class="fa-solid fa-truck"></i> Pengiriman Kurir
            </span>
          @endif
        </div>
        
        @if($order->delivery_type === 'delivery')
          <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Alamat Tujuan</p>
            <p class="font-medium text-neutral-700 leading-relaxed bg-neutral-50 p-3 rounded-xl border border-zinc-100">
              {{ $order->customer_address ?: 'Tidak ada alamat tercatat.' }}
            </p>
          </div>
        @endif

        @if($order->customer_notes)
          <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Catatan Pemesan</p>
            <div class="p-3 bg-amber-50 rounded-xl border border-amber-100/50 text-amber-900">
              <p class="font-medium italic">"{{ $order->customer_notes }}"</p>
            </div>
          </div>
        @endif
      </div>
    </div>

  </div>

</div>
@endsection
