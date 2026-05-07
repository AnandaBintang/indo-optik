@extends('layouts.guest')

@section('title', 'Masuk — IndoOptik')
@section('description', 'Masuk ke akun IndoOptik Anda untuk menikmati pengalaman belanja kacamata yang lebih personal dan mudah.')

@section('content')
<div class="bg-gradient-to-br from-indigo-50 via-violet-50 to-white py-16 px-4 sm:px-6 lg:px-8 flex flex-col justify-center items-center relative min-h-[calc(100vh-80px-300px)] lg:min-h-[calc(100vh-80px-300px)] h-full overflow-hidden w-full">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48Y2lyY2xlIGN4PSIyIiBjeT0iMiIgcj0iMiIgZmlsbD0iIzYzNjZmMSIgZmlsbC1vcGFjaXR5PSIwLjA1Ii8+PC9zdmc+')] opacity-60"></div>
  
  <div class="w-full max-w-md relative z-10 mx-auto my-auto" data-animate="scale-up">

    <!-- Card -->
    <div class="bg-white rounded-[32px] shadow-2xl shadow-indigo-100/60 border border-zinc-100 p-8 md:p-10">
      <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="navbar-logo text-2xl mb-6 inline-block font-extrabold tracking-tight">IndoOptik</a>
        <h1 class="text-3xl font-extrabold text-neutral-900 mb-2 tracking-tight">Selamat Datang</h1>
        <p class="text-gray-500 font-medium tracking-wide">Masuk ke akun IndoOptik Anda</p>
      </div>

      {{-- Session Status --}}
      @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 px-4 py-3 rounded-xl border border-green-200">
            {{ session('status') }}
        </div>
      @endif
      
      @if ($errors->any())
        <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 px-4 py-3 rounded-xl border border-red-200">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif

      <form action="{{ route('login') }}" method="POST" class="space-y-5" id="login-form">
        @csrf
        
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
              required
              autocomplete="email"
              autofocus
              placeholder="Masukkan email"
              class="w-full bg-neutral-50 text-neutral-900 rounded-2xl py-3.5 pl-11 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-transparent transition-all duration-200 placeholder:text-gray-400"
            />
          </div>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-bold text-neutral-900 mb-2">Password</label>
          <div class="relative flex items-center group" x-data="{ show: false }">
            <div class="absolute left-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors">
              <i class="fa-solid fa-lock"></i>
            </div>
            <input
              :type="show ? 'text' : 'password'"
              id="password"
              name="password"
              required
              autocomplete="current-password"
              placeholder="Masukkan password"
              class="w-full bg-neutral-50 text-neutral-900 rounded-2xl py-3.5 pl-11 pr-12 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-transparent transition-all duration-200 placeholder:text-gray-400"
            />
            <button type="button" @click="show = !show" class="absolute right-4 text-gray-400 hover:text-indigo-600 transition outline-none" aria-label="Tampilkan password">
              <i class="fa-solid fa-eye-slash" :class="{ 'fa-eye': show, 'fa-eye-slash': !show }"></i>
            </button>
          </div>
        </div>

        <div class="flex justify-between items-center text-sm font-bold">
             <label class="flex items-center gap-2 cursor-pointer group">
              <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 bg-neutral-100 border-zinc-300 rounded focus:ring-indigo-500 cursor-pointer">
              <span class="text-gray-500 group-hover:text-neutral-900 transition-colors">Ingat saya</span>
            </label>
          @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline transition">Lupa Password?</a>
          @endif
        </div>

        <button
          type="submit"
          class="w-full btn btn-primary py-3.5 justify-center mt-2 group"
        >
          Masuk
          <i class="fa-solid fa-arrow-right-to-bracket ml-1 group-hover:translate-x-1 transition-transform"></i>
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
        Lanjutkan dengan Google
      </button>

      <!-- Register link -->
      <p class="text-center text-sm text-gray-500 mt-8 font-medium">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:text-indigo-800 hover:underline transition ml-1">Daftar Sekarang</a>
      </p>
    </div>
  </div>
</div>
@endsection
