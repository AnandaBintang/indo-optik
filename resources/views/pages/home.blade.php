@extends('layouts.app')

@section('title', ($settings['site_name'] ?? 'IndoOptik') . ' — Kacamata & Lensa Berkualitas Terbaik')
@section('description', 'IndoOptik menyediakan kacamata dan lensa berkualitas tinggi dengan harga terjangkau. Temukan frame modern, periksa mata, dan buat janji temu secara online.')
@section('og_title', 'IndoOptik — Kacamata & Lensa Berkualitas Terbaik')
@section('og_description', 'Solusi optik modern yang praktis, terjangkau, dan stylish di Indonesia.')

@section('content')

{{-- ============================================================
     HERO
     ============================================================ --}}
<section class="relative overflow-hidden bg-[#11131a] text-white">
  <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(129,140,248,0.35),transparent_34%),linear-gradient(135deg,#11131a_0%,#1f2433_48%,#f3f4f6_100%)]"></div>
  <div class="absolute -right-24 top-24 h-64 w-64 rounded-full bg-indigo-400/20 blur-3xl"></div>

  <div class="relative page-shell grid min-h-[640px] items-center gap-10 py-12 sm:py-16 lg:grid-cols-[1.02fr_0.98fr] lg:py-20">
    <div class="max-w-3xl" data-animate>
      <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-indigo-100 backdrop-blur">
        <i class="fa-solid fa-sparkles"></i> Selamat Datang di IndoOptik
      </span>

      <h1 class="mt-6 text-[clamp(2.65rem,7vw,5.35rem)] font-black leading-[0.98] tracking-[-0.055em]">
        Penglihatan jernih,<br>
        <span class="bg-gradient-to-r from-indigo-300 via-violet-300 to-white bg-clip-text text-transparent">gaya terbaik</span> Anda.
      </h1>

      <p class="mt-6 max-w-2xl text-base leading-8 text-white/78 sm:text-lg lg:text-xl">
        Katalog frame, konsultasi lensa, dan janji periksa mata dibuat praktis. Pilih produk, lalu konfirmasi langsung lewat WhatsApp.
      </p>

      <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
        <a href="{{ route('services.index') }}" class="btn btn-primary btn-xl w-full sm:w-auto" id="hero-booking-btn">
          <i class="fa-solid fa-calendar-check text-base"></i>
          Buat Janji Temu
        </a>
        <a href="{{ route('catalog.index') }}" class="btn btn-xl btn-light w-full sm:w-auto" id="hero-catalog-btn">
          <i class="fa-solid fa-magnifying-glass text-base"></i>
          Lihat Katalog
        </a>
      </div>

      <div class="mt-8 grid grid-cols-3 gap-2 sm:max-w-xl sm:gap-3" data-animate data-animate-delay="2">
        <div class="rounded-2xl border border-white/10 bg-white/10 p-3 backdrop-blur sm:p-4">
          <p class="text-2xl font-black sm:text-3xl" data-count="5000">0</p>
          <p class="mt-1 text-[11px] font-medium text-white/62 sm:text-xs">Pelanggan Puas</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 p-3 backdrop-blur sm:p-4">
          <p class="text-2xl font-black sm:text-3xl" data-count="200">0</p>
          <p class="mt-1 text-[11px] font-medium text-white/62 sm:text-xs">Pilihan Frame</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 p-3 backdrop-blur sm:p-4">
          <p class="text-2xl font-black sm:text-3xl">8+</p>
          <p class="mt-1 text-[11px] font-medium text-white/62 sm:text-xs">Tahun Pengalaman</p>
        </div>
      </div>
    </div>

    <div class="relative hidden lg:block" data-animate="slide-right">
      <div class="absolute -left-8 top-10 h-24 w-24 rounded-full bg-indigo-400/25 blur-2xl"></div>
      <div class="relative overflow-hidden rounded-[44px] border border-white/15 bg-white/10 p-4 shadow-2xl shadow-neutral-950/30 backdrop-blur">
        <img
          src="https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&q=80&w=1200"
          alt="Kacamata modern IndoOptik"
          class="h-[470px] w-full rounded-[32px] object-cover"
        />
        <div class="absolute bottom-8 left-8 right-8 rounded-3xl border border-white/20 bg-neutral-950/70 p-5 backdrop-blur-md">
          <p class="text-sm font-bold text-indigo-200">Konsultasi cepat</p>
          <p class="mt-1 text-2xl font-black">Pilih frame hari ini, konfirmasi via WhatsApp.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ============================================================
     PROMO BANNER
     ============================================================ --}}
