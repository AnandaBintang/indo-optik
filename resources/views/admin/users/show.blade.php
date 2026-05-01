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

  <div class="lg:col-span-1 space-y-6">
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 text-center">
       <div class="w-24 h-24 mx-auto rounded-full border-[3px] border-indigo-50 flex items-center justify-center bg-gray-100 overflow-hidden mb-4 relative">
          @if($user->avatar)
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
          @else
            <i class="fa-solid fa-user text-4xl text-gray-300"></i>
          @endif
       </div>
       <h2 class="text-xl font-bold text-neutral-900 leading-tight mb-1">{{ $user->name }}</h2>
       <p class="text-xs text-gray-500 font-medium mb-3 break-all">{{ $user->email }}</p>
       
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
             <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-0.5">Status Email</p>
             <p class="text-sm font-bold {{ $user->email_verified_at ? 'text-emerald-600' : 'text-amber-600' }} mt-2">
               {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
             </p>
          </div>
          <div class="text-center">
             <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-0.5">Terdaftar</p>
             <p class="text-sm font-bold text-neutral-900 mt-2">{{ $user->created_at->format('M Y') }}</p>
          </div>
       </div>
    </div>

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
             <p class="font-medium text-neutral-900">{{ $user->phone ?? 'Belum ditambahkan' }}</p>
          </li>
       </ul>
    </div>
  </div>

  <div class="lg:col-span-2 space-y-6">
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
       <div class="px-6 py-5 border-b border-zinc-100">
          <h2 class="text-lg font-bold text-neutral-900">Profil Pelanggan</h2>
          <p class="text-sm text-gray-500 mt-1">Tidak ada riwayat transaksi internal karena checkout diarahkan langsung ke WhatsApp.</p>
       </div>
       <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-5">
            <div class="w-11 h-11 rounded-xl bg-emerald-500 text-white flex items-center justify-center mb-4 text-xl">
              <i class="fa-brands fa-whatsapp"></i>
            </div>
            <h3 class="font-bold text-neutral-900 mb-2">Transaksi via WhatsApp</h3>
            <p class="text-sm text-gray-600 leading-relaxed">Pesanan pelanggan dikonfirmasi langsung lewat WhatsApp, bukan disimpan sebagai order di dashboard.</p>
          </div>
          <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-5">
            <div class="w-11 h-11 rounded-xl bg-indigo-500 text-white flex items-center justify-center mb-4 text-xl">
              <i class="fa-solid fa-user-shield"></i>
            </div>
            <h3 class="font-bold text-neutral-900 mb-2">Kelola Akses</h3>
            <p class="text-sm text-gray-600 leading-relaxed">Gunakan halaman pengguna untuk mengatur role admin, staff, atau pelanggan sesuai kebutuhan operasional toko.</p>
          </div>
       </div>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
      <h2 class="text-lg font-bold text-neutral-900 mb-4">Ringkasan Akun</h2>
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
        <div class="rounded-2xl bg-neutral-50 p-4">
          <dt class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Role Saat Ini</dt>
          <dd class="font-bold text-neutral-900 capitalize">{{ $user->role }}</dd>
        </div>
        <div class="rounded-2xl bg-neutral-50 p-4">
          <dt class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Tanggal Bergabung</dt>
          <dd class="font-bold text-neutral-900">{{ $user->created_at->format('d M Y') }}</dd>
        </div>
        <div class="rounded-2xl bg-neutral-50 p-4">
          <dt class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Update Terakhir</dt>
          <dd class="font-bold text-neutral-900">{{ $user->updated_at->format('d M Y') }}</dd>
        </div>
        <div class="rounded-2xl bg-neutral-50 p-4">
          <dt class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Kontak WhatsApp</dt>
          <dd class="font-bold text-neutral-900">{{ $user->phone ?: 'Belum tersedia' }}</dd>
        </div>
      </dl>
    </div>
  </div>

</div>
@endsection
