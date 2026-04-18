@extends('layouts.admin')

@section('title', 'Kategori')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Kategori</span>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col">
  <div class="px-6 py-5 border-b border-zinc-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    
    <!-- Search Form -->
    <form action="{{ route('admin.categories.index') }}" method="GET" class="relative w-full sm:max-w-xs">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
      </div>
      <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}"
        placeholder="Cari kategori..." 
        class="w-full bg-neutral-50 text-neutral-900 rounded-xl py-2.5 pl-9 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-zinc-200 transition-all placeholder:text-gray-400"
      >
    </form>

    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm flex-shrink-0">
      <i class="fa-solid fa-plus mt-0.5"></i> Tambah Kategori
    </a>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-neutral-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider">
          <th class="px-6 py-4 border-b border-zinc-100">Nama Kategori</th>
          <th class="px-6 py-4 border-b border-zinc-100">Deskripsi</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-center">Produk</th>
          <th class="px-6 py-4 border-b border-zinc-100">Status</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-sm font-medium text-neutral-800 divide-y divide-zinc-100">
        @forelse($categories as $category)
          <tr class="hover:bg-neutral-50/50 transition {{ $category->trashed() ? 'opacity-50' : '' }}">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                  <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                  <p class="font-bold text-neutral-900 leading-tight">{{ $category->name }}</p>
                  <p class="text-xs text-gray-400 mt-0.5">{{ $category->slug }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <p class="text-gray-500 line-clamp-2 max-w-xs">{{ $category->description ?? '-' }}</p>
            </td>
            <td class="px-6 py-4 text-center">
              <span class="inline-flex items-center justify-center bg-gray-100 text-gray-700 w-8 h-8 rounded-full font-bold text-xs">
                {{ $category->products_count ?? 0 }}
              </span>
            </td>
            <td class="px-6 py-4">
              @if($category->trashed())
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-red-100 text-red-700">Dihapus</span>
              @elseif($category->status === 'active')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-green-100 text-green-700">Aktif</span>
              @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">Nonaktif</span>
              @endif
            </td>
            <td class="px-6 py-4 text-right">
              @if($category->trashed())
                <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" class="inline-block delete-form" data-confirm="Apakah Anda yakin ingin memulihkan kategori ini?">
                  @csrf
                  <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition-colors" title="Pulihkan">
                     <i class="fa-solid fa-arrow-rotate-left"></i>
                  </button>
                </form>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block ml-1 delete-form" data-confirm="Apakah Anda yakin ingin menghapus PERMANEN ini?">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors" title="Hapus Permanen">
                     <i class="fa-solid fa-trash-can"></i>
                  </button>
                </form>
              @else
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-colors" title="Edit">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block ml-1 delete-form" data-confirm="Apakah Anda yakin ingin menghapus ini?">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors" title="Hapus">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
             <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-3 text-2xl">
                  <i class="fa-solid fa-layer-group"></i>
                </div>
                <p class="font-bold text-neutral-900 mb-1">Belum ada kategori</p>
                <p class="text-sm">Silakan tambah kategori baru untuk mulai mengelola katalog.</p>
             </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100 bg-neutral-50/30">
      {{ $categories->links() }}
    </div>
  @endif
</div>
@endsection
