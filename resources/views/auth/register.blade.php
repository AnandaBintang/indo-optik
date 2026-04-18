@extends('layouts.guest')

@section('title', 'Buat Akun — IndoOptik')
@section('description', 'Daftar akun IndoOptik dan nikmati pengalaman belanja kacamata yang mudah, serta dapatkan promo eksklusif pengguna baru.')

@section('content')
<div class="bg-gradient-to-br from-indigo-50 via-violet-50 to-white py-12 px-4 sm:px-6 lg:px-8 flex flex-col justify-center items-center relative min-h-[calc(100vh-80px-300px)] lg:min-h-[calc(100vh-80px-300px)] h-full overflow-hidden w-full">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48Y2lyY2xlIGN4PSIyIiBjeT0iMiIgcj0iMiIgZmlsbD0iIzYzNjZmMSIgZmlsbC1vcGFjaXR5PSIwLjA1Ii8+PC9zdmc+')] opacity-60"></div>
  
  <div class="w-full max-w-lg my-4 relative z-10 mx-auto" data-animate="scale-up">

    <!-- Card -->
    <div class="bg-white rounded-[32px] shadow-2xl shadow-indigo-100/60 border border-zinc-100 p-8 md:p-10">
      <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="navbar-logo text-2xl mb-6 inline-block font-extrabold tracking-tight">IndoOptik</a>
        <h1 class="text-3xl font-extrabold text-neutral-900 mb-2 tracking-tight">Buat Akun</h1>
        <p class="text-gray-500 font-medium tracking-wide">Bergabung dan dapatkan keuntungan eksklusif</p>
      </div>

      <!-- Promo banner -->
      <div class="bg-gradient-to-r from-indigo-50 to-violet-50 border border-indigo-100 rounded-2xl p-4 mb-8 flex items-center gap-4 relative overflow-hidden group">
        <i class="fa-solid fa-gift text-6xl text-indigo-100/70 absolute -right-2 -bottom-3 rotate-[-15deg] group-hover:scale-110 transition-transform duration-500"></i>
        <div class="w-12 h-12 bg-white rounded-xl shadow-sm text-indigo-500 flex items-center justify-center text-xl shrink-0 z-10">
            <i class="fa-solid fa-tags"></i>
        </div>
        <div class="relative z-10">
          <p class="text-[0.95rem] font-extrabold text-indigo-700 leading-tight">Diskon 30% untuk pengguna baru!</p>
          <p class="text-xs font-semibold text-indigo-500 mt-0.5">Daftar sekarang dan gunakan kode <span class="bg-white px-1.5 py-0.5 rounded text-indigo-600 border border-indigo-100">BARU30</span></p>
        </div>
      </div>
      
      @if ($errors->any())
        <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 px-4 py-3 rounded-xl border border-red-200">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif

      <form action="{{ route('register') }}" method="POST" class="space-y-4" id="register-form">
        @csrf
        
        <!-- Nama -->
        <div>
          <label for="name" class="block text-sm font-bold text-neutral-900 mb-2">Nama Lengkap</label>
          <div class="relative flex items-center group">
            <div class="absolute left-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors">
              <i class="fa-solid fa-user"></i>
            </div>
            <input
              type="text"
              id="name"
              name="name"
              value="{{ old('name') }}"
              required autofocus autocomplete="name"
              placeholder="Masukkan nama lengkap"
              class="w-full bg-neutral-50 text-neutral-900 rounded-2xl py-3.5 pl-11 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-transparent transition-all duration-200 placeholder:text-gray-400"
            />
          </div>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-bold text-neutral-900 mb-2">Email</label>
           <div class="relative flex items-center group">
            <div class="absolute left-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors">
              <i class="fa-solid fa-envelope"></i>
            </div>
            <input
              type="email"
              id="email"
              name="email"
              value="{{ old('email') }}"
              required autocomplete="username"
              placeholder="Masukkan email"
              class="w-full bg-neutral-50 text-neutral-900 rounded-2xl py-3.5 pl-11 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-transparent transition-all duration-200 placeholder:text-gray-400"
            />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Password -->
            <div x-data="{ show: false }">
              <label for="password" class="block text-sm font-bold text-neutral-900 mb-2">Password</label>
              <div class="relative flex items-center group">
                <div class="absolute left-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                  <i class="fa-solid fa-lock"></i>
                </div>
                <input
                  :type="show ? 'text' : 'password'"
                  id="password"
                  name="password"
                  required autocomplete="new-password"
                  placeholder="Min. 8 karakter"
                  class="w-full bg-neutral-50 text-neutral-900 rounded-2xl py-3.5 pl-11 pr-10 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-transparent transition-all duration-200 placeholder:text-gray-400"
                />
                 <button type="button" @click="show = !show" class="absolute right-3.5 text-gray-400 hover:text-indigo-600 transition outline-none" aria-label="Tampilkan password">
                  <i class="fa-solid" :class="show ? 'fa-eye' : 'fa-eye-slash'" class="text-xs"></i>
                </button>
              </div>
            </div>

            <!-- Konfirmasi Password -->
            <div x-data="{ show: false }">
              <label for="password_confirmation" class="block text-sm font-bold text-neutral-900 mb-2">Konfirmasi Password</label>
              <div class="relative flex items-center group">
                <div class="absolute left-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                   <i class="fa-solid fa-check-double"></i>
                </div>
                <input
                  :type="show ? 'text' : 'password'"
                  id="password_confirmation"
                  name="password_confirmation"
                  required autocomplete="new-password"
                  placeholder="Ulangi password"
                   class="w-full bg-neutral-50 text-neutral-900 rounded-2xl py-3.5 pl-11 pr-10 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-transparent transition-all duration-200 placeholder:text-gray-400"
                />
                <button type="button" @click="show = !show" class="absolute right-3.5 text-gray-400 hover:text-indigo-600 transition outline-none" aria-label="Tampilkan password">
                  <i class="fa-solid" :class="show ? 'fa-eye' : 'fa-eye-slash'" class="text-xs"></i>
                </button>
              </div>
            </div>
        </div>

        <!-- Terms -->
        <div class="flex items-start gap-3 pt-2 pb-1">
          <input
            type="checkbox"
            id="terms"
            name="terms"
            required
            class="mt-1 w-4 h-4 text-indigo-600 bg-neutral-100 border-zinc-300 rounded focus:ring-indigo-500 cursor-pointer"
          />
          <label for="terms" class="text-sm text-gray-500 cursor-pointer leading-relaxed font-medium">
            Saya setuju dengan
            <a href="#" class="text-indigo-600 font-bold hover:text-indigo-800 hover:underline">Syarat dan Ketentuan</a>
            serta
            <a href="#" class="text-indigo-600 font-bold hover:text-indigo-800 hover:underline">Kebijakan Privasi</a>
            IndoOptik
          </label>
        </div>

        <button
          type="submit"
           class="w-full btn btn-primary py-3.5 justify-center mt-2 group"
        >
          Buat Akun Sekarang
           <i class="fa-solid fa-user-plus ml-1 group-hover:scale-110 transition-transform"></i>
        </button>
      </form>

      <!-- Divider -->
      <div class="flex items-center my-6">
        <div class="flex-grow border-t border-zinc-200"></div>
        <span class="px-4 text-xs text-gray-400 font-bold tracking-widest uppercase">Atau</span>
        <div class="flex-grow border-t border-zinc-200"></div>
      </div>

      <!-- Google -->
      <button
        type="button"
        class="w-full bg-white border-2 border-zinc-200 text-neutral-700 font-bold rounded-2xl py-3 flex items-center justify-center gap-3 hover:bg-neutral-50 hover:border-zinc-300 hover:text-neutral-900 transition-all duration-200 text-sm"
      >
        <i class="fa-brands fa-google text-red-500 text-lg"></i>
        Daftar dengan Google
      </button>

      <!-- Login link -->
      <p class="text-center text-sm text-gray-500 mt-8 font-medium">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:text-indigo-800 hover:underline transition ml-1">Masuk Sekarang</a>
      </p>
    </div>
  </div>
</div>
@endsection
