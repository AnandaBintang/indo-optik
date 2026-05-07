@extends('layouts.app')

@section('title', 'Katalog — IndoOptik')
@section('description', 'Jelajahi koleksi kacamata pria dan wanita terbaru dari IndoOptik. Frame modern dan lensa berkualitas dengan harga terbaik.')
@section('og_title', 'Katalog — IndoOptik')
@section('og_description', 'Temukan frame sempurna yang melengkapi gaya Anda dari koleksi IndoOptik.')

@section('content')

{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<header class="relative py-20 md:py-24 overflow-hidden bg-indigo-900 border-b border-indigo-900/50 mb-12">
  <img
    src="https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&q=80&w=2000"
    alt="Katalog Background"
    class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay"
  />
  <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/90 via-indigo-800/80 to-violet-900/70"></div>

  <div class="relative page-shell text-center z-10" data-animate>
    <span class="inline-block px-4 py-1.5 bg-white/20 text-white rounded-full text-xs font-bold tracking-wider uppercase mb-4 backdrop-blur-sm">
      Koleksi Terlengkap
    </span>
    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">Katalog Produk</h1>
    <p class="text-lg text-indigo-100 font-medium max-w-2xl mx-auto">
      Temukan frame sempurna yang melengkapi gaya Anda
    </p>
  </div>
</header>

<div class="page-shell pb-20">

  {{-- ============================================================
       FILTERS
       ============================================================ --}}
  <form action="{{ route('catalog.index') }}" method="GET" class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 mb-8" data-animate>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
      <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </div>
        <input
          type="text"
          id="catalog-search"
          name="q"
          value="{{ $search ?? '' }}"
          placeholder="Cari produk..."
          class="w-full bg-neutral-50 text-neutral-900 rounded-xl py-3 pl-10 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-zinc-200 transition-all placeholder:text-gray-400"
        >
      </div>

      <details class="w-full sm:w-auto">
        <summary class="btn btn-ghost btn-sm w-full sm:w-auto justify-center">
          <i class="fa-solid fa-sliders"></i> Filter
          @if(($category ?? 'all') !== 'all' || ($minPrice ?? null) !== null || ($maxPrice ?? null) !== null || ($stock ?? null) || ($discount ?? false) || ($featured ?? false) || ($sort ?? 'latest') !== 'latest')
            <span class="ml-2 inline-flex h-2 w-2 rounded-full bg-indigo-500"></span>
          @endif
        </summary>

        <div class="mt-4 rounded-xl border border-zinc-200 bg-neutral-50 p-4">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kategori</label>
              <select name="category" class="w-full bg-white text-neutral-900 rounded-xl py-3 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all">
                <option value="all">Semua Kategori</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->slug }}" {{ ($category ?? 'all') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Urutkan</label>
              <select name="sort" class="w-full bg-white text-neutral-900 rounded-xl py-3 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all">
                <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                <option value="name_asc" {{ ($sort ?? '') === 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                <option value="name_desc" {{ ($sort ?? '') === 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                <option value="discount_desc" {{ ($sort ?? '') === 'discount_desc' ? 'selected' : '' }}>Diskon Terbesar</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="min-price">Harga Minimum</label>
              <input
                type="number"
                id="min-price"
                name="min_price"
                min="0"
                inputmode="numeric"
                value="{{ $minPrice ?? '' }}"
                placeholder="0"
                class="w-full bg-white text-neutral-900 rounded-xl py-3 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all placeholder:text-gray-400"
              >
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="max-price">Harga Maksimum</label>
              <input
                type="number"
                id="max-price"
                name="max_price"
                min="0"
                inputmode="numeric"
                value="{{ $maxPrice ?? '' }}"
                placeholder="999000"
                class="w-full bg-white text-neutral-900 rounded-xl py-3 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all placeholder:text-gray-400"
              >
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Stok</label>
              <select name="stock" class="w-full bg-white text-neutral-900 rounded-xl py-3 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-zinc-200 transition-all">
                <option value="">Semua Stok</option>
                <option value="in" {{ ($stock ?? '') === 'in' ? 'selected' : '' }}>Tersedia</option>
                <option value="out" {{ ($stock ?? '') === 'out' ? 'selected' : '' }}>Habis</option>
              </select>
            </div>

            <div class="flex items-end gap-4">
              <label class="inline-flex items-center gap-2 text-sm font-semibold text-neutral-900">
                <input type="checkbox" name="discount" value="1" class="rounded text-indigo-600 focus:ring-indigo-500" {{ ($discount ?? false) ? 'checked' : '' }}>
                Diskon
              </label>
              <label class="inline-flex items-center gap-2 text-sm font-semibold text-neutral-900">
                <input type="checkbox" name="featured" value="1" class="rounded text-indigo-600 focus:ring-indigo-500" {{ ($featured ?? false) ? 'checked' : '' }}>
                Unggulan
              </label>
            </div>
          </div>

          <div class="mt-4 flex flex-wrap items-center gap-3">
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="fa-solid fa-filter"></i> Terapkan Filter
            </button>
            <a href="{{ route('catalog.index') }}" class="btn btn-ghost btn-sm">Reset</a>
          </div>
        </div>
      </details>
    </div>

    <button type="submit" class="sr-only">Cari</button>
  </form>

  <div class="flex items-center justify-between mb-6 text-sm text-gray-500">
    <p>Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk</p>
    @if(($search ?? null) || ($category ?? 'all') !== 'all' || ($minPrice ?? null) !== null || ($maxPrice ?? null) !== null || ($stock ?? null) || ($discount ?? false) || ($featured ?? false))
      <span class="text-xs font-medium text-indigo-600">Filter aktif</span>
    @endif
  </div>

  <article class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 xl:gap-8" data-stagger>

      @forelse($products ?? [] as $product)
          <a href="{{ route('products.show', $product->slug) }}"
            class="group micro-card-soft bg-white rounded-[24px] border border-zinc-100 shadow-sm overflow-hidden flex flex-col block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            @if($product->image)
              <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover micro-zoom"
                loading="lazy"
              />
            @else
              <div class="w-full h-full flex items-center justify-center text-gray-300 bg-neutral-100">
                <i class="fa-solid fa-glasses text-5xl"></i>
              </div>
            @endif

            @if($product->is_featured ?? false)
              <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-indigo-600 text-[10px] font-bold px-2 py-1 rounded-full">
                <i class="fa-solid fa-fire text-orange-500 mr-1"></i> Bestseller
              </span>
            @endif

            @if(isset($product->discount_price) && $product->discount_price)
              <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">
                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
              </span>
            @endif
          </div>

          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">{{ $product->name }}</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star-half-stroke"></i>
              </div>
              <span class="text-gray-400 text-xs">4.8</span>
            </div>
            <div class="mt-auto mb-4">
              @if(isset($product->discount_price) && $product->discount_price)
                <p class="text-[0.8rem] text-gray-400 line-through font-medium">
                  Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                <p class="text-[1.35rem] font-extrabold text-indigo-600">
                  Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                </p>
              @else
                <p class="text-[1.35rem] font-extrabold text-indigo-600">
                  Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
              @endif
            </div>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>

      @empty
        <div class="col-span-full">
          <div class="bg-white rounded-2xl border border-dashed border-zinc-200 p-10 text-center">
            <div class="w-16 h-16 mx-auto bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500 text-2xl mb-4">
              <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h3 class="text-lg font-extrabold text-neutral-900">Produk tidak ditemukan</h3>
            <p class="text-sm text-gray-500 mt-2">Coba ubah kata kunci atau reset filter untuk melihat produk lainnya.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-ghost btn-sm mt-4">Reset Filter</a>
          </div>
        </div>

      @endforelse
    </article>

    {{-- Pagination --}}
    @if(isset($products) && method_exists($products, 'links') && $products->hasPages())
      <div class="mt-12 flex justify-center">
        {{ $products->links() }}
      </div>
    @endif
</div>

@endsection
