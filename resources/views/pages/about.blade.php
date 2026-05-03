@extends('layouts.app')

@section('title', 'Tentang Kami — IndoOptik')
@section('description', 'Pelajari lebih lanjut tentang IndoOptik, misi kami, dan komitmen kami untuk memberikan perawatan mata dan produk optik kualitas terbaik.')
@section('og_title', 'Tentang Kami — IndoOptik')
@section('og_description', 'Mendefinisikan ulang perawatan mata sejak tahun 2018. Kami percaya bahwa setiap orang berhak mendapatkan penglihatan yang jernih.')

@section('content')

{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<header class="relative py-20 md:py-28 overflow-hidden bg-indigo-900 border-b border-indigo-900/50">
  <img
    src="https://images.unsplash.com/photo-1679496125315-3206c6e50d47?q=80&w=2070&auto=format&fit=crop"
    alt="Tentang Kami Background"
    class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay"
  />
  <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/90 via-indigo-800/80 to-violet-900/70"></div>

  <div class="relative page-shell text-center z-10" data-animate>
    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">Kisah Kami</h1>
    <p class="text-lg md:text-xl text-indigo-100 font-medium max-w-3xl mx-auto leading-relaxed">
      Mendefinisikan ulang perawatan mata sejak tahun 2018. Kami percaya bahwa setiap orang berhak mendapatkan penglihatan yang jernih tanpa mengorbankan gaya modern.
    </p>
  </div>
</header>

{{-- ============================================================
     MISI & VISI
     ============================================================ --}}
<section class="py-24">
  <div class="page-shell">
    <div class="grid md:grid-cols-2 gap-12 xl:gap-20 items-center">

      {{-- Image --}}
      <div data-animate="slide-left">
        <div class="aspect-square md:aspect-[4/5] rounded-[32px] overflow-hidden shadow-2xl relative">
          <img
            src="https://plus.unsplash.com/premium_photo-1693222144068-513f78a25a29?q=80&w=987&auto=format&fit=crop"
            alt="Klinik Optik Modern"
            class="w-full h-full object-cover"
          />
          <div class="absolute inset-0 border-[6px] border-white rounded-[32px] pointer-events-none"></div>
        </div>
      </div>

      {{-- Text --}}
      <div class="flex flex-col justify-center" data-animate="slide-right">
        <span class="section-label">Misi Kami</span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-neutral-900 mb-6">
          Membawa Perawatan Mata Premium untuk Semua
        </h2>

        <p class="text-gray-500 text-[1.1rem] leading-relaxed mb-6 font-medium">
          Berawal dari visi sederhana: membuat kacamata berkualitas tinggi yang dapat diakses oleh semua kalangan. Di IndoOptik, kami memotong biaya perantara untuk memberikan nilai terbaik langsung kepada pelanggan kami.
        </p>

        <ul class="space-y-5 text-gray-600 font-medium mb-10">
          <li class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0 text-sm">
              <i class="fa-solid fa-check"></i>
            </div>
            Layanan pelanggan yang luar biasa dan personal
          </li>
          <li class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0 text-sm">
              <i class="fa-solid fa-check"></i>
            </div>
            Material berkualitas tinggi &amp; lensa premium standar internasional
          </li>
          <li class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0 text-sm">
              <i class="fa-solid fa-check"></i>
            </div>
            Harga transparan tanpa biaya tersembunyi
          </li>
        </ul>

        {{-- Animated Counters --}}
        <div class="grid grid-cols-2 gap-6 bg-white p-6 rounded-2xl border border-zinc-100 shadow-sm">
          <div>
            <h4 class="text-3xl font-extrabold text-indigo-600 mb-1" data-count="15000">0</h4>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Kacamata Terjual</p>
          </div>
          <div>
            <h4 class="text-3xl font-extrabold text-indigo-600 mb-1" data-count="98">0</h4>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">% Tingkat Kepuasan</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ============================================================
     NILAI INTI
     ============================================================ --}}
