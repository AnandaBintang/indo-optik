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
       TAB FILTERS
       ============================================================ --}}
  <div class="flex justify-center mb-10 overflow-x-auto hide-scrollbar" data-animate>
    <div class="flex border-b border-zinc-200">
      <button
        class="px-6 py-4 text-base font-semibold border-b-[3px] border-indigo-500 text-indigo-600 transition-colors whitespace-nowrap"
        data-tab-btn="kacamata"
        aria-selected="true"
        role="tab"
        aria-controls="panel-kacamata">
        <i class="fa-solid fa-glasses mr-2"></i>Kacamata
      </button>
      <button
        class="px-6 py-4 text-base font-medium text-gray-500 border-b-[3px] border-transparent hover:text-indigo-600 transition-colors whitespace-nowrap"
        data-tab-btn="lensa"
        aria-selected="false"
        role="tab"
        aria-controls="panel-lensa">
        <i class="fa-solid fa-eye mr-2"></i>Lensa
      </button>
      <button
        class="px-6 py-4 text-base font-medium text-gray-500 border-b-[3px] border-transparent hover:text-indigo-600 transition-colors whitespace-nowrap"
        data-tab-btn="kontak-lensa"
        aria-selected="false"
        role="tab"
        aria-controls="panel-kontak-lensa">
        <i class="fa-solid fa-eye-dropper mr-2"></i>Kontak Lensa
      </button>
    </div>
  </div>

  {{-- ============================================================
       PANEL: KACAMATA
       ============================================================ --}}
  <div id="panel-kacamata" data-tab-panel="kacamata" role="tabpanel">
    <article class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 xl:gap-8" data-stagger>

      @forelse($products ?? [] as $product)
        <a href="{{ route('products.show', $product->slug) }}"
           class="group bg-white rounded-[24px] border border-zinc-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1 block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            @if($product->image)
              <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
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

        {{-- Placeholder Card 1 --}}
        <a href="{{ route('catalog.index') }}" class="group bg-white rounded-[24px] border border-zinc-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1 block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            <img
              src="https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&q=80&w=400"
              alt="Classic Round Frame"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
            <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-indigo-600 text-[10px] font-bold px-2 py-1 rounded-full">
              <i class="fa-solid fa-fire text-orange-500 mr-1"></i> Bestseller
            </span>
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">Classic Round Frame</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
              </div>
              <span class="text-gray-400 text-xs">4.8</span>
            </div>
            <p class="text-[1.35rem] font-extrabold text-indigo-600 mt-auto mb-4">Rp 299.000</p>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>

        {{-- Placeholder Card 2 --}}
        <a href="{{ route('catalog.index') }}" class="group bg-white rounded-[24px] border border-zinc-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1 block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            <img
              src="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&q=80&w=400"
              alt="Modern Square Frame"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">Modern Square Frame</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <span class="text-gray-400 text-xs">4.9</span>
            </div>
            <p class="text-[1.35rem] font-extrabold text-indigo-600 mt-auto mb-4">Rp 349.000</p>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>

        {{-- Placeholder Card 3 --}}
        <a href="{{ route('catalog.index') }}" class="group bg-white rounded-[24px] border border-zinc-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1 block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            <img
              src="https://images.unsplash.com/photo-1591076482161-42ce6da69f67?auto=format&fit=crop&q=80&w=400"
              alt="Minimalist Titanium"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">Minimalist Titanium</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <span class="text-gray-400 text-xs">5.0</span>
            </div>
            <p class="text-[1.35rem] font-extrabold text-indigo-600 mt-auto mb-4">Rp 499.000</p>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>

        {{-- Placeholder Card 4 --}}
        <a href="{{ route('catalog.index') }}" class="group bg-white rounded-[24px] border border-zinc-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1 block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            <img
              src="https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&q=80&w=400"
              alt="Designer Oval Frame"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">Designer Oval Frame</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
              </div>
              <span class="text-gray-400 text-xs">4.7</span>
            </div>
            <p class="text-[1.35rem] font-extrabold text-indigo-600 mt-auto mb-4">Rp 399.000</p>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>

        {{-- Placeholder Card 5 --}}
        <a href="{{ route('catalog.index') }}" class="group bg-white rounded-[24px] border border-zinc-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1 block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            <img
              src="https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&q=80&w=400"
              alt="Vintage Clubmaster"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">Vintage Clubmaster</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star text-zinc-300"></i>
              </div>
              <span class="text-gray-400 text-xs">4.3</span>
            </div>
            <p class="text-[1.35rem] font-extrabold text-indigo-600 mt-auto mb-4">Rp 329.000</p>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>

        {{-- Placeholder Card 6 --}}
        <a href="{{ route('catalog.index') }}" class="group bg-white rounded-[24px] border border-zinc-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1 block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            <img
              src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&q=80&w=400"
              alt="Sporty Wraparound"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
            <span class="absolute top-3 right-3 bg-indigo-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">
              Baru
            </span>
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">Sporty Wraparound</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <span class="text-gray-400 text-xs">5.0</span>
            </div>
            <p class="text-[1.35rem] font-extrabold text-indigo-600 mt-auto mb-4">Rp 449.000</p>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>

        {{-- Placeholder Card 7 --}}
        <a href="{{ route('catalog.index') }}" class="group bg-white rounded-[24px] border border-zinc-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1 block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            <img
              src="https://images.unsplash.com/photo-1556306535-0f09a537f0a3?auto=format&fit=crop&q=80&w=400"
              alt="Elegant Cat-Eye"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">Elegant Cat-Eye</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
              </div>
              <span class="text-gray-400 text-xs">4.6</span>
            </div>
            <div class="mt-auto mb-4">
              <p class="text-[0.8rem] text-gray-400 line-through font-medium">Rp 520.000</p>
              <p class="text-[1.35rem] font-extrabold text-indigo-600">Rp 389.000</p>
            </div>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>

        {{-- Placeholder Card 8 --}}
        <a href="{{ route('catalog.index') }}" class="group bg-white rounded-[24px] border border-zinc-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1 block">
          <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
            <img
              src="https://images.unsplash.com/photo-1483299664077-7d3a1ee62e57?auto=format&fit=crop&q=80&w=400"
              alt="Slim Rectangle Frame"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">Slim Rectangle Frame</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star text-zinc-300"></i>
              </div>
              <span class="text-gray-400 text-xs">4.2</span>
            </div>
            <p class="text-[1.35rem] font-extrabold text-indigo-600 mt-auto mb-4">Rp 279.000</p>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>

      @endforelse
    </article>

    {{-- Pagination --}}
    @if(isset($products) && method_exists($products, 'links') && $products->hasPages())
      <div class="mt-12 flex justify-center">
        {{ $products->links() }}
      </div>
    @endif
  </div>

  {{-- ============================================================
       PANEL: LENSA (Hidden by default)
       ============================================================ --}}
  <div id="panel-lensa" data-tab-panel="lensa" role="tabpanel" hidden>
    <div class="text-center py-20 px-4 sm:px-6 lg:px-8">
      <div class="text-6xl text-indigo-400 mb-6">
        <i class="fa-solid fa-microscope"></i>
      </div>
      <h2 class="text-3xl font-extrabold text-neutral-900 mb-4">Premium Lenses</h2>
      <p class="text-gray-500 text-lg max-w-xl mx-auto mb-8 leading-relaxed">
        Koleksi lensa dengan pelindung sinar biru, anti-pantul, dan adaptif cahaya. Tersedia untuk dibeli bersama frame pilihan Anda.
      </p>
      <div class="flex flex-wrap justify-center gap-4">
        <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-calendar-check"></i>
          Konsultasi Lensa
        </a>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline btn-lg">
          <i class="fa-solid fa-glasses"></i>
          Lihat Kacamata
        </a>
      </div>

      {{-- Lens feature cards --}}
      <div class="grid sm:grid-cols-3 gap-6 max-w-3xl mx-auto mt-16">
        <div class="bg-white rounded-[24px] p-6 border border-zinc-100 shadow-sm text-center">
          <div class="w-12 h-12 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl mb-4">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <h4 class="font-bold text-neutral-900 mb-2">Anti Blue Light</h4>
          <p class="text-gray-500 text-sm leading-relaxed">Perlindungan dari radiasi sinar biru layar digital</p>
        </div>
        <div class="bg-white rounded-[24px] p-6 border border-zinc-100 shadow-sm text-center">
          <div class="w-12 h-12 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl mb-4">
            <i class="fa-solid fa-sun"></i>
          </div>
          <h4 class="font-bold text-neutral-900 mb-2">Photochromic</h4>
          <p class="text-gray-500 text-sm leading-relaxed">Lensa adaptif yang menyesuaikan intensitas cahaya</p>
        </div>
        <div class="bg-white rounded-[24px] p-6 border border-zinc-100 shadow-sm text-center">
          <div class="w-12 h-12 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl mb-4">
            <i class="fa-solid fa-eye"></i>
          </div>
          <h4 class="font-bold text-neutral-900 mb-2">Anti Reflective</h4>
          <p class="text-gray-500 text-sm leading-relaxed">Lapisan anti-pantulan untuk penglihatan lebih jernih</p>
        </div>
      </div>
    </div>
  </div>

  {{-- ============================================================
       PANEL: KONTAK LENSA (Hidden by default)
       ============================================================ --}}
  <div id="panel-kontak-lensa" data-tab-panel="kontak-lensa" role="tabpanel" hidden>
    <div class="text-center py-20 px-4 sm:px-6 lg:px-8">
      <div class="text-6xl text-indigo-400 mb-6">
        <i class="fa-solid fa-eye-dropper"></i>
      </div>
      <h2 class="text-3xl font-extrabold text-neutral-900 mb-4">Kontak Lensa</h2>
      <p class="text-gray-500 text-lg max-w-xl mx-auto mb-8 leading-relaxed">
        Berbagai varian kontak lensa bening dan berwarna untuk kenyamanan harian, mingguan, maupun bulanan.
      </p>
      <div class="flex flex-wrap justify-center gap-4">
        <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-user-doctor"></i>
          Periksa Mata Gratis
        </a>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline btn-lg">
          <i class="fa-solid fa-glasses"></i>
          Lihat Kacamata
        </a>
      </div>

      {{-- Contact lens feature cards --}}
      <div class="grid sm:grid-cols-3 gap-6 max-w-3xl mx-auto mt-16">
        <div class="bg-white rounded-[24px] p-6 border border-zinc-100 shadow-sm text-center">
          <div class="w-12 h-12 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl mb-4">
            <i class="fa-regular fa-calendar-days"></i>
          </div>
          <h4 class="font-bold text-neutral-900 mb-2">Harian</h4>
          <p class="text-gray-500 text-sm leading-relaxed">Kontak lensa sekali pakai untuk kenyamanan maksimal</p>
        </div>
        <div class="bg-white rounded-[24px] p-6 border border-zinc-100 shadow-sm text-center">
          <div class="w-12 h-12 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl mb-4">
            <i class="fa-solid fa-rotate"></i>
          </div>
          <h4 class="font-bold text-neutral-900 mb-2">Mingguan</h4>
          <p class="text-gray-500 text-sm leading-relaxed">Kontak lensa yang dapat digunakan hingga 2 minggu</p>
        </div>
        <div class="bg-white rounded-[24px] p-6 border border-zinc-100 shadow-sm text-center">
          <div class="w-12 h-12 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl mb-4">
            <i class="fa-solid fa-infinity"></i>
          </div>
          <h4 class="font-bold text-neutral-900 mb-2">Bulanan</h4>
          <p class="text-gray-500 text-sm leading-relaxed">Kontak lensa tahan lama dengan perawatan rutin</p>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection
