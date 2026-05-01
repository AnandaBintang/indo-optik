@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Pengguna</span>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-zinc-200 shadow-sm flex flex-col">

  <!-- Role Tabs -->
  <div class="border-b border-zinc-100 px-6 pt-4 flex flex-wrap gap-3 sm:gap-6">
    <a href="{{ request()->fullUrlWithQuery(['role' => '']) }}"
       class="pb-4 text-sm font-bold whitespace-nowrap {{ !request('role') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-neutral-900' }}">
      Semua Pengguna
      <span class="ml-1.5 inline-flex items-center justify-center bg-gray-100 text-gray-600 text-[10px] w-5 h-5 rounded-full">{{ $roleCounts['total'] ?? 0 }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['role' => \App\Models\User::ROLE_USER]) }}"
       class="pb-4 text-sm font-bold whitespace-nowrap {{ request('role') === \App\Models\User::ROLE_USER ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-neutral-900' }}">
      Pelanggan
      <span class="ml-1.5 inline-flex items-center justify-center bg-indigo-100 text-indigo-700 text-[10px] w-5 h-5 rounded-full">{{ $roleCounts['user'] ?? 0 }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['role' => \App\Models\User::ROLE_STAFF]) }}"
       class="pb-4 text-sm font-bold whitespace-nowrap {{ request('role') === \App\Models\User::ROLE_STAFF ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-neutral-900' }}">
      Staff
      <span class="ml-1.5 inline-flex items-center justify-center bg-amber-100 text-amber-700 text-[10px] w-5 h-5 rounded-full">{{ $roleCounts['staff'] ?? 0 }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['role' => \App\Models\User::ROLE_ADMIN]) }}"
       class="pb-4 text-sm font-bold whitespace-nowrap {{ request('role') === \App\Models\User::ROLE_ADMIN ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-neutral-900' }}">
      Admin
      <span class="ml-1.5 inline-flex items-center justify-center bg-red-100 text-red-700 text-[10px] w-5 h-5 rounded-full">{{ $roleCounts['admin'] ?? 0 }}</span>
    </a>
  </div>

  <div class="px-6 py-5 border-b border-zinc-100 flex flex-col sm:flex-row gap-4 justify-between bg-neutral-50/50">
    <!-- Filter & Search Form -->
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3 w-full">
      @if(request('role'))
        <input type="hidden" name="role" value="{{ request('role') }}">
      @endif

      <!-- Search -->
      <div class="relative w-full sm:max-w-md flex-shrink-0">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </div>
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari user berdasarkan nama, email, telp..."
          class="w-full bg-white text-neutral-900 rounded-xl py-2.5 pl-9 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all placeholder:text-gray-400"
        >
      </div>

      <button type="submit" class="btn btn-outline btn-sm bg-white border-zinc-200 text-gray-600 hover:bg-gray-50 flex-shrink-0 shrink">
        Cari
      </button>

      @if(request()->has('search'))
        <a href="{{ route('admin.users.index', ['role' => request('role')]) }}" class="btn btn-sm text-red-500 hover:bg-red-50 hover:text-red-600 border-transparent shadow-none px-3 flex-shrink-0">
          Reset
        </a>
      @endif
    </form>
  </div>

  <div class="overflow-x-auto lg:overflow-visible">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-white text-xs font-bold text-gray-500 uppercase tracking-wider">
          <th class="px-6 py-4 border-b border-zinc-100">Pengguna</th>
          <th class="px-6 py-4 border-b border-zinc-100">Role</th>
          <th class="px-6 py-4 border-b border-zinc-100">Bergabung</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-sm font-medium text-neutral-800 divide-y divide-zinc-100">
        @forelse($users as $user)
          <tr class="hover:bg-neutral-50/50 transition">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full border border-zinc-200 flex items-center justify-center shrink-0 bg-neutral-100">
                  @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="" class="w-full h-full rounded-full object-cover">
                  @else
                    <i class="fa-solid fa-user text-gray-400"></i>
                  @endif
                </div>
                <div>
                   <p class="font-bold text-neutral-900 leading-tight">
                     {{ $user->name }}
                     @if(auth()->id() === $user->id)
                       <span class="inline-flex ml-1 items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700">Anda</span>
                     @endif
                   </p>
                   <p class="text-xs text-gray-500 mt-0.5">{{ $user->email }} @if($user->phone) &bull; {{ $user->phone }} @endif</p>
                </div>
              </div>
            </td>

            <td class="px-6 py-4">
              @if($user->role === \App\Models\User::ROLE_ADMIN)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-red-100 text-red-700">Admin</span>
              @elseif($user->role === \App\Models\User::ROLE_STAFF)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700">Staff</span>
              @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">Pelanggan</span>
              @endif
            </td>

            <td class="px-6 py-4">
              <p class="text-neutral-900">{{ $user->created_at->format('d M Y') }}</p>
              <p class="text-[10px] text-gray-400 mt-0.5">{{ $user->created_at->diffForHumans() }}</p>
            </td>

            <td class="px-6 py-4 text-right">
              <!-- Edit Role (Hanya Admin ke pengguna lain) -->
              @if(auth()->user()->isAdmin() && auth()->id() !== $user->id)
                <div class="relative inline-block text-left" x-data="{ open: false }">
                  <button type="button" @click="open = !open" @click.away="open = false" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-200 transition-colors mr-1" title="Ubah Peran">
                     <i class="fa-solid fa-user-shield"></i>
                  </button>
                  <div x-show="open"
                       x-transition:enter="transition ease-out duration-100"
                       x-transition:enter-start="opacity-0 scale-95"
                       x-transition:enter-end="opacity-100 scale-100"
                       x-transition:leave="transition ease-in duration-75"
                       x-transition:leave-start="opacity-100 scale-100"
                       x-transition:leave-end="opacity-0 scale-95"
                       class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50 text-left"
                       style="display: none;">
                     <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST">
                       @csrf
                       @method('PATCH')
                       <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-gray-400">Jadikan sebagai:</div>
                       @foreach($roles as $role)
                          @if($role !== $user->role)
                            <button type="submit" name="role" value="{{ $role }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-neutral-50 w-full text-left font-medium">
                               {{ ucfirst($role) }}
                            </button>
                          @endif
                       @endforeach
                     </form>
                  </div>
                </div>
              @endif

              <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-colors mr-1" title="Lihat Profil">
                <i class="fa-solid fa-eye"></i>
              </a>

              @if(auth()->id() !== $user->id)
                @if(!$user->isAdmin() || (auth()->user()->isAdmin() && auth()->id() !== $user->id))
                  <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block delete-form" data-confirm="Apakah Anda yakin ingin menghapus akun pengguna ini?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors" title="Hapus Akun">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </form>
                @endif
              @endif
            </td>
          </tr>
        @empty
          <tr>
             <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-3 text-2xl">
                  <i class="fa-solid fa-users"></i>
                </div>
                <p class="font-bold text-neutral-900 mb-1">Pengguna Tidak Ditemukan</p>
                <p class="text-sm">Tidak ada data pengguna yang sesuai dengan kriteria yang dicari.</p>
             </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($users->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100 bg-neutral-50/30">
      {{ $users->links() }}
    </div>
  @endif
</div>
@endsection
