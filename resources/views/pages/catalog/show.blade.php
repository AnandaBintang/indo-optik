@extends('layouts.app')

@section('title', ($product->name ?? 'Detail Produk') . ' — IndoOptik')
@section('description', $product->short_description ?? $product->meta_description ?? 'Temukan produk optik berkualitas di IndoOptik.')
@section('og_title', ($product->name ?? 'Produk') . ' — IndoOptik')
@section('og_description', $product->short_description ?? 'Produk optik premium dari IndoOptik.')
@section('og_type', 'product')

@section('content')

@php
  $colorVariants = is_array($product->color_variants ?? null) ? $product->color_variants : [];
  $lensVariants = is_array($product->lens_variants ?? null) ? $product->lens_variants : [];
  $frameVariants = is_array($product->frame_variants ?? null) ? $product->frame_variants : [];
  $colorVariantList = array_values($colorVariants);
  $lensVariantList = array_values($lensVariants);
  $frameVariantList = array_values($frameVariants);
  $defaultColorLabel = $colorVariantList[0]['label'] ?? 'Hitam';
  $galleryImages = collect([$product->image_url])
      ->merge($product->images->pluck('image_url'))
      ->filter()
      ->values();
@endphp

<main class="page-shell py-12 flex-1"
      data-base-price="{{ $product->effective_price ?? $product->price ?? 299000 }}"
      data-orig-price="{{ $product->price ?? 399000 }}">

  {{-- ============================================================
       BREADCRUMB
       ============================================================ --}}
  <nav aria-label="Breadcrumb" class="breadcrumb" data-animate>
    <a href="{{ route('home') }}">Beranda</a>
    <span class="separator">›</span>
    <a href="{{ route('catalog.index') }}">Katalog</a>
    <span class="separator">›</span>
    <span class="current">{{ $product->name ?? 'Classic Round Frame' }}</span>
  </nav>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 xl:gap-16">

    {{-- ============================================================
         GALERI
         ============================================================ --}}
    <div class="space-y-4" data-animate="slide-left">
      <div class="relative aspect-[4/5] bg-neutral-100 rounded-3xl overflow-hidden shadow-xl border border-zinc-100 group">
        <img
          id="main-product-img"
          src="{{ $product->image ? asset('storage/' . $product->image) : 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=' }}"
          alt="{{ $product->name ?? 'Classic Round Frame' }}"
          class="product-main-img w-full h-full object-cover"
        />
        @if(isset($product->discount_price) && $product->discount_price)
          <div class="absolute top-4 right-4 price-badge" id="discount-badge">
            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
          </div>
        @else
          <div class="absolute top-4 right-4 price-badge" id="discount-badge" style="display:none;">-25%</div>
        @endif
      </div>

      {{-- Thumbnail Grid --}}
      <div class="grid grid-cols-4 gap-3" id="thumb-container">
        @if(isset($product->gallery) && count($product->gallery))
          @foreach($product->gallery as $idx => $thumb)
            <button
              type="button"
              onclick="document.getElementById('main-product-img').src='{{ asset('storage/' . $thumb) }}'; document.querySelectorAll('#thumb-container button').forEach(b=>b.classList.remove('ring-2','ring-indigo-500')); this.classList.add('ring-2','ring-indigo-500');"
              class="aspect-square rounded-2xl overflow-hidden bg-neutral-100 border border-zinc-200 hover:border-indigo-400 transition-all duration-150 {{ $idx === 0 ? 'ring-2 ring-indigo-500' : '' }}">
              <img
                src="{{ asset('storage/' . $thumb) }}"
                alt="Thumbnail {{ $idx + 1 }}"
                class="w-full h-full object-cover"
                loading="lazy"
              />
            </button>
          @endforeach
        @else
          {{-- JS will populate COLOR_VARIANTS thumbs; placeholders for layout --}}
          <div class="aspect-square rounded-2xl overflow-hidden bg-neutral-100 border-2 border-indigo-400 ring-2 ring-indigo-500">
            <img
              src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&q=80&w=200' }}"
              alt="Thumbnail 1"
              class="w-full h-full object-cover"
              loading="lazy"
            />
          </div>
          <div class="aspect-square rounded-2xl overflow-hidden bg-neutral-100 border border-zinc-200 hover:border-indigo-400 transition cursor-pointer">
            <img
              src="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&q=80&w=200"
              alt="Thumbnail 2"
              class="w-full h-full object-cover"
              loading="lazy"
            />
          </div>
          <div class="aspect-square rounded-2xl overflow-hidden bg-neutral-100 border border-zinc-200 hover:border-indigo-400 transition cursor-pointer">
            <img
              src="https://images.unsplash.com/photo-1591076482161-42ce6da69f67?auto=format&fit=crop&q=80&w=200"
              alt="Thumbnail 3"
              class="w-full h-full object-cover"
              loading="lazy"
            />
          </div>
          <div class="aspect-square rounded-2xl overflow-hidden bg-neutral-100 border border-zinc-200 hover:border-indigo-400 transition cursor-pointer">
            <img
              src="https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&q=80&w=200"
              alt="Thumbnail 4"
              class="w-full h-full object-cover"
              loading="lazy"
            />
          </div>
        @endif
      </div>
    </div>

    {{-- ============================================================
         DETAIL PRODUK
         ============================================================ --}}
    <div class="flex flex-col gap-8" data-animate="slide-right">

      {{-- Nama & Rating --}}
      <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-semibold mb-3">
          <i class="fa-solid fa-glasses"></i>
          {{ $product->category->name ?? 'Kacamata' }}
        </span>
        <h1 class="text-4xl md:text-5xl font-bold text-neutral-900 mb-3 leading-tight">
          {{ $product->name ?? 'Classic Round Frame' }}
        </h1>
        <div class="flex items-center gap-3">
          <div class="flex text-indigo-500 text-sm gap-0.5">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star-half-stroke"></i>
          </div>
          <span class="text-gray-500 text-sm">4.8 (124 ulasan)</span>
        </div>
      </div>

      {{-- Harga --}}
      <div class="flex flex-col gap-1">
        <div class="flex items-baseline gap-3">
          @if(isset($product->discount_price) && $product->discount_price)
            <span class="price-base" id="product-price">
              Rp {{ number_format($product->discount_price, 0, ',', '.') }}
            </span>
            <span class="price-original">
              Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>
          @else
            <span class="price-base" id="product-price">
              Rp {{ number_format($product->price ?? 299000, 0, ',', '.') }}
            </span>
            <span class="price-original" style="display:none;">
              Rp {{ number_format(($product->price ?? 299000) * 1.33, 0, ',', '.') }}
            </span>
          @endif
        </div>
        <div class="price-addon" id="price-addon" style="display:none;"></div>
        <div class="text-sm font-semibold text-green-600" id="promo-discount" style="display:none;"></div>
      </div>

      {{-- Deskripsi --}}
      <p class="text-gray-500 leading-relaxed text-[0.95rem]">
        {{ $product->description ?? 'Frame bundar klasik dengan material polymer berkualitas tinggi. Sangat ringan namun kokoh, dirancang beradaptasi dengan kontur wajah untuk kenyamanan sepanjang hari. Pilihan sempurna untuk tampilan cerdas dan stylish.' }}
      </p>

      {{-- Divider --}}
      <hr class="border-zinc-200">

      {{-- Warna --}}
      <div>
        <div class="flex justify-between items-end mb-4">
          <h3 class="text-base font-bold text-neutral-900">1. Pilih Tipe Frame</h3>
          <span class="text-sm font-semibold text-indigo-600" id="selected-frame-label">{{ $frameVariantList[0]['label'] ?? 'Full Rim' }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="frame-options" role="radiogroup" aria-label="Tipe Frame">
          @if(count($frameVariantList))
            @foreach($frameVariantList as $index => $variant)
              @php
                $key = $variant['key']
                  ?? \Illuminate\Support\Str::slug($variant['label'] ?? 'frame');
                $label = $variant['label'] ?? ucfirst($key);
                $desc = $variant['desc'] ?? '';
                $priceAddon = (int) ($variant['priceAddon'] ?? $variant['price'] ?? 0);
                $icon = $variant['icon'] ?? 'fa-solid fa-glasses';
                $isSelected = $index === 0;
              @endphp
              <label class="lens-option {{ $isSelected ? 'selected' : '' }} cursor-pointer" id="frame-{{ $key }}">
                <input type="radio" name="frame_type" value="{{ $key }}" class="sr-only" {{ $isSelected ? 'checked' : '' }} />
                <div class="border-2 {{ $isSelected ? 'border-indigo-500 bg-indigo-50' : 'border-zinc-200 hover:border-indigo-400 hover:bg-indigo-50/50' }} rounded-2xl p-4 transition-all duration-200">
                  <div class="flex items-start justify-between mb-1">
                    <span class="font-bold text-sm text-neutral-900"><i class="{{ $icon }} text-indigo-500 mr-1"></i>{{ $label }}</span>
                    <span class="text-xs font-bold text-indigo-600">+Rp {{ number_format($priceAddon, 0, ',', '.') }}</span>
                  </div>
                  @if($desc)
                    <span class="text-xs text-gray-500">{{ $desc }}</span>
                  @endif
                </div>
              </label>
            @endforeach
          @endif
        </div>
      </div>

      {{-- Warna --}}
      <div>
        <div class="flex justify-between items-end mb-4">
          <h3 class="text-base font-bold text-neutral-900">2. Pilih Warna</h3>
            <span class="text-sm font-semibold text-indigo-600" id="selected-color-label">{{ $defaultColorLabel }}</span>
        </div>
        {{-- Color swatches injected by JS via COLOR_VARIANTS; fallback static swatches --}}
        <div class="flex flex-wrap gap-3" id="color-swatches">
            @if(count($colorVariantList))
              @foreach($colorVariantList as $index => $variant)
                @php
            $key = $variant['key']
              ?? \Illuminate\Support\Str::slug($variant['label'] ?? 'color');
            $label = $variant['label'] ?? ucfirst($key);
            $color = $variant['color'] ?? '#111827';
            $isSelected = $index === 0;
                @endphp
                <button type="button"
                  class="color-swatch {{ $isSelected ? 'selected w-10 h-10 rounded-full border-[3px] border-indigo-500 shadow-md shadow-indigo-200 transition-all duration-150 ring-2 ring-offset-2 ring-indigo-500' : 'w-10 h-10 rounded-full border-[3px] border-zinc-200 hover:border-indigo-400 shadow-sm transition-all duration-150' }}"
                  style="background-color:{{ $color }};"
                  data-color="{{ $key }}"
                  aria-label="{{ $label }}"
                  aria-pressed="{{ $isSelected ? 'true' : 'false' }}">
                </button>
              @endforeach
            @else
              <button type="button"
                class="color-swatch selected w-10 h-10 rounded-full border-[3px] border-indigo-500 shadow-md shadow-indigo-200 transition-all duration-150 ring-2 ring-offset-2 ring-indigo-500"
                style="background-color:#1a1a1a;"
                data-color="Hitam"
                aria-label="Hitam"
                aria-pressed="true">
              </button>
              <button type="button"
                class="color-swatch w-10 h-10 rounded-full border-[3px] border-zinc-200 hover:border-indigo-400 shadow-sm transition-all duration-150"
                style="background-color:#8B4513;"
                data-color="Coklat"
                aria-label="Coklat"
                aria-pressed="false">
              </button>
              <button type="button"
                class="color-swatch w-10 h-10 rounded-full border-[3px] border-zinc-200 hover:border-indigo-400 shadow-sm transition-all duration-150"
                style="background-color:#1e40af;"
                data-color="Biru"
                aria-label="Biru"
                aria-pressed="false">
              </button>
              <button type="button"
                class="color-swatch w-10 h-10 rounded-full border-[3px] border-zinc-200 hover:border-indigo-400 shadow-sm transition-all duration-150"
                style="background: linear-gradient(135deg, #c8a45b 0%, #6b3a1f 50%, #1a1a1a 100%);"
                data-color="Tortoise"
                aria-label="Tortoise"
                aria-pressed="false">
              </button>
            @endif
        </div>
      </div>

      {{-- Tipe Lensa --}}
      <div>
        <div class="flex justify-between items-end mb-4">
          <h3 class="text-base font-bold text-neutral-900">3. Pilih Tipe Lensa</h3>
          <a href="{{ route('services.index') }}" class="text-sm text-indigo-600 hover:underline">Panduan Lensa</a>
        </div>
        {{-- Lens options injected by JS via LENS_VARIANTS; fallback static options --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="lens-options" role="radiogroup" aria-label="Tipe Lensa">
          @if(count($lensVariantList))
            @foreach($lensVariantList as $index => $variant)
              @php
                $key = $variant['key']
                  ?? \Illuminate\Support\Str::slug($variant['label'] ?? 'lens');
                $label = $variant['label'] ?? ucfirst($key);
                $desc = $variant['desc'] ?? '';
                $priceAddon = (int) ($variant['priceAddon'] ?? $variant['price'] ?? 0);
                $isSelected = $index === 0;
              @endphp
              <label class="lens-option {{ $isSelected ? 'selected' : '' }} cursor-pointer" id="lens-{{ $key }}">
                <input type="radio" name="lens_type" value="{{ $key }}" class="sr-only" {{ $isSelected ? 'checked' : '' }} />
                <div class="border-2 {{ $isSelected ? 'border-indigo-500 bg-indigo-50' : 'border-zinc-200 hover:border-indigo-400 hover:bg-indigo-50/50' }} rounded-2xl p-4 transition-all duration-200">
                  <div class="flex items-start justify-between mb-1">
                    <span class="font-bold text-sm text-neutral-900">{{ $label }}</span>
                    <span class="text-xs font-bold text-indigo-600">+Rp {{ number_format($priceAddon, 0, ',', '.') }}</span>
                  </div>
                  @if($desc)
                    <span class="text-xs text-gray-500">{{ $desc }}</span>
                  @endif
                </div>
              </label>
            @endforeach
          @else
            <label class="lens-option selected cursor-pointer" id="lens-standard">
              <input type="radio" name="lens_type" value="standard" class="sr-only" checked />
              <div class="border-2 border-indigo-500 bg-indigo-50 rounded-2xl p-4 transition-all duration-200">
                <div class="flex items-start justify-between mb-1">
                  <span class="font-bold text-sm text-neutral-900">Standar</span>
                  <span class="text-xs font-bold text-indigo-600">+Rp 0</span>
                </div>
                <span class="text-xs text-gray-500">Lensa plastik standar, cocok untuk penggunaan harian</span>
              </div>
            </label>

            <label class="lens-option cursor-pointer" id="lens-blue-light">
              <input type="radio" name="lens_type" value="blue_light" class="sr-only" />
              <div class="border-2 border-zinc-200 hover:border-indigo-400 rounded-2xl p-4 transition-all duration-200 hover:bg-indigo-50/50">
                <div class="flex items-start justify-between mb-1">
                  <span class="font-bold text-sm text-neutral-900">Anti Blue Light</span>
                  <span class="text-xs font-bold text-indigo-600">+Rp 150.000</span>
                </div>
                <span class="text-xs text-gray-500">Perlindungan dari radiasi layar digital</span>
              </div>
            </label>

            <label class="lens-option cursor-pointer" id="lens-photochromic">
              <input type="radio" name="lens_type" value="photochromic" class="sr-only" />
              <div class="border-2 border-zinc-200 hover:border-indigo-400 rounded-2xl p-4 transition-all duration-200 hover:bg-indigo-50/50">
                <div class="flex items-start justify-between mb-1">
                  <span class="font-bold text-sm text-neutral-900">Photochromic</span>
                  <span class="text-xs font-bold text-indigo-600">+Rp 300.000</span>
                </div>
                <span class="text-xs text-gray-500">Lensa adaptif berubah sesuai cahaya</span>
              </div>
            </label>

            <label class="lens-option cursor-pointer" id="lens-progressive">
              <input type="radio" name="lens_type" value="progressive" class="sr-only" />
              <div class="border-2 border-zinc-200 hover:border-indigo-400 rounded-2xl p-4 transition-all duration-200 hover:bg-indigo-50/50">
                <div class="flex items-start justify-between mb-1">
                  <span class="font-bold text-sm text-neutral-900">Progresif</span>
                  <span class="text-xs font-bold text-indigo-600">+Rp 500.000</span>
                </div>
                <span class="text-xs text-gray-500">Untuk jauh, menengah, dan dekat sekaligus</span>
              </div>
            </label>
          @endif
        </div>
      </div>

      {{-- Resep --}}
      <div>
        <h3 class="text-base font-bold text-neutral-900 mb-4">
          4. Resep Kacamata
          <span class="text-gray-400 font-normal text-sm ml-1">(Opsional)</span>
        </h3>
        <div class="prescription-section space-y-6">

          {{-- Upload Zone --}}
          <div class="upload-zone" id="upload-zone" tabindex="0" role="button" aria-label="Upload file resep">
            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-3"></i>
            <p class="text-sm font-medium text-neutral-900">Klik untuk upload foto resep</p>
            <p class="text-xs text-gray-500 mt-1">JPG, PNG atau PDF (Max 5MB)</p>
            <input type="file" id="prescription-upload" class="hidden" accept=".jpg,.jpeg,.png,.pdf" />
          </div>

          {{-- Divider --}}
          <div class="flex items-center gap-4">
            <div class="h-px bg-zinc-300 flex-1"></div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">ATAU ISI MANUAL</span>
            <div class="h-px bg-zinc-300 flex-1"></div>
          </div>

          {{-- Manual Prescription --}}
          <div class="grid md:grid-cols-2 gap-6">

            {{-- Mata Kanan --}}
            <div>
              <div class="flex items-center gap-2 mb-3">
                <div class="w-6 h-6 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">R</div>
                <h4 class="text-sm font-bold text-neutral-900">Kanan (OD)</h4>
              </div>
              <div class="grid grid-cols-3 gap-2">
                <input type="text" name="rx_r_sph" placeholder="SPH" class="prescription-input" aria-label="Mata Kanan SPH" />
                <input type="text" name="rx_r_cyl" placeholder="CYL" class="prescription-input" aria-label="Mata Kanan CYL" />
                <input type="text" name="rx_r_axis" placeholder="AXIS" class="prescription-input" aria-label="Mata Kanan AXIS" />
              </div>
            </div>

            {{-- Mata Kiri --}}
            <div>
              <div class="flex items-center gap-2 mb-3">
                <div class="w-6 h-6 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">L</div>
                <h4 class="text-sm font-bold text-neutral-900">Kiri (OS)</h4>
              </div>
              <div class="grid grid-cols-3 gap-2">
                <input type="text" name="rx_l_sph" placeholder="SPH" class="prescription-input" aria-label="Mata Kiri SPH" />
                <input type="text" name="rx_l_cyl" placeholder="CYL" class="prescription-input" aria-label="Mata Kiri CYL" />
                <input type="text" name="rx_l_axis" placeholder="AXIS" class="prescription-input" aria-label="Mata Kiri AXIS" />
              </div>
            </div>

          </div>

          <div>
            <label class="block text-sm font-bold text-neutral-900 mb-2">
              PD (Pupillary Distance)
              <span class="text-gray-400 font-normal">mm</span>
            </label>
            <input type="text" name="rx_pd" placeholder="Contoh: 62" class="prescription-input w-24" />
          </div>

        </div>
      </div>

      {{-- Pengiriman --}}
      <div>
        <h3 class="text-base font-bold text-neutral-900 mb-4">5. Pengiriman</h3>
        <div class="grid grid-cols-2 gap-4" role="radiogroup">

          <div class="delivery-option selected"
               data-delivery="pickup"
               tabindex="0"
               role="radio"
               aria-checked="true">
            <i class="fa-solid fa-store"></i>
            <span class="delivery-option-name">Ambil di Toko</span>
          </div>

          <div class="delivery-option"
               data-delivery="delivery"
               tabindex="0"
               role="radio"
               aria-checked="false">
            <i class="fa-solid fa-motorcycle"></i>
            <span class="delivery-option-name">Antar ke Rumah</span>
          </div>

        </div>
      </div>

      {{-- Kode Promo --}}
      <div>
        <h3 class="text-base font-bold text-neutral-900 mb-4">6. Kode Promo</h3>
        <div class="bg-neutral-50 border border-zinc-200 rounded-2xl p-4 md:p-5 space-y-3">
          <div class="flex flex-col sm:flex-row gap-3">
            <input
              type="text"
              id="promo-code-input"
              placeholder="Contoh: HEMAT10"
              maxlength="20"
              class="prescription-input text-left flex-1"
            />
            <button type="button"
                    id="apply-promo-btn"
                  data-promo-url="{{ route('cart.promo') }}"
                    class="btn btn-outline btn-sm"
                    style="justify-content:center;min-width:120px;">
              Apply
            </button>
            <button type="button"
                    id="remove-promo-btn"
                    class="btn btn-ghost btn-sm"
                    style="justify-content:center;min-width:120px;display:none;">
              Hapus
            </button>
          </div>
          <p class="text-xs font-medium" id="promo-feedback" style="display:none;"></p>
        </div>
      </div>

      {{-- Divider --}}
      <hr class="border-zinc-200">

      {{-- Actions --}}
      <div class="flex flex-col sm:flex-row gap-4 pt-2">
        <button
          class="btn btn-whatsapp btn-xl flex-1 justify-center"
          id="wa-order-btn"
          data-product-name="{{ $product->name ?? 'Classic Round Frame' }}"
          data-wa-number="{{ $settings['whatsapp_number'] ?? '6281234567890' }}">
          <i class="fa-brands fa-whatsapp text-lg"></i>
          Beli via WhatsApp
        </button>

        <button
          class="btn btn-outline btn-xl flex-1 justify-center"
          id="add-to-cart-btn"
          data-cart-url="{{ route('cart.add') }}"
          data-product-id="{{ $product->id ?? '' }}"
          data-product-name="{{ $product->name ?? 'Classic Round Frame' }}"
          data-product-slug="{{ $product->slug ?? 'classic-round-frame' }}"
          data-product-price="{{ $product->effective_price ?? $product->price ?? 299000 }}"
          data-product-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}">
          <i class="fa-solid fa-cart-plus"></i>
          Tambah ke Keranjang
        </button>
      </div>

      {{-- Jaminan --}}
      <div class="flex justify-center gap-8 mt-2 text-gray-500 text-xs font-medium">
        <div class="flex items-center gap-1.5">
          <i class="fa-solid fa-shield-halved text-indigo-400"></i>
          Garansi 1 Tahun
        </div>
        <div class="flex items-center gap-1.5">
          <i class="fa-solid fa-rotate-left text-indigo-400"></i>
          14 Hari Retur
        </div>
        <div class="flex items-center gap-1.5">
          <i class="fa-solid fa-truck-fast text-indigo-400"></i>
          Pengiriman Aman
        </div>
      </div>

    </div>
  </div>
</main>

<script type="application/json" id="product-color-variants">
{!! json_encode($colorVariantList) !!}
</script>
<script type="application/json" id="product-lens-variants">
{!! json_encode($lensVariantList) !!}
</script>
<script type="application/json" id="product-frame-variants">
{!! json_encode($frameVariantList) !!}
</script>
<script type="application/json" id="product-gallery-images">
{!! json_encode($galleryImages) !!}
</script>

@endsection
