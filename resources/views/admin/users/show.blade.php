@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 transition-colors">Pengguna</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">{{ $user->name }}</span>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  <!-- Left Column -->
  <div class="lg:col-span-1 space-y-6">
    <!-- Profil Card -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 text-center">
       <div class="w-24 h-24 mx-auto rounded-full border-[3px] border-indigo-50 flex items-center justify-center bg-gray-100 overflow-hidden mb-4 relative">
          @if($user->avatar)
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
          @else
            <i class="fa-solid fa-user text-4xl text-gray-300"></i>
          @endif
       </div>
       <h2 class="text-xl font-bold text-neutral-900 leading-tight mb-1">{{ $user->name }}</h2>
       <p class="text-xs text-gray-500 font-medium mb-3">{{ $user->email }}</p>
       
       <div class="flex items-center justify-center gap-2 mb-6">
          @if($user->role === \App\Models\User::ROLE_ADMIN)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-100 text-red-700">
               <i class="fa-solid fa-crown text-[10px]"></i> Admin
            </span>
          @elseif($user->role === \App\Models\User::ROLE_STAFF)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-700">
               <i class="fa-solid fa-user-tie text-[10px]"></i> Staff
            </span>
          @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
               <i class="fa-solid fa-user text-[10px]"></i> Pelanggan
            </span>
          @endif
       </div>

       <div class="border-t border-zinc-100 pt-4 grid grid-cols-2 gap-4">
          <div class="text-center border-r border-zinc-100">
             <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-0.5">Total Pesanan</p>
             <p class="text-2xl font-bold text-neutral-900">{{ $user->orders_count }}</p>
          </div>
          <div class="text-center">
             <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-0.5">Terdaftar</p>
             <p class="text-sm font-bold text-neutral-900 mt-2">{{ $user->created_at->format('M Y') }}</p>
          </div>
       </div>
    </div>

    <!-- Informasi Pribadi -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
       <h3 class="text-sm font-bold text-neutral-900 mb-4 pb-3 border-b border-zinc-100 flex items-center gap-2">
         <i class="fa-solid fa-address-card text-gray-400"></i> Informasi Pribadi
       </h3>
       <ul class="space-y-4 text-sm">
          <li>
             <p class="text-xs font-bold uppercase text-gray-400 mb-1">Nama Lengkap</p>
             <p class="font-medium text-neutral-900">{{ $user->name }}</p>
          </li>
          <li>
             <p class="text-xs font-bold uppercase text-gray-400 mb-1">Email</p>
             <p class="font-medium text-neutral-900 break-all">{{ $user->email }}
               @if($user->email_verified_at)
                  <i class="fa-solid fa-circle-check text-green-500 ml-1 text-xs" title="Email Terverifikasi"></i>
               @endif
             </p>
          </li>
          <li>
             <p class="text-xs font-bold uppercase text-gray-400 mb-1">Nomor Telepon</p>
             <p class="font-medium text-neutral-900">
                {{ $user->phone ?? 'Belum ditambahkan' }}
             </p>
          </li>
       </ul>
    </div>
  </div>

  <!-- Right Column -->
  <div class="lg:col-span-2 space-y-6">
    <!-- Riwayat Transaksi -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
       <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between">
          <h2 class="text-lg font-bold text-neutral-900">Riwayat Pesanan Terakhir</h2>
          @if($user->orders_count > 0)
            <a href="{{ route('admin.orders.index', ['search' => $user->email]) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">Lihat Seluruh Pesanan</a>
          @endif
       </div>
       
       <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-neutral-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 border-b border-zinc-100">ID Pesanan</th>
                <th class="px-6 py-4 border-b border-zinc-100">Tanggal</th>
                <th class="px-6 py-4 border-b border-zinc-100 text-center">Status</th>
                <th class="px-6 py-4 border-b border-zinc-100 text-right">Total</th>
              </tr>
            </thead>
            <tbody class="text-sm font-medium text-neutral-800 divide-y divide-zinc-100">
              @forelse($user->orders as $order)
                <tr class="hover:bg-neutral-50/50 transition">
                  <td class="px-6 py-4">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="font-bold text-indigo-600 hover:underline">
                      #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </a>
                  </td>
                  <td class="px-6 py-4 text-gray-500">
                    {{ $order->created_at->format('d M Y, H:i') }}
                  </td>
                  <td class="px-6 py-4 text-center">
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
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $statusColor }}">
                       {{ $statusLabel }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right font-extrabold text-neutral-900">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                  </td>
                </tr>
              @empty
                <tr>
                   <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                      <div class="w-12 h-12 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-2 text-xl">
                        <i class="fa-solid fa-box-open"></i>
                      </div>
                      <p class="font-semibold text-neutral-900 mb-1">Belum Ada Transaksi</p>
                      <p class="text-xs">Pengguna ini belum pernah membuat pesanan.</p>
                   </td>
                </tr>
              @endforelse
            </tbody>
          </table>
       </div>
    </div>
  </div>

</div>
@endsection
