@extends('layouts.admin')

@section('title', 'Kode Promo')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Kode Promo</span>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col">
  <div class="px-6 py-5 border-b border-zinc-100 flex flex-col sm:flex-row gap-4 justify-between bg-neutral-50/50">
    
    <!-- Filter & Search Form -->
    <form action="{{ route('admin.promo-codes.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3 w-full">
      <!-- Search -->
      <div class="relative w-full sm:max-w-xs flex-shrink-0">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </div>
        <input 
          type="text" 
          name="search" 
          value="{{ request('search') }}"
          placeholder="Cari kode promo..." 
          class="w-full bg-white text-neutral-900 rounded-xl py-2.5 pl-9 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all placeholder:text-gray-400 uppercase"
        >
      </div>

      <!-- Tipe -->
      <select name="type" class="bg-white text-neutral-900 rounded-xl py-2.5 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all sm:w-[140px]" onchange="this.form.submit()">
        <option value="">Semua Tipe</option>
        <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Persentase</option>
        <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Nominal</option>
      </select>
      
      <!-- Status -->
      <select name="is_active" class="bg-white text-neutral-900 rounded-xl py-2.5 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all sm:w-[140px]" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
      </select>

      @if(request()->hasAny(['search', 'type', 'is_active']))
        <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-sm text-red-500 hover:bg-red-50 hover:text-red-600 border-transparent shadow-none px-3 flex-shrink-0">
          Reset
        </a>
      @endif

      <div class="sm:ml-auto">
        <a href="{{ route('admin.promo-codes.create') }}" class="btn btn-primary btn-sm h-full">
          <i class="fa-solid fa-plus mt-0.5"></i> Tambah Promo
        </a>
      </div>
    </form>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-white text-xs font-bold text-gray-500 uppercase tracking-wider">
          <th class="px-6 py-4 border-b border-zinc-100">Kode Promo</th>
          <th class="px-6 py-4 border-b border-zinc-100">Nilai Diskon</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-center">Penggunaan</th>
          <th class="px-6 py-4 border-b border-zinc-100">Kedaluwarsa</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-center">Status</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-sm font-medium text-neutral-800 divide-y divide-zinc-100">
        @forelse($promoCodes as $promo)
          <tr class="hover:bg-neutral-50/50 transition">
            <td class="px-6 py-4">
               <div class="flex items-center gap-3">
                 <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                   <i class="fa-solid fa-ticket-simple"></i>
                 </div>
                 <div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded border border-gray-200 bg-gray-50 font-mono font-bold text-neutral-900 shadow-sm text-sm tracking-widest mb-1">{{ $promo->code }}</span>
                    @if($promo->label)
                       <p class="text-xs text-gray-500 line-clamp-1 max-w-[150px] title" title="{{ $promo->label }}">{{ $promo->label }}</p>
                    @endif
                 </div>
               </div>
            </td>
            
            <td class="px-6 py-4">
              <span class="font-extrabold text-indigo-600">
                 @if($promo->type === 'percentage')
                   {{ rtrim(rtrim(number_format($promo->value, 2, ',', '.'), '0'), ',') }}%
                 @else
                   Rp {{ number_format($promo->value, 0, ',', '.') }}
                 @endif
              </span>
              @if($promo->min_purchase > 0)
                 <p class="text-[10px] text-gray-400 mt-0.5">Min trf: Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}</p>
              @endif
              @if($promo->type === 'percentage' && $promo->max_discount > 0)
                 <p class="text-[10px] text-gray-400 mt-0.5">Maks: Rp {{ number_format($promo->max_discount, 0, ',', '.') }}</p>
              @endif
            </td>

            <td class="px-6 py-4 text-center">
              <span class="font-bold text-neutral-900">{{ $promo->usage_count }}</span>
              @if($promo->usage_limit)
                 <span class="text-xs text-gray-400">/ {{ $promo->usage_limit }}</span>
              @else
                 <span class="text-xs text-gray-400">/ ∞</span>
              @endif
            </td>

            <td class="px-6 py-4">
               @if($promo->expired_at)
                  @if($promo->expired_at < now())
                     <span class="text-red-500 font-semibold">{{ $promo->expired_at->format('d/m/Y') }}</span>
                     <p class="text-[10px] text-red-500">Berakhir</p>
                  @else
                     <span class="text-neutral-900">{{ $promo->expired_at->format('d/m/Y') }}</span>
                  @endif
               @else
                  <span class="text-gray-400 text-xs italic">Tanpa batas</span>
               @endif
            </td>

            <td class="px-6 py-4 text-center">
              @if($promo->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-green-100 text-green-700">Aktif</span>
              @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">Nonaktif</span>
              @endif
            </td>

            <td class="px-6 py-4 text-right whitespace-nowrap">
               <form action="{{ route('admin.promo-codes.toggle-status', $promo->id) }}" method="POST" class="inline-block mr-1">
                  @csrf
                  @method('PATCH')
                  @if($promo->is_active)
                     <button type="submit" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-colors" title="Nonaktifkan">
                        <i class="fa-solid fa-ban"></i>
                     </button>
                  @else
                     <button type="submit" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition-colors" title="Aktifkan">
                        <i class="fa-solid fa-check"></i>
                     </button>
                  @endif
               </form>

              <a href="{{ route('admin.promo-codes.edit', $promo->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-colors" title="Edit">
                <i class="fa-solid fa-pen-to-square"></i>
              </a>
              
              <form action="{{ route('admin.promo-codes.destroy', $promo->id) }}" method="POST" class="inline-block ml-1 delete-form" data-confirm="Apakah Anda yakin ingin menghapus promo kode ini?">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors" title="Hapus">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
             <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-3 text-2xl">
                  <i class="fa-solid fa-ticket"></i>
                </div>
                <p class="font-bold text-neutral-900 mb-1">Belum ada promo code</p>
                <p class="text-sm">Anda belum menambahkan kode promo untuk pelanggan.</p>
             </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($promoCodes->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100 bg-neutral-50/30">
      {{ $promoCodes->links() }}
    </div>
  @endif
</div>
@endsection
