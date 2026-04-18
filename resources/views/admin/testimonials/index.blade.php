@extends('layouts.admin')

@section('title', 'Testimoni Pelanggan')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Testimoni</span>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col">
  <div class="px-6 py-5 border-b border-zinc-100 flex flex-col sm:flex-row gap-4 justify-between bg-neutral-50/50">
    
    <!-- Filter & Search Form -->
    <form action="{{ route('admin.testimonials.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3 w-full">
      <!-- Search -->
      <div class="relative w-full sm:max-w-xs flex-shrink-0">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </div>
        <input 
          type="text" 
          name="search" 
          value="{{ request('search') }}"
          placeholder="Cari nama pelanggan..." 
          class="w-full bg-white text-neutral-900 rounded-xl py-2.5 pl-9 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all placeholder:text-gray-400"
        >
      </div>

      <!-- Rating -->
      <select name="rating" class="bg-white text-neutral-900 rounded-xl py-2.5 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all sm:w-[140px]" onchange="this.form.submit()">
        <option value="">Semua Rating</option>
        <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Bintang</option>
        <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 Bintang</option>
        <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 Bintang</option>
        <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 Bintang</option>
        <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 Bintang</option>
      </select>
      
      <!-- Status -->
      <select name="status" class="bg-white text-neutral-900 rounded-xl py-2.5 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all sm:w-[140px]" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Tampil (Published)</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="unpublished" {{ request('status') === 'unpublished' ? 'selected' : '' }}>Disembunyikan</option>
      </select>

      @if(request()->hasAny(['search', 'rating', 'status']))
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-sm text-red-500 hover:bg-red-50 hover:text-red-600 border-transparent shadow-none px-3 flex-shrink-0">
          Reset
        </a>
      @endif

      <div class="sm:ml-auto">
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm h-full">
          <i class="fa-solid fa-plus mt-0.5"></i> Tambah Manual
        </a>
      </div>
    </form>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-white text-xs font-bold text-gray-500 uppercase tracking-wider">
          <th class="px-6 py-4 border-b border-zinc-100">Pelanggan</th>
          <th class="px-6 py-4 border-b border-zinc-100">Rating</th>
          <th class="px-6 py-4 border-b border-zinc-100">Pesan</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-center">Status</th>
          <th class="px-6 py-4 border-b border-zinc-100 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-sm font-medium text-neutral-800 divide-y divide-zinc-100">
        @forelse($testimonials as $testimonial)
          <tr class="hover:bg-neutral-50/50 transition">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full border border-zinc-200 overflow-hidden bg-neutral-100 shrink-0">
                  @if($testimonial->photo)
                    <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                  @elseif($testimonial->user && $testimonial->user->avatar)
                    <img src="{{ $testimonial->user->avatar }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                  @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm font-bold">
                       {{ substr($testimonial->name, 0, 1) }}
                    </div>
                  @endif
                </div>
                <div>
                  <div class="flex items-center gap-1.5">
                     <p class="font-bold text-neutral-900 leading-tight">{{ $testimonial->name }}</p>
                     @if($testimonial->is_verified)
                       <i class="fa-solid fa-circle-check text-blue-500 text-[10px]" title="Terverifikasi"></i>
                     @endif
                  </div>
                  <p class="text-xs text-gray-500 mt-0.5">{{ $testimonial->role ?? 'Pelanggan' }}</p>
                </div>
              </div>
            </td>
            
            <td class="px-6 py-4">
              <div class="flex items-center text-amber-400 text-[10px] gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                  <i class="fa-{{ $i <= $testimonial->rating ? 'solid' : 'regular' }} fa-star"></i>
                @endfor
              </div>
              <p class="text-[10px] text-gray-400 mt-1">{{ $testimonial->created_at->format('d/m/Y') }}</p>
            </td>

            <td class="px-6 py-4">
              <p class="text-gray-600 italic line-clamp-2 title" title="{{ $testimonial->message }}">"{{ $testimonial->message }}"</p>
            </td>

            <td class="px-6 py-4 text-center">
              @php
                $statusColor = match($testimonial->status) {
                    'published' => 'bg-green-100 text-green-700',
                    'pending' => 'bg-amber-100 text-amber-700',
                    'unpublished' => 'bg-gray-100 text-gray-700',
                    default => 'bg-gray-100 text-gray-700'
                };
                $statusLabel = match($testimonial->status) {
                    'published' => 'Tampil',
                    'pending' => 'Pending',
                    'unpublished' => 'Sembunyi',
                    default => ucfirst($testimonial->status)
                };
              @endphp
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $statusColor }}">
                {{ $statusLabel }}
              </span>
            </td>

            <td class="px-6 py-4 text-right whitespace-nowrap">
               <form action="{{ route('admin.testimonials.toggle-status', $testimonial->id) }}" method="POST" class="inline-block mr-1">
                  @csrf
                  @method('PATCH')
                  @if($testimonial->status === 'published')
                     <button type="submit" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-colors" title="Sembunyikan">
                        <i class="fa-solid fa-eye-slash"></i>
                     </button>
                  @else
                     <button type="submit" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition-colors" title="Tampilkan">
                        <i class="fa-solid fa-eye"></i>
                     </button>
                  @endif
               </form>

              <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-colors" title="Edit">
                <i class="fa-solid fa-pen-to-square"></i>
              </a>
              
              <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="inline-block ml-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?');">
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
             <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-3 text-2xl">
                  <i class="fa-solid fa-comment-dots"></i>
                </div>
                <p class="font-bold text-neutral-900 mb-1">Belum ada testimoni</p>
                <p class="text-sm">Tidak ada testimoni yang sesuai dengan filter Anda.</p>
             </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($testimonials->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100 bg-neutral-50/30">
      {{ $testimonials->links() }}
    </div>
  @endif
</div>
@endsection
