@extends('layouts.app')

@section('title', 'Layanan & Janji Temu — IndoOptik')
@section('description', 'Pesan jadwal periksa mata secara online di IndoOptik. Ahli optometri kami siap memberikan pelayanan terbaik untuk kesehatan mata Anda.')
@section('og_title', 'Layanan & Janji Temu — IndoOptik')

@section('content')
<!-- ============================================================
     PAGE HEADER
     ============================================================ -->
<header class="relative py-20 md:py-24 overflow-hidden bg-indigo-900 border-b border-indigo-900/50 mb-12">
  <img
    src="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&q=80&w=2000"
    alt="Layanan Background"
    class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay"
  />
  <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/90 via-indigo-800/80 to-violet-900/70"></div>

  <div class="relative page-shell text-center z-10" data-animate>
    <span class="inline-block px-4 py-1.5 bg-white/20 text-white rounded-full text-xs font-bold tracking-wider uppercase mb-4 backdrop-blur-sm">Janji Klinik</span>
    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">Layanan Kami</h1>
    <p class="text-lg text-indigo-100 font-medium max-w-2xl mx-auto">
      Buat janji periksa mata bersama ahli kami
    </p>
  </div>
</header>

<div class="page-shell pb-20">

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 lg:gap-16">

  <!-- Form Pemesanan -->
  <div class="bg-white rounded-[32px] p-6 md:p-10 shadow-xl shadow-indigo-100/50 border border-zinc-100" data-animate="slide-left">
    <form action="{{ route('services.booking.store', [], false) }}" method="POST" id="booking-form" class="space-y-8">
      @csrf

      <!-- Layanan -->
      <div>
        <h2 class="text-xl font-bold text-neutral-900 mb-4 flex items-center gap-2">
          <i class="fa-solid fa-1 text-indigo-500 bg-indigo-50 w-7 h-7 flex items-center justify-center rounded-full text-sm"></i>
          Pilih Layanan
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <label class="cursor-pointer">
            <input type="radio" name="service" value="exam" checked class="peer sr-only">
            <div class="border-2 border-zinc-200 rounded-[20px] p-5 hover:border-indigo-500 hover:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all duration-200 group">
              <i class="fa-solid fa-user-doctor text-2xl text-gray-400 group-hover:text-indigo-500 peer-checked:text-indigo-600 mb-3 transition"></i>
              <span class="block font-bold text-neutral-900 leading-tight">Periksa Mata</span>
              <span class="block text-xs text-gray-500 mt-1">Estimasi 30 Menit</span>
            </div>
          </label>

          <label class="cursor-pointer">
            <input type="radio" name="service" value="consultation" class="peer sr-only">
            <div class="border-2 border-zinc-200 rounded-[20px] p-5 hover:border-indigo-500 hover:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all duration-200 group">
              <i class="fa-solid fa-glasses text-2xl text-gray-400 group-hover:text-indigo-500 peer-checked:text-indigo-600 mb-3 transition"></i>
              <span class="block font-bold text-neutral-900 leading-tight">Konsultasi Frame</span>
              <span class="block text-xs text-gray-500 mt-1">Estimasi 20 Menit</span>
            </div>
          </label>
        </div>
      </div>

      <!-- Tanggal & Waktu -->
      <div>
         <h2 class="text-xl font-bold text-neutral-900 mb-4 flex items-center gap-2">
          <i class="fa-solid fa-2 text-indigo-500 bg-indigo-50 w-7 h-7 flex items-center justify-center rounded-full text-sm"></i>
          Pilih Jadwal
        </h2>
        <input type="hidden" name="booking_date" id="booking-date" value="">
        <input type="hidden" name="booking_time" id="booking-time" value="14:00">
        <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_220px] gap-6 items-start">

          <!-- Kalender -->
          <div class="booking-calendar border border-zinc-200 rounded-[20px] p-4 bg-white shadow-sm">
            <div class="flex justify-between items-center mb-4">
              <button type="button" data-cal-prev class="calendar-nav text-gray-400 hover:text-indigo-600 transition" aria-label="Bulan sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
              <span class="font-bold text-neutral-900" data-cal-title>{{ now()->translatedFormat('F Y') }}</span>
              <button type="button" data-cal-next class="calendar-nav text-gray-400 hover:text-indigo-600 transition" aria-label="Bulan berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-gray-400 mb-2">
              <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
            </div>
            <div class="booking-calendar-grid grid grid-cols-7 gap-1 text-center" data-cal-grid></div>
            <p class="mt-3 text-xs font-medium text-red-500 hidden" data-booking-error></p>
          </div>

          <!-- Pilihan Waktu -->
          <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-2 gap-2 text-center text-sm">
            <button type="button" data-time-btn class="py-3.5 rounded-2xl border-2 border-zinc-200 text-gray-500 font-medium hover:border-indigo-500 hover:text-indigo-600 transition-all duration-200">10:00</button>
            <button type="button" data-time-btn class="py-3.5 rounded-2xl border-2 border-zinc-200 text-gray-500 font-medium hover:border-indigo-500 hover:text-indigo-600 transition-all duration-200">11:00</button>
            <button type="button" data-time-btn class="py-3.5 rounded-2xl border-2 border-zinc-200 text-gray-500 font-medium hover:border-indigo-500 hover:text-indigo-600 transition-all duration-200">13:00</button>

            <button type="button" data-time-btn class="selected py-3.5 rounded-2xl border-2 border-indigo-500 bg-indigo-50 text-indigo-600 font-bold transition-all duration-200">14:00</button>

            <button type="button" data-time-btn class="py-3.5 rounded-2xl border-2 border-zinc-200 text-gray-500 font-medium hover:border-indigo-500 hover:text-indigo-600 transition-all duration-200">15:00</button>
            <button type="button" data-time-btn class="py-3.5 rounded-2xl border-2 border-zinc-200 text-gray-500 font-medium hover:border-indigo-500 hover:text-indigo-600 transition-all duration-200">16:00</button>
          </div>

        </div>
      </div>

      <!-- Detail Kontak -->
      <div>
         <h2 class="text-xl font-bold text-neutral-900 mb-4 flex items-center gap-2">
          <i class="fa-solid fa-3 text-indigo-500 bg-indigo-50 w-7 h-7 flex items-center justify-center rounded-full text-sm"></i>
          Data Diri
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="booking-name" class="block text-sm font-semibold text-neutral-900 mb-2">Nama Lengkap</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-solid fa-user"></i></span>
              <input
                type="text"
                id="booking-name"
                name="name"
                placeholder="Masukkan nama Anda"
                class="w-full bg-neutral-50 border border-transparent rounded-2xl py-3.5 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
              />
            </div>
          </div>
          <div>
            <label for="booking-phone" class="block text-sm font-semibold text-neutral-900 mb-2">Nomor Telepon/WA</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-solid fa-phone"></i></span>
              <input
                type="tel"
                id="booking-phone"
                name="phone"
                placeholder="Contoh: 081234567890"
                class="w-full bg-neutral-50 border border-transparent rounded-2xl py-3.5 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Tombol Konfirmasi -->
      <button
        type="button"
        id="wa-booking-btn"
        class="w-full bg-[#25D366] hover:bg-[#20bd5a] text-white py-4 rounded-2xl font-bold flex items-center justify-center gap-3 transition-all duration-200 shadow-lg shadow-green-200 hover:-translate-y-0.5 text-lg"
      >
        <i class="fa-brands fa-whatsapp text-2xl leading-none"></i>
        Konfirmasi via WhatsApp
      </button>

    </form>
  </div>

  <!-- Info Card -->
  <div class="flex flex-col gap-6" data-animate="slide-right">
    <!-- Promo banner -->
     <div class="bg-gradient-to-r from-indigo-50 to-violet-50 border border-indigo-100 rounded-3xl p-6 flex items-start gap-4 shadow-sm relative overflow-hidden">
       <i class="fa-solid fa-gift absolute -right-4 -bottom-4 text-6xl text-indigo-100 opacity-50 rotate-[-15deg]"></i>
      <div class="text-3xl text-indigo-500 mt-1"><i class="fa-solid fa-tags"></i></div>
      <div class="relative z-10">
        <h3 class="text-xl font-extrabold text-indigo-700 mb-1">Periksa Mata Gratis</h3>
        <p class="text-sm font-medium text-indigo-900/70">Dengan pembelian kacamata atau lensa jenis apapun pada hari yang sama.</p>
      </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100 flex-1">
       <h3 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
         <i class="fa-solid fa-circle-info text-gray-400"></i> Informasi Klinik
      </h3>
      <ul class="space-y-5">
        <li class="flex items-start gap-4">
           <div class="w-10 h-10 rounded-[12px] bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <div>
            <p class="font-bold text-neutral-900 leading-tight">Alamat Toko</p>
            <p class="text-gray-500 text-sm mt-1">{!! nl2br(e($settings['address'] ?? 'Jl. Optik Utama No. 123<br>Jakarta Pusat, 10110')) !!}</p>
          </div>
        </li>
        <li class="flex items-start gap-4">
           <div class="w-10 h-10 rounded-[12px] bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0">
            <i class="fa-regular fa-clock"></i>
          </div>
          <div>
            <p class="font-bold text-neutral-900 leading-tight">Jam Operasional</p>
            <p class="text-gray-500 text-sm mt-1">Senin - Minggu<br>10:00 - 21:00 WIB</p>
          </div>
        </li>
        <li class="flex items-start gap-4">
           <div class="w-10 h-10 rounded-[12px] bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0">
            <i class="fa-solid fa-phone"></i>
          </div>
          <div>
            <p class="font-bold text-neutral-900 leading-tight">Telepon</p>
            <p class="text-gray-500 text-sm mt-1"><a href="tel:{{ $settings['whatsapp_number'] ?? '+6281234567890' }}" class="hover:text-indigo-600 hover:underline">{{ $settings['whatsapp_number'] ?? '+62 812-3456-7890' }}</a></p>
          </div>
        </li>
      </ul>
      
      <hr class="my-6 border-zinc-100">

       <p class="text-sm text-gray-400 flex items-start gap-2 bg-neutral-50 p-4 rounded-xl">
         <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-1 shrink-0"></i>
         Penting: Mohon tiba 10 menit lebih awal dari jadwal yang telah ditentukan. Jika membatalkan, harap konfirmasi minimal H-1.
       </p>
    </div>
  </div>
</div>
</div>
@endsection
