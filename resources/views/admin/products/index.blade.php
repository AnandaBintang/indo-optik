@extends('layouts.admin')

@section('title', 'Katalog Produk')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Produk</span>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col">
  <div class="px-6 py-5 border-b border-zinc-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    
    <!-- Filter & Search Form -->
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto flex-1">
      <!-- Search -->
      <div class="relative w-full sm:max-w-xs">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </div>
        <input 
          type="text" 
          name="search" 
          value="{{ request('search') }}"
          placeholder="Cari produk / SKU..." 
          class="w-full bg-neutral-50 text-neutral-900 rounded-xl py-2.5 pl-9 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-zinc-200 transition-all placeholder:text-gray-400"
        >
      </div>

      <!-- Kategori Filter -->
      <select name="category_id" class="bg-neutral-50 text-neutral-900 rounded-xl py-2.5 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-zinc-200 transition-all sm:max-w-[160px]" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
      </select>

      <!-- Status Filter -->
      <select name="status" class="bg-neutral-50 text-neutral-900 rounded-xl py-2.5 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-zinc-200 transition-all sm:max-w-[140px]" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        <option value="trashed" {{ request('status') === 'trashed' ? 'selected' : '' }}>Dihapus</option>
      </select>
    </form>

    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm flex-shrink-0">
      <i class="fa-solid fa-plus mt-0.5"></i> Tambah Produk
    </a>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-neutral-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider">
          <th class="px-6 py-4 border-b border-zinc-100">Produk</th>
          <th class="px-6 py-4 border-b border-zinc-100">Kategori</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-right">Harga</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-center">Stok</th>
          <th class="px-6 py-4 border-b border-zinc-100">Status</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-sm font-medium text-neutral-800 divide-y divide-zinc-100">
        @forelse($products as $product)
          <tr class="hover:bg-neutral-50/50 transition {{ $product->trashed() ? 'opacity-50' : '' }}">
            <!-- Input Produk -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-100 rounded-xl border border-zinc-200 overflow-hidden shrink-0 relative">
                  @if($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                  @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                      <i class="fa-solid fa-image"></i>
                    </div>
                  @endif
                  @if($product->is_featured)
                    <div class="absolute top-0 right-0 bg-orange-500 w-3 h-3 rounded-bl-lg" title="Produk Unggulan"></div>
                  @endif
                </div>
                <div>
                  <p class="font-bold text-neutral-900 leading-tight line-clamp-1" title="{{ $product->name }}">{{ $product->name }}</p>
                  <p class="text-[11px] text-gray-400 font-mono mt-0.5">{{ $product->sku ?? '-' }}</p>
                </div>
              </div>
            </td>
            
            <td class="px-6 py-4">
              <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold">
                {{ $product->category->name ?? '-' }}
              </span>
            </td>

            <td class="px-6 py-4 text-right">
              @if($product->discount_price)
                 <div class="flex flex-col items-end">
                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="font-extrabold text-indigo-600">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                 </div>
              @else
                 <span class="font-extrabold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
              @endif
            </td>

            <td class="px-6 py-4 text-center">
               <span class="font-bold {{ $product->stock <= 5 ? 'text-red-500' : 'text-neutral-900' }}">
                 {{ $product->stock }}
               </span>
            </td>

            <td class="px-6 py-4">
              @if($product->trashed())
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-red-100 text-red-700">Dihapus</span>
              @elseif($product->status === 'active')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-green-100 text-green-700">Aktif</span>
              @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">Nonaktif</span>
              @endif
            </td>

            <td class="px-6 py-4 text-right">
              @if($product->trashed())
                <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="inline-block delete-form" data-confirm="Apakah Anda yakin ingin memulihkan produk ini?">
                  @csrf
                  <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition-colors" title="Pulihkan">
                     <i class="fa-solid fa-arrow-rotate-left"></i>
                  </button>
                </form>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block ml-1 delete-form" data-confirm="Apakah Anda yakin ingin menghapus PERMANEN produk ini? Aksi ini tidak dapat dibatalkan.">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors" title="Hapus Permanen">
                     <i class="fa-solid fa-trash-can"></i>
                  </button>
                </form>
              @else
                <a href="{{ route('products.show', $product->slug ?? 'slug') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-200 transition-colors mr-1" title="Lihat di Web" style="pointer-events: none; opacity:0.5;">
                  <!-- We redirect front end show properly using proper route later, but for now we put a placeholder link -->
                  <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                </a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-colors" title="Edit">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block ml-1 delete-form" data-confirm="Apakah Anda yakin ingin menghapus produk ini?">
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
             <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-3 text-2xl">
                  <i class="fa-solid fa-glasses"></i>
                </div>
                <p class="font-bold text-neutral-900 mb-1">Belum ada produk</p>
                <p class="text-sm">Silakan mulai dengan menambahkan produk baru ke katalog stok.</p>
             </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($products->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100 bg-neutral-50/30">
      {{ $products->links() }}
    </div>
  @endif
</div>
@endsection