@guest
  <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 py-4">
    <div class="page-shell flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
      <div class="flex flex-col sm:flex-row items-center gap-3">
        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
          <i class="fa-solid fa-tag"></i> Promo Terbatas
        </span>
        <p class="text-white text-base font-medium">Daftar sekarang dan dapatkan diskon hingga 30% untuk pengguna baru!</p>
      </div>
      <a href="{{ route('register') }}" class="btn btn-sm bg-white text-indigo-600 font-bold hover:bg-neutral-50 hover:-translate-y-px">
        Daftar Sekarang
      </a>
    </div>
  </div>
@endguest

{{-- ============================================================
     KATEGORI
     ============================================================ --}}
<section class="py-24 bg-neutral-50">
  <div class="page-shell">
    <div class="section-header" data-animate>
      <span class="section-label">Koleksi Kami</span>
      <h2 class="section-title">Temukan Produk<br />yang Tepat untuk Anda</h2>
      <p class="section-subtitle">Dari frame klasik hingga modern, kami punya semua yang Anda butuhkan</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6" data-stagger>

      {{-- Kacamata --}}
      <a href="{{ route('catalog.index') }}" class="group micro-card relative h-[420px] rounded-[32px] overflow-hidden shadow-md block">
        <img
          src="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&q=80&w=800"
          alt="Kacamata frame premium"
          class="absolute inset-0 w-full h-full object-cover micro-zoom"
          loading="lazy"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/90 via-neutral-900/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-8 w-full">
          <div class="w-14 h-14 bg-indigo-500 text-white rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-indigo-500/40 text-2xl">
            <i class="fa-solid fa-glasses"></i>
          </div>
          <h3 class="text-3xl font-extrabold text-white mb-2">Kacamata</h3>
          <p class="text-white/80 text-sm mb-5 font-medium">Frame premium dengan desain modern</p>
          <span class="btn btn-sm bg-white text-indigo-600 font-bold group-hover:bg-indigo-50 transition border-0">
            Lihat Koleksi <i class="fa-solid fa-arrow-right ml-1"></i>
          </span>
        </div>
      </a>

      {{-- Lensa --}}
      <a href="{{ route('catalog.index') }}" class="group micro-card relative h-[420px] rounded-[32px] overflow-hidden shadow-md block">
        <img
          src="https://images.unsplash.com/photo-1517948430535-1e2469d314fe?q=80&w=2070&auto=format&fit=crop"
          alt="Lensa berkualitas tinggi"
          class="absolute inset-0 w-full h-full object-cover micro-zoom"
          loading="lazy"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/90 via-neutral-900/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-8 w-full">
          <div class="w-14 h-14 bg-indigo-500 text-white rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-indigo-500/40 text-2xl">
            <i class="fa-solid fa-eye"></i>
          </div>
          <h3 class="text-3xl font-extrabold text-white mb-2">Lensa</h3>
          <p class="text-white/80 text-sm mb-5 font-medium">Teknologi lensa terkini untuk penglihatan optimal</p>
          <span class="btn btn-sm bg-white text-indigo-600 font-bold group-hover:bg-indigo-50 transition border-0">
            Lihat Koleksi <i class="fa-solid fa-arrow-right ml-1"></i>
          </span>
        </div>
      </a>

      {{-- Kontak Lensa --}}
      <a href="{{ route('catalog.index') }}" class="group micro-card relative h-[420px] rounded-[32px] overflow-hidden shadow-md block">
        <img
          src="https://plus.unsplash.com/premium_photo-1663048816150-1638f707cea2?q=80&w=2071&auto=format&fit=crop"
          alt="Kontak lensa softlens"
          class="absolute inset-0 w-full h-full object-cover micro-zoom"
          loading="lazy"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/90 via-neutral-900/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-8 w-full">
          <div class="w-14 h-14 bg-indigo-500 text-white rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-indigo-500/40 text-2xl">
            <i class="fa-solid fa-eye-dropper"></i>
          </div>
          <h3 class="text-3xl font-extrabold text-white mb-2">Kontak Lensa</h3>
          <p class="text-white/80 text-sm mb-5 font-medium">Softlens berkualitas untuk kenyamanan maksimal</p>
          <span class="btn btn-sm bg-white text-indigo-600 font-bold group-hover:bg-indigo-50 transition border-0">
            Lihat Koleksi <i class="fa-solid fa-arrow-right ml-1"></i>
          </span>
        </div>
      </a>

    </div>
  </div>
</section>

{{-- ============================================================
     PRODUK UNGGULAN
     ============================================================ --}}
<section class="py-24 bg-white">
  <div class="page-shell">
    <div class="section-header" data-animate>
      <span class="section-label">Paling Populer</span>
      <h2 class="section-title">Pilihan Teratas</h2>
      <p class="section-subtitle">Frame paling banyak diminati pelanggan kami</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 xl:gap-8" data-stagger>
      @forelse($featuredProducts ?? [] as $product)
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
              <div class="w-full h-full flex items-center justify-center text-gray-300">
                <i class="fa-solid fa-glasses text-5xl"></i>
              </div>
            @endif
            @if($product->is_featured)
              <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-indigo-600 text-[10px] font-bold px-2 py-1 rounded-full">
                <i class="fa-solid fa-fire text-orange-500 mr-1"></i> Bestseller
              </span>
            @endif
            @if($product->discount_price)
              <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">
                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
              </span>
            @endif
          </div>
          <div class="p-5 flex flex-col flex-1">
            <h4 class="text-[0.95rem] font-bold text-neutral-900 mb-1 leading-tight">{{ $product->name }}</h4>
            <div class="flex items-center gap-1.5 mb-3">
              <div class="flex text-indigo-500 text-[10px]">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
              </div>
              <span class="text-gray-400 text-xs">4.8</span>
            </div>
            <div class="mt-auto mb-4">
              @if($product->discount_price)
                <p class="text-[0.8rem] text-gray-400 line-through font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-[1.35rem] font-extrabold text-indigo-600">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
              @else
                <p class="text-[1.35rem] font-extrabold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
              @endif
            </div>
            <button class="btn btn-outline btn-sm btn-block group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 pointer-events-none">
              Lihat Detail
            </button>
          </div>
        </a>
      @empty
        <div class="col-span-full rounded-3xl border border-zinc-200 bg-zinc-50 p-10 text-center">
          <p class="text-lg font-bold text-neutral-900">Belum ada produk unggulan tersedia.</p>
          <p class="mt-2 text-sm text-gray-500">Tambahkan produk dari panel admin agar tampil di beranda.</p>
        </div>
      @endforelse
    </div>

    <div class="text-center mt-12" data-animate>
      <a href="{{ route('catalog.index') }}" class="btn btn-outline btn-lg border-2">
        Lihat Semua Produk <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

{{-- ============================================================
     PENAWARAN SPESIAL
     ============================================================ --}}
<section class="py-24 bg-neutral-50">
  <div class="page-shell">
    <div class="section-header" data-animate>
      <span class="section-label">Promosi</span>
      <h2 class="section-title">Penawaran Spesial</h2>
      <p class="section-subtitle">Promosi terbatas khusus untuk Anda</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8" data-stagger>

      <div class="bg-white p-10 rounded-[32px] shadow-sm border border-zinc-100 text-center hover:-translate-y-2 transition-all duration-300 hover:shadow-xl">
        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-indigo-500 to-indigo-400 rounded-[20px] flex items-center justify-center text-3xl text-white mb-6 shadow-lg shadow-indigo-200">
          <i class="fa-solid fa-percent"></i>
        </div>
        <h3 class="text-3xl font-extrabold text-neutral-900 mb-3">30% OFF</h3>
        <p class="text-gray-500 mb-6 leading-relaxed text-[0.95rem]">Diskon menarik untuk pembelian frame kacamata pilihan kategori premium.</p>
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-700 transition">
          Belanja Sekarang <i class="fa-solid fa-arrow-right text-sm"></i>
        </a>
      </div>

      <div class="bg-white p-10 rounded-[32px] shadow-sm border border-zinc-100 text-center hover:-translate-y-2 transition-all duration-300 hover:shadow-xl">
        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-indigo-500 to-indigo-400 rounded-[20px] flex items-center justify-center text-3xl text-white mb-6 shadow-lg shadow-indigo-200">
          <i class="fa-solid fa-gift"></i>
        </div>
        <h3 class="text-3xl font-extrabold text-neutral-900 mb-3">GRATIS</h3>
        <p class="text-gray-500 mb-6 leading-relaxed text-[0.95rem]">Gratis lapisan anti-scratch coating dengan asimilasi pembelian lensa.</p>
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-700 transition">
          Pelajari Lebih Lanjut <i class="fa-solid fa-arrow-right text-sm"></i>
        </a>
      </div>

      <div class="bg-white p-10 rounded-[32px] shadow-sm border border-zinc-100 text-center hover:-translate-y-2 transition-all duration-300 hover:shadow-xl">
        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-indigo-500 to-indigo-400 rounded-[20px] flex items-center justify-center text-3xl text-white mb-6 shadow-lg shadow-indigo-200">
          <i class="fa-solid fa-gem"></i>
        </div>
        <h3 class="text-3xl font-extrabold text-neutral-900 mb-3">35% OFF</h3>
        <p class="text-gray-500 mb-6 leading-relaxed text-[0.95rem]">Upgrade ke paket lensa premium kami dengan diskon super spesial.</p>
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-700 transition">
          Dapatkan Diskon <i class="fa-solid fa-arrow-right text-sm"></i>
        </a>
      </div>

    </div>
  </div>
</section>

{{-- ============================================================
     LAYANAN UNGGULAN
     ============================================================ --}}
<section class="py-24 bg-white">
  <div class="page-shell">
    <div class="section-header" data-animate>
      <span class="section-label">Layanan Kami</span>
      <h2 class="section-title">Perawatan Optik Lengkap</h2>
      <p class="section-subtitle">Dari pemeriksaan mata hingga konsultasi gaya, semua tersedia</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8" data-stagger>
      <div class="bg-neutral-50 rounded-[32px] p-8 border border-zinc-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-[18px] flex items-center justify-center text-2xl mb-6">
          <i class="fa-solid fa-user-doctor"></i>
        </div>
        <h3 class="text-xl font-bold text-neutral-900 mb-3">Periksa Mata</h3>
        <p class="text-gray-500 leading-relaxed text-[0.9rem]">Pemeriksaan mata profesional dengan alat modern untuk memastikan kesehatan mata dan akurasi resep Anda.</p>
      </div>
      <div class="bg-neutral-50 rounded-[32px] p-8 border border-zinc-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-[18px] flex items-center justify-center text-2xl mb-6">
          <i class="fa-solid fa-screwdriver-wrench"></i>
        </div>
        <h3 class="text-xl font-bold text-neutral-900 mb-3">Ganti Lensa</h3>
        <p class="text-gray-500 leading-relaxed text-[0.9rem]">Layanan ganti lensa cepat dan presisi untuk semua jenis frame. Upgrade ke lensa premium dengan mudah.</p>
      </div>
      <div class="bg-neutral-50 rounded-[32px] p-8 border border-zinc-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-[18px] flex items-center justify-center text-2xl mb-6">
          <i class="fa-regular fa-lightbulb"></i>
        </div>
        <h3 class="text-xl font-bold text-neutral-900 mb-3">Konsultasi Gaya</h3>
        <p class="text-gray-500 leading-relaxed text-[0.9rem]">Tim ahli kami siap membantu Anda memilih frame yang paling cocok dengan bentuk wajah dan gaya hidup Anda.</p>
      </div>
    </div>

    <div class="text-center mt-12" data-animate>
      <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg">
        Buat Janji Temu Sekarang <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

{{-- ============================================================
     TESTIMONI
     ============================================================ --}}
<section class="py-24 bg-neutral-50">
  <div class="page-shell">
    <div class="section-header" data-animate>
      <span class="section-label">Testimoni</span>
      <h2 class="section-title">Kata Pelanggan Kami</h2>
      <p class="section-subtitle">Bergabunglah dengan ribuan pelanggan yang puas</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8" data-stagger>
      @forelse($testimonials ?? [] as $testimonial)
        <x-testimonial-card :testimonial="$testimonial" />
      @empty
        {{-- Hardcoded Testimonial 1 --}}
        <div class="bg-white border border-zinc-100 shadow-sm rounded-[28px] p-8 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative">
          <div class="absolute top-8 right-8 text-indigo-100 text-5xl">
            <i class="fa-solid fa-quote-right"></i>
          </div>
          <div class="relative z-10">
            <div class="flex text-indigo-500 text-sm mb-5 gap-0.5">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="text-gray-600 leading-relaxed italic mb-8 font-medium">
              "Kualitas dan pelayanan yang luar biasa! Kacamata baru saya pas sempurna dan terlihat cantik. Sangat direkomendasikan!"
            </p>
          </div>
          <div class="flex items-center gap-3 mt-auto">
            <div class="w-11 h-11 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">SW</div>
            <div>
              <p class="font-bold text-neutral-900 text-sm leading-tight">Sari Wijaya</p>
              <p class="text-gray-500 text-xs mt-0.5">Pelanggan Setia</p>
            </div>
          </div>
        </div>

        {{-- Hardcoded Testimonial 2 --}}
        <div class="bg-white border border-zinc-100 shadow-sm rounded-[28px] p-8 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative">
          <div class="absolute top-8 right-8 text-indigo-100 text-5xl">
            <i class="fa-solid fa-quote-right"></i>
          </div>
          <div class="relative z-10">
            <div class="flex text-indigo-500 text-sm mb-5 gap-0.5">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="text-gray-600 leading-relaxed italic mb-8 font-medium">
              "Pengiriman cepat dan customer support sangat membantu. Proses pemesanannya mudah dan transparan. Saya sangat puas!"
            </p>
          </div>
          <div class="flex items-center gap-3 mt-auto">
            <div class="w-11 h-11 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">MT</div>
            <div>
              <p class="font-bold text-neutral-900 text-sm leading-tight">Michael Tan</p>
              <p class="text-gray-500 text-xs mt-0.5 flex items-center gap-1">
                <i class="fa-solid fa-circle-check text-green-500"></i> Verified
              </p>
            </div>
          </div>
        </div>

        {{-- Hardcoded Testimonial 3 --}}
        <div class="bg-white border border-zinc-100 shadow-sm rounded-[28px] p-8 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative">
          <div class="absolute top-8 right-8 text-indigo-100 text-5xl">
            <i class="fa-solid fa-quote-right"></i>
          </div>
          <div class="relative z-10">
            <div class="flex text-indigo-500 text-sm mb-5 gap-0.5">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="text-gray-600 leading-relaxed italic mb-8 font-medium">
              "Suka sekali dengan frame baru saya! Koleksinya fantastis dan harganya sangat terjangkau. Pasti akan beli lagi."
            </p>
          </div>
          <div class="flex items-center gap-3 mt-auto">
            <div class="w-11 h-11 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">LP</div>
            <div>
              <p class="font-bold text-neutral-900 text-sm leading-tight">Lisa Prasetyo</p>
              <p class="text-gray-500 text-xs mt-0.5 flex items-center gap-1">
                <i class="fa-solid fa-circle-check text-green-500"></i> Verified
              </p>
            </div>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>

{{-- ============================================================
     CTA BANNER
     ============================================================ --}}
<section class="py-24 bg-gradient-to-br from-indigo-600 via-indigo-500 to-violet-500 relative overflow-hidden" data-animate>
  <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48Y2lyY2xlIGN4PSIyIiBjeT0iMiIgcj0iMiIgZmlsbD0iI2ZmZmZmZiIgZmlsbC1vcGFjaXR5PSIwLjA1Ii8+PC9zdmc+')] opacity-50"></div>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
    <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-5 tracking-tight">Siap Melihat Lebih Jelas?</h2>
    <p class="text-white/80 text-lg mb-10 font-medium">Buat janji periksa mata sekarang dan temukan kacamata impian Anda.</p>
    <a href="{{ route('services.index') }}" class="btn btn-xl bg-white text-indigo-600 border-white hover:bg-neutral-50 shadow-xl shadow-indigo-900/20">
      Buat Janji Sekarang <i class="fa-solid fa-arrow-right"></i>
    </a>
  </div>
</section>

@endsection