<section class="py-24 bg-white border-t border-zinc-100">
  <div class="page-shell flex flex-col items-center">

    <div class="section-header" data-animate>
      <span class="section-label">Janji Kami</span>
      <h2 class="section-title">Nilai Inti Kami</h2>
      <p class="section-subtitle">Prinsip yang memandu segala hal yang kami lakukan di IndoOptik</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 w-full" data-stagger>

      {{-- Kualitas --}}
      <div class="text-center p-8">
        <div class="w-20 h-20 mx-auto bg-indigo-50 text-indigo-600 rounded-[20px] flex items-center justify-center text-3xl mb-6 shadow-sm border border-indigo-100/50">
          <i class="fa-solid fa-star"></i>
        </div>
        <h3 class="text-xl font-bold text-neutral-900 mb-3">Kualitas<br>Tanpa Kompromi</h3>
        <p class="text-gray-500 leading-relaxed text-[0.95rem]">
          Kami tidak pernah mengambil jalan pintas jika menyangkut bahan atau presisi pembuatan. Produk Anda pantas mendapatkan yang terbaik.
        </p>
      </div>

      {{-- Pelanggan --}}
      <div class="text-center p-8">
        <div class="w-20 h-20 mx-auto bg-indigo-50 text-indigo-600 rounded-[20px] flex items-center justify-center text-3xl mb-6 shadow-sm border border-indigo-100/50">
          <i class="fa-solid fa-heart"></i>
        </div>
        <h3 class="text-xl font-bold text-neutral-900 mb-3">Utamakan<br>Pelanggan</h3>
        <p class="text-gray-500 leading-relaxed text-[0.95rem]">
          Kami selalu menempatkan Anda sebagai pusat kebijakan perusahaan. Kepuasan serta kenyamanan Anda merupakan keutamaan kami.
        </p>
      </div>

      {{-- Berkelanjutan --}}
      <div class="text-center p-8">
        <div class="w-20 h-20 mx-auto bg-indigo-50 text-indigo-600 rounded-[20px] flex items-center justify-center text-3xl mb-6 shadow-sm border border-indigo-100/50">
          <i class="fa-solid fa-leaf"></i>
        </div>
        <h3 class="text-xl font-bold text-neutral-900 mb-3">Bisnis<br>Berkelanjutan</h3>
        <p class="text-gray-500 leading-relaxed text-[0.95rem]">
          Secara aktif berupaya mengurangi jejak karbon melalui praktik ramah lingkungan, memastikan misi demi kelestarian bumi.
        </p>
      </div>

    </div>
  </div>
</section>

{{-- ============================================================
     TEAM
     ============================================================ --}}
@if($teams->isNotEmpty())
  <section class="py-24 bg-neutral-50 border-t border-zinc-100">
    <div class="page-shell">
      <div class="section-header" data-animate>
        <span class="section-label">Tim Kami</span>
        <h2 class="section-title">Kenali Orang di Balik IndoOptik</h2>
        <p class="section-subtitle">Wajah-wajah yang memastikan Anda mendapatkan layanan terbaik</p>
      </div>

      <div class="flex flex-wrap justify-center gap-6" data-stagger>
        @foreach($teams as $team)
          <div class="w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] bg-white rounded-[24px] p-7 border border-zinc-100 shadow-sm text-center micro-card-soft">
            <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border border-zinc-200 bg-neutral-100 mb-4">
              @if($team->photo)
                <img src="{{ $team->image_url }}" alt="{{ $team->name }}" class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center text-xl font-extrabold text-indigo-600 bg-indigo-50">
                  {{ strtoupper(substr($team->name, 0, 1)) }}
                </div>
              @endif
            </div>
            <h4 class="text-lg font-extrabold text-neutral-900 mb-1">{{ $team->name }}</h4>
            <p class="text-gray-500 text-sm leading-relaxed">{{ $team->role }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endif

{{-- ============================================================
     CTA SECTION
     ============================================================ --}}
<section class="py-24 bg-gradient-to-br from-indigo-600 via-indigo-500 to-violet-500 overflow-hidden relative" data-animate>
  <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48Y2lyY2xlIGN4PSIyIiBjeT0iMiIgcj0iMiIgZmlsbD0iI2ZmZmZmZiIgZmlsbC1vcGFjaXR5PSIwLjA1Ii8+PC9zdmc+')] opacity-50"></div>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
    <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-5">
      Siap Bergabung<br>Bersama Kami?
    </h2>
    <p class="text-white/80 text-lg mb-10 font-medium">Temukan kacamata premium pilihan dari katalog kami hari ini!</p>
    <div class="flex flex-wrap justify-center gap-4">
      <a href="{{ route('catalog.index') }}" class="btn btn-xl bg-white text-indigo-600 shadow-xl border border-white hover:bg-neutral-50 font-bold">
        Jelajahi Katalog <i class="fa-solid fa-arrow-right"></i>
      </a>
      <a href="{{ route('services.index') }}" class="btn btn-xl bg-white/10 text-white font-semibold border border-white/30 backdrop-blur-md hover:bg-white/20">
        <i class="fa-solid fa-calendar-check"></i>
        Buat Janji Temu
      </a>
    </div>
  </div>
</section>

@endsection
