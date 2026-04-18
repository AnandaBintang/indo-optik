@extends('layouts.app')

@section('title', ($product->name ?? 'Detail Produk') . ' — IndoOptik')
@section('description', $product->short_description ?? $product->meta_description ?? 'Temukan produk optik berkualitas di IndoOptik.')
@section('og_title', ($product->name ?? 'Produk') . ' — IndoOptik')
@section('og_description', $product->short_description ?? 'Produk optik premium dari IndoOptik.')
@section('og_type', 'product')

@section('content')

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
          <h3 class="text-base font-bold text-neutral-900">1. Pilih Warna</h3>
          <span class="text-sm font-semibold text-indigo-600" id="selected-color-label">Hitam</span>
        </div>
        {{-- Color swatches injected by JS via COLOR_VARIANTS; fallback static swatches --}}
        <div class="flex flex-wrap gap-3" id="color-swatches">
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
        </div>
      </div>

      {{-- Tipe Lensa --}}
      <div>
        <div class="flex justify-between items-end mb-4">
          <h3 class="text-base font-bold text-neutral-900">2. Pilih Tipe Lensa</h3>
          <a href="{{ route('services.index') }}" class="text-sm text-indigo-600 hover:underline">Panduan Lensa</a>
        </div>
        {{-- Lens options injected by JS via LENS_VARIANTS; fallback static options --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="lens-options" role="radiogroup" aria-label="Tipe Lensa">

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

        </div>
      </div>

      {{-- Resep --}}
      <div>
        <h3 class="text-base font-bold text-neutral-900 mb-4">
          3. Resep Kacamata
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
        <h3 class="text-base font-bold text-neutral-900 mb-4">4. Pengiriman</h3>
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
        <h3 class="text-base font-bold text-neutral-900 mb-4">5. Kode Promo</h3>
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

@push('scripts')
<script>
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    // ---- Color Swatches ----
    const swatches = document.querySelectorAll('.color-swatch');
    const colorLabel = document.getElementById('selected-color-label');

    swatches.forEach(function (swatch) {
      swatch.addEventListener('click', function () {
        swatches.forEach(function (s) {
          s.classList.remove('ring-2', 'ring-offset-2', 'ring-indigo-500', 'border-indigo-500');
          s.classList.add('border-zinc-200');
          s.setAttribute('aria-pressed', 'false');
        });
        this.classList.add('ring-2', 'ring-offset-2', 'ring-indigo-500', 'border-indigo-500');
        this.classList.remove('border-zinc-200');
        this.setAttribute('aria-pressed', 'true');
        if (colorLabel) colorLabel.textContent = this.dataset.color || '';
      });
    });

    // ---- Lens Options ----
    const lensOptions = document.querySelectorAll('#lens-options label');

    lensOptions.forEach(function (label) {
      label.addEventListener('click', function () {
        lensOptions.forEach(function (l) {
          const inner = l.querySelector('div');
          if (inner) {
            inner.classList.remove('border-indigo-500', 'bg-indigo-50');
            inner.classList.add('border-zinc-200');
          }
        });
        const inner = this.querySelector('div');
        if (inner) {
          inner.classList.add('border-indigo-500', 'bg-indigo-50');
          inner.classList.remove('border-zinc-200');
        }
      });
    });

    // ---- Delivery Options ----
    const deliveryOptions = document.querySelectorAll('.delivery-option');

    deliveryOptions.forEach(function (opt) {
      opt.addEventListener('click', function () {
        deliveryOptions.forEach(function (d) {
          d.classList.remove('selected');
          d.setAttribute('aria-checked', 'false');
        });
        this.classList.add('selected');
        this.setAttribute('aria-checked', 'true');
      });

      opt.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.click();
        }
      });
    });

    // ---- Upload Zone ----
    const uploadZone = document.getElementById('upload-zone');
    const uploadInput = document.getElementById('prescription-upload');

    if (uploadZone && uploadInput) {
      uploadZone.addEventListener('click', function () {
        uploadInput.click();
      });
      uploadZone.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          uploadInput.click();
        }
      });
      uploadInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
          uploadZone.innerHTML = '<i class="fa-solid fa-file-circle-check text-3xl text-green-500 mb-3"></i><p class="text-sm font-medium text-neutral-900">' + this.files[0].name + '</p><p class="text-xs text-gray-500 mt-1">Klik untuk ganti file</p>';
        }
      });
    }

    // ---- Promo Code ----
    const promoInput   = document.getElementById('promo-code-input');
    const applyBtn     = document.getElementById('apply-promo-btn');
    const removeBtn    = document.getElementById('remove-promo-btn');
    const promoFeedback = document.getElementById('promo-feedback');

    if (applyBtn && promoInput) {
      applyBtn.addEventListener('click', function () {
        const code = (promoInput.value || '').trim().toUpperCase();
        if (!code) return;

        fetch('/api/promo/check', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            code: code,
            price: parseInt(document.querySelector('main').dataset.basePrice || '0', 10)
          })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (promoFeedback) {
            promoFeedback.style.display = 'block';
            if (data.valid) {
              promoFeedback.textContent = 'Kode promo berhasil diterapkan! Diskon: Rp ' + data.discount_amount.toLocaleString('id-ID');
              promoFeedback.className = 'text-xs font-medium text-green-600';
              if (removeBtn) removeBtn.style.display = 'inline-flex';
              applyBtn.style.display = 'none';
              const promoDiscount = document.getElementById('promo-discount');
              if (promoDiscount) {
                promoDiscount.textContent = 'Promo ' + code + ': -Rp ' + data.discount_amount.toLocaleString('id-ID');
                promoDiscount.style.display = 'block';
              }
            } else {
              promoFeedback.textContent = data.message || 'Kode promo tidak valid atau sudah kadaluarsa.';
              promoFeedback.className = 'text-xs font-medium text-red-600';
            }
          }
        })
        .catch(function () {
          if (promoFeedback) {
            promoFeedback.style.display = 'block';
            promoFeedback.textContent = 'Gagal memeriksa kode promo. Silakan coba lagi.';
            promoFeedback.className = 'text-xs font-medium text-red-600';
          }
        });
      });
    }

    if (removeBtn && applyBtn && promoInput && promoFeedback) {
      removeBtn.addEventListener('click', function () {
        promoInput.value = '';
        promoFeedback.style.display = 'none';
        removeBtn.style.display = 'none';
        applyBtn.style.display = 'inline-flex';
        const promoDiscount = document.getElementById('promo-discount');
        if (promoDiscount) promoDiscount.style.display = 'none';
      });
    }

    // ---- WhatsApp Order Button ----
    const waBtn = document.getElementById('wa-order-btn');
    if (waBtn) {
      waBtn.addEventListener('click', function () {
        const productName = this.dataset.productName || 'Produk';
        const waNumber    = this.dataset.waNumber || '6281234567890';
        const selectedColor = colorLabel ? colorLabel.textContent : 'Hitam';
        const selectedLens  = document.querySelector('#lens-options input[type="radio"]:checked');
        const lensName      = selectedLens ? (selectedLens.closest('label')?.querySelector('span.font-bold')?.textContent || 'Standar') : 'Standar';
        const selectedDelivery = document.querySelector('.delivery-option.selected');
        const deliveryName     = selectedDelivery ? (selectedDelivery.querySelector('.delivery-option-name')?.textContent || 'Ambil di Toko') : 'Ambil di Toko';
        const priceEl = document.getElementById('product-price');
        const price   = priceEl ? priceEl.textContent.trim() : 'N/A';

        const message = [
          'Halo IndoOptik! Saya ingin memesan:',
          '',
          '\uD83D\uDD76\uFE0F ' + productName + ' (' + selectedColor + ')',
          '\uD83D\uDD0D Lensa: ' + lensName,
          '\uD83D\uDE9A Pengiriman: ' + deliveryName,
          '',
          'Harga: ' + price,
          '',
          'Mohon konfirmasinya. Terima kasih!'
        ].join('\n');

        window.open('https://wa.me/' + waNumber + '?text=' + encodeURIComponent(message), '_blank');
      });
    }

    // ---- Add to Cart ----
    const cartBtn = document.getElementById('add-to-cart-btn');
    if (cartBtn) {
      cartBtn.addEventListener('click', function () {
        const productId   = this.dataset.productId;
        const productSlug = this.dataset.productSlug;
        const selectedColor = colorLabel ? colorLabel.textContent : 'Hitam';
        const selectedLens  = document.querySelector('#lens-options input[type="radio"]:checked');
        const lensValue     = selectedLens ? selectedLens.value : 'standard';
        const selectedDelivery = document.querySelector('.delivery-option.selected');
        const deliveryValue    = selectedDelivery ? selectedDelivery.dataset.delivery : 'pickup';

        // Prescription
        const rx = {
          r_sph:  document.querySelector('[name="rx_r_sph"]')?.value  || '',
          r_cyl:  document.querySelector('[name="rx_r_cyl"]')?.value  || '',
          r_axis: document.querySelector('[name="rx_r_axis"]')?.value || '',
          l_sph:  document.querySelector('[name="rx_l_sph"]')?.value  || '',
          l_cyl:  document.querySelector('[name="rx_l_cyl"]')?.value  || '',
          l_axis: document.querySelector('[name="rx_l_axis"]')?.value || '',
          pd:     document.querySelector('[name="rx_pd"]')?.value     || '',
        };

        const promoCode = promoInput ? promoInput.value.trim().toUpperCase() : '';

        const originalText = cartBtn.innerHTML;
        cartBtn.disabled = true;
        cartBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menambahkan...';

        fetch('{{ route("cart.add") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            product_id:        productId,
            color:             selectedColor,
            lens_type:         lensValue,
            delivery_type:     deliveryValue,
            prescription_data: rx,
            quantity:          1
          })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          cartBtn.innerHTML = '<i class="fa-solid fa-check text-green-500"></i> Ditambahkan!';
          setTimeout(function () {
            cartBtn.disabled = false;
            cartBtn.innerHTML = originalText;
          }, 2000);

          // Update cart badge
          const badge = document.getElementById('cart-badge');
          if (badge && data.cart_count > 0) {
            badge.textContent = data.cart_count;
            badge.style.display = 'flex';
          }
        })
        .catch(function () {
          cartBtn.disabled = false;
          cartBtn.innerHTML = originalText;
          alert('Gagal menambahkan ke keranjang. Silakan coba lagi.');
        });
      });
    }

    // ---- Thumbnail click handler ----
    const thumbContainer = document.getElementById('thumb-container');
    if (thumbContainer) {
      thumbContainer.querySelectorAll('button, div[class*="aspect"]').forEach(function (el) {
        el.style.cursor = 'pointer';
        el.addEventListener('click', function () {
          const img = this.querySelector('img');
          const mainImg = document.getElementById('main-product-img');
          if (img && mainImg) {
            mainImg.src = img.src;
          }
          thumbContainer.querySelectorAll('button, div[class*="aspect"]').forEach(function (t) {
            t.classList.remove('ring-2', 'ring-indigo-500', 'border-indigo-400');
          });
          this.classList.add('ring-2', 'ring-indigo-500');
        });
      });
    }

  });
})();
</script>
@endpush

@endsection
