@extends('layouts.app')

@section('title', 'Keranjang Belanja — IndoOptik')
@section('description', 'Tinjau produk di keranjang belanja Anda dan selesaikan pesanan via WhatsApp dengan mudah.')
@section('og_title', 'Keranjang Belanja — IndoOptik')

@section('content')

<main class="page-shell py-12 flex-1">

  {{-- ============================================================
       BREADCRUMB
       ============================================================ --}}
  <nav aria-label="Breadcrumb" class="breadcrumb mb-6" data-animate>
    <a href="{{ route('home') }}">Beranda</a>
    <span class="separator">›</span>
    <span class="current">Keranjang Belanja</span>
  </nav>

  <h1 class="text-3xl md:text-4xl font-extrabold text-neutral-900 mb-8 flex items-center gap-3" data-animate>
    <i class="fa-solid fa-cart-shopping text-indigo-500"></i> Keranjang Belanja
  </h1>

  @php
    $cartItems   = session('cart', []);
    $hasItems    = count($cartItems) > 0;
    $waNumber    = $settings['whatsapp_number'] ?? '6281234567890';

    // Build subtotal
    $subtotal = 0;
    foreach ($cartItems as $item) {
      $subtotal += ($item['product_price'] ?? 0) * ($item['quantity'] ?? 1);
      if (!empty($item['frame_price'])) {
        $subtotal += ($item['frame_price'] ?? 0) * ($item['quantity'] ?? 1);
      }
      if (!empty($item['lens_price'])) {
        $subtotal += ($item['lens_price'] ?? 0) * ($item['quantity'] ?? 1);
      }
    }
    $shipping  = 0; // Free shipping
    $total     = $subtotal + $shipping;

    // Build WhatsApp message
    $waLines = ['Halo IndoOptik! Saya ingin memesan:', ''];
    $itemNum = 1;
    foreach ($cartItems as $item) {
      $linePrice = ($item['product_price'] ?? 0) + ($item['frame_price'] ?? 0) + ($item['lens_price'] ?? 0);
      $waLines[] = $itemNum . '. ' . ($item['product_name'] ?? 'Produk');
      if (!empty($item['color']))          $waLines[] = '   Warna: ' . $item['color'];
      if (!empty($item['frame_type']))     $waLines[] = '   Frame: ' . $item['frame_type'];
      if (!empty($item['lens_type']))      $waLines[] = '   Lensa: ' . $item['lens_type'];
      if (!empty($item['delivery_type']))  $waLines[] = '   Pengiriman: ' . ($item['delivery_type'] === 'delivery' ? 'Antar ke Rumah' : 'Ambil di Toko');
      $waLines[] = '   Harga: Rp ' . number_format($linePrice, 0, ',', '.');
      $waLines[] = '';
      $itemNum++;
    }
    $waLines[] = 'Total: Rp ' . number_format($total, 0, ',', '.');
    $waLines[] = '';
    $waLines[] = 'Mohon konfirmasinya. Terima kasih!';
    $waMessage = implode("\n", $waLines);
  @endphp

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- ============================================================
         CART ITEMS
         ============================================================ --}}
    <div class="lg:col-span-2 flex flex-col gap-5" id="cart-item-container">

      @if($hasItems)
        @foreach($cartItems as $key => $item)
          @php
            $itemPrice    = ($item['product_price'] ?? 0) + ($item['frame_price'] ?? 0) + ($item['lens_price'] ?? 0);
            $itemQuantity = $item['quantity'] ?? 1;
            $itemTotal    = $itemPrice * $itemQuantity;
          @endphp

          <div class="bg-white rounded-3xl p-6 md:p-7 shadow-sm border border-zinc-100 flex flex-col sm:flex-row gap-6 hover:shadow-md transition-all duration-300 relative group cart-item-card" data-animate>

            {{-- Product Image --}}
            <div class="w-full sm:w-40 h-40 bg-neutral-100 rounded-2xl overflow-hidden shrink-0">
              @if(!empty($item['image']))
                <img
                  src="{{ $item['image'] }}"
                  alt="{{ $item['product_name'] ?? 'Produk' }}"
                  class="w-full h-full object-cover"
                  loading="lazy"
                />
              @else
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                  <i class="fa-solid fa-glasses text-4xl"></i>
                </div>
              @endif
            </div>

            {{-- Product Info --}}
            <div class="flex-1 flex flex-col justify-between">
              <div class="flex justify-between items-start gap-4 mb-3">
                <div>
                  <h3 class="text-xl font-bold text-neutral-900 mb-1">{{ $item['product_name'] ?? 'Produk' }}</h3>
                  <p class="text-2xl font-extrabold text-indigo-600">Rp {{ number_format($itemTotal, 0, ',', '.') }}</p>
                </div>

                {{-- Remove Button --}}
                <form method="POST" action="{{ route('cart.remove', $key) }}" class="shrink-0">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="w-[34px] h-[34px] flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition"
                          aria-label="Hapus item"
                          onclick="return confirm('Hapus produk ini dari keranjang?')">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </form>
              </div>

              {{-- Item Details --}}
              <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-sm">
                @if(!empty($item['color']))
                  <p class="flex flex-col">
                    <span class="text-gray-400 text-xs mb-0.5">Warna</span>
                    <span class="text-neutral-900 font-semibold">
                      <i class="fa-solid fa-droplet text-indigo-400 mr-1 opacity-70"></i>{{ $item['color'] }}
                    </span>
                  </p>
                @endif

                @if(!empty($item['lens_type']))
                  <p class="flex flex-col">
                    <span class="text-gray-400 text-xs mb-0.5">Lensa</span>
                    <span class="text-neutral-900 font-semibold">
                      <i class="fa-solid fa-eye text-indigo-400 mr-1 opacity-70"></i>{{ $item['lens_type'] }}
                    </span>
                  </p>
                @endif

                @if(!empty($item['frame_type']))
                  <p class="flex flex-col">
                    <span class="text-gray-400 text-xs mb-0.5">Frame</span>
                    <span class="text-neutral-900 font-semibold">
                      <i class="fa-solid fa-glasses text-indigo-400 mr-1 opacity-70"></i>{{ $item['frame_type'] }}
                    </span>
                  </p>
                @endif

                @if(!empty($item['delivery_type']))
                  <p class="flex flex-col">
                    <span class="text-gray-400 text-xs mb-0.5">Pengiriman</span>
                    <span class="text-neutral-900 font-semibold">
                      @if($item['delivery_type'] === 'delivery')
                        <i class="fa-solid fa-motorcycle text-indigo-400 mr-1 opacity-70"></i>Antar ke Rumah
                      @else
                        <i class="fa-solid fa-store text-indigo-400 mr-1 opacity-70"></i>Ambil di Toko
                      @endif
                    </span>
                  </p>
                @endif
              </div>

              {{-- Quantity & Prescription note --}}
              <div class="flex items-center gap-4 mt-3 pt-3 border-t border-zinc-100">
                <span class="text-xs text-gray-400 font-medium">Qty: {{ $itemQuantity }}</span>

                @if(!empty($item['prescription_data']) && array_filter((array) $item['prescription_data']))
                  <span class="inline-flex items-center gap-1 text-xs text-green-600 font-semibold bg-green-50 px-2 py-0.5 rounded-full">
                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                    Resep disertakan
                  </span>
                @endif

                @if(!empty($item['promo_code']))
                  <span class="inline-flex items-center gap-1 text-xs text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded-full">
                    <i class="fa-solid fa-tag text-[10px]"></i>
                    Promo: {{ $item['promo_code'] }}
                  </span>
                @endif

                @if((!empty($item['frame_price']) && $item['frame_price'] > 0) || (!empty($item['lens_price']) && $item['lens_price'] > 0))
                  <span class="text-xs text-gray-400 font-medium ml-auto">
                    Produk + Frame + Lensa: Rp {{ number_format($item['product_price'], 0, ',', '.') }} + Rp {{ number_format($item['frame_price'] ?? 0, 0, ',', '.') }} + Rp {{ number_format($item['lens_price'], 0, ',', '.') }}
                  </span>
                @endif
              </div>
            </div>
          </div>
        @endforeach

        {{-- Clear Cart Button --}}
        <div class="flex justify-end pt-2">
          <form method="POST" action="{{ route('cart.clear') }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 text-sm text-red-500 hover:text-red-700 font-semibold hover:bg-red-50 px-4 py-2 rounded-xl transition"
                    onclick="return confirm('Kosongkan seluruh keranjang?')">
              <i class="fa-solid fa-trash"></i>
              Kosongkan Keranjang
            </button>
          </form>
        </div>

      @else

        {{-- Empty State --}}
        <div id="empty-state" class="bg-white rounded-3xl p-12 text-center shadow-sm border border-zinc-100">
          <div class="text-6xl text-gray-200 mb-4">
            <i class="fa-solid fa-basket-shopping"></i>
          </div>
          <p class="text-xl font-bold text-neutral-900 mb-2">Keranjang masih kosong</p>
          <p class="text-gray-500 mb-6 font-medium">Belum ada produk yang Anda tambahkan ke keranjang.</p>
          <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-magnifying-glass"></i>
            Mulai Belanja
          </a>
        </div>

      @endif

      {{-- Continue Shopping --}}
      @if($hasItems)
        <div class="flex items-center justify-between pt-2">
          <a href="{{ route('catalog.index') }}"
             class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-700 transition text-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Lanjut Belanja
          </a>
          <span class="text-sm text-gray-400 font-medium">
            {{ count($cartItems) }} produk dalam keranjang
          </span>
        </div>
      @endif

    </div>

    {{-- ============================================================
         ORDER SUMMARY
         ============================================================ --}}
    <div class="lg:col-span-1">

      @if($hasItems)
        <div id="summary-card"
             class="bg-white rounded-3xl p-7 shadow-sm border border-zinc-100 sticky top-24"
             data-animate>

          <h2 class="text-xl font-bold text-neutral-900 mb-6 border-b border-zinc-100 pb-4">
            Ringkasan Pesanan
          </h2>

          {{-- Item Lines --}}
          <div class="space-y-3 mb-6 text-sm">
            @foreach($cartItems as $item)
              @php
                $linePrice = (($item['product_price'] ?? 0) + ($item['frame_price'] ?? 0) + ($item['lens_price'] ?? 0)) * ($item['quantity'] ?? 1);
              @endphp
              <div class="flex justify-between gap-2">
                <span class="text-gray-500 font-medium truncate max-w-[65%]">
                  {{ $item['product_name'] ?? 'Produk' }}
                  @if(($item['quantity'] ?? 1) > 1)
                    <span class="text-gray-400">(×{{ $item['quantity'] }})</span>
                  @endif
                </span>
                <span class="font-bold text-neutral-900 shrink-0">
                  Rp {{ number_format($linePrice, 0, ',', '.') }}
                </span>
              </div>
            @endforeach

            <div class="flex justify-between pt-3 border-t border-zinc-100">
              <span class="text-gray-500 font-medium">Subtotal</span>
              <span class="font-bold text-neutral-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
              <span class="text-gray-500 font-medium">Ongkos Kirim</span>
              <span class="font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-md">Gratis</span>
            </div>

            {{-- Promo Discount (if any) --}}
            @php
              $totalPromoDiscount = 0;
              foreach ($cartItems as $item) {
                if (!empty($item['promo_discount'])) {
                  $totalPromoDiscount += $item['promo_discount'];
                }
              }
            @endphp
            @if($totalPromoDiscount > 0)
              <div class="flex justify-between text-green-600">
                <span class="font-medium flex items-center gap-1">
                  <i class="fa-solid fa-tag text-xs"></i> Diskon Promo
                </span>
                <span class="font-bold">-Rp {{ number_format($totalPromoDiscount, 0, ',', '.') }}</span>
              </div>
            @endif

            <div class="pt-4 border-t border-zinc-100 flex justify-between text-lg font-black">
              <span class="text-neutral-900">Total</span>
              <span class="text-indigo-600">
                Rp {{ number_format($total - $totalPromoDiscount, 0, ',', '.') }}
              </span>
            </div>
          </div>

          {{-- WhatsApp Checkout Button --}}
          <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waMessage) }}"
             target="_blank"
             rel="noopener noreferrer"
             class="w-full bg-[#25D366] hover:bg-[#20bd5a] text-white py-4 rounded-2xl font-bold flex items-center justify-center gap-3 transition-all duration-200 shadow-lg shadow-green-200 hover:-translate-y-0.5 mb-4 text-base no-underline">
            <i class="fa-brands fa-whatsapp text-2xl leading-none"></i>
            Checkout via WhatsApp
          </a>

          <p class="text-center text-gray-400 text-xs font-medium flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-lock text-[10px]"></i>
            Transaksi aman. Anda akan diarahkan ke WhatsApp
          </p>

          {{-- Trust Badges --}}
          <div class="mt-6 pt-5 border-t border-zinc-100 grid grid-cols-3 gap-3 text-center">
            <div class="flex flex-col items-center gap-1">
              <i class="fa-solid fa-shield-halved text-indigo-400 text-lg"></i>
              <span class="text-[10px] font-semibold text-gray-500 leading-tight">Garansi 1 Tahun</span>
            </div>
            <div class="flex flex-col items-center gap-1">
              <i class="fa-solid fa-rotate-left text-indigo-400 text-lg"></i>
              <span class="text-[10px] font-semibold text-gray-500 leading-tight">14 Hari Retur</span>
            </div>
            <div class="flex flex-col items-center gap-1">
              <i class="fa-solid fa-truck-fast text-indigo-400 text-lg"></i>
              <span class="text-[10px] font-semibold text-gray-500 leading-tight">Pengiriman Aman</span>
            </div>
          </div>

        </div>

      @else

        {{-- Summary hidden when cart empty --}}
        <div id="summary-card" class="hidden">
          {{-- Hidden --}}
        </div>

      @endif

    </div>

  </div>

</main>

@endsection
