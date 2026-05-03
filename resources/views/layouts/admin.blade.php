<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Dashboard') — IndoOptik Admin</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>

<body class="bg-neutral-100 text-neutral-900 antialiased">

  <div class="flex min-h-screen">

    <!-- =============================================
       SIDEBAR
       ============================================= -->
    <aside
      class="admin-sidebar fixed inset-y-0 left-0 z-40 w-64 flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300"
      style="background-color:#0f172a;">

      <!-- Logo -->
      <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
        <a href="{{ route('admin.dashboard') }}" class="navbar-logo text-xl text-white font-extrabold tracking-tight">
          IndoOptik
        </a>
        <span
          class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 uppercase tracking-wider">Admin</span>
      </div>

      <!-- Navigation -->
      <nav class="admin-nav flex-1 overflow-y-auto px-3 py-4 space-y-1">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-white/10' }}">
          <i class="fa-solid fa-gauge-high w-4 text-center"></i>
          Dashboard
        </a>

        <!-- Produk Section -->
        <div class="pt-4 pb-1">
          <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Produk</p>
        </div>

        <a href="{{ route('admin.products.index') }}"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-white/10' }}">
          <i class="fa-solid fa-glasses w-4 text-center"></i>
          Produk
        </a>

        <a href="{{ route('admin.categories.index') }}"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-white/10' }}">
          <i class="fa-solid fa-layer-group w-4 text-center"></i>
          Kategori
        </a>

        <!-- Konten Section -->
        <div class="pt-4 pb-1">
          <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Konten</p>
        </div>

        <a href="{{ route('admin.testimonials.index') }}"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                {{ request()->routeIs('admin.testimonials.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-white/10' }}">
          <i class="fa-solid fa-star w-4 text-center"></i>
          Testimoni
        </a>

        <a href="{{ route('admin.teams.index') }}"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                {{ request()->routeIs('admin.teams.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-white/10' }}">
          <i class="fa-solid fa-people-group w-4 text-center"></i>
          Tim
        </a>

        <a href="{{ route('admin.promo-codes.index') }}"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                {{ request()->routeIs('admin.promo-codes.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-white/10' }}">
          <i class="fa-solid fa-tag w-4 text-center"></i>
          Kode Promo
        </a>

        <!-- Pengaturan Section -->
        <div class="pt-4 pb-1">
          <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sistem</p>
        </div>

        <a href="{{ route('admin.users.index') }}"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-white/10' }}">
          <i class="fa-solid fa-users w-4 text-center"></i>
          Pengguna
        </a>

        <a href="{{ route('admin.settings.index') }}"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-white/10' }}">
          <i class="fa-solid fa-sliders w-4 text-center"></i>
          Pengaturan
        </a>

      </nav>

      <!-- Sidebar Footer -->
      <div class="px-4 py-4 border-t border-white/10">
        <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
          class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-slate-400 hover:text-white hover:bg-white/10 transition-all duration-150 font-medium">
          <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center text-xs"></i>
          Lihat Toko
        </a>
      </div>

    </aside>

    <!-- =============================================
       MAIN AREA (offset for sidebar)
       ============================================= -->
    <div class="flex-1 flex flex-col min-w-0 lg:ml-64 transition-all duration-300">

      <!-- =============================================
         TOP BAR
         ============================================= -->
      <header class="admin-topbar sticky top-0 z-30 bg-white border-b border-zinc-200 shadow-sm w-full">
        <div class="flex items-center justify-between px-4 sm:px-6 py-3.5 gap-4 w-full">

          <!-- Page Title -->
          <div class="flex items-center gap-3 shrink-0">
            <!-- Mobile sidebar toggle -->
            <button type="button" id="sidebar-toggle"
              class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-neutral-100 hover:text-neutral-900 transition"
              aria-label="Toggle sidebar">
              <i class="fa-solid fa-bars text-base"></i>
            </button>
            <div class="min-w-0">
              <h1 class="text-lg sm:text-xl font-extrabold text-neutral-900 leading-tight truncate">
                @yield('title', 'Dashboard')</h1>
              @hasSection('breadcrumb')
                <nav
                  class="hidden sm:flex items-center gap-1.5 text-xs text-gray-400 mt-0.5 font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                  @yield('breadcrumb')
                </nav>
              @endif
            </div>
          </div>

          <!-- Right Controls -->
          <div class="flex items-center gap-3">

            <!-- Notifications (placeholder) -->
            <button type="button"
              class="relative p-2 rounded-xl text-gray-500 hover:bg-neutral-100 hover:text-neutral-900 transition"
              aria-label="Notifikasi">
              <i class="fa-solid fa-bell text-base"></i>
            </button>

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
              <button type="button" @click="open = !open"
                class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-neutral-100 transition-all duration-150 group"
                aria-haspopup="true" :aria-expanded="open">
                <div
                  class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                  {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="hidden sm:block text-left">
                  <p class="text-sm font-bold text-neutral-900 leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                  <p class="text-[11px] text-gray-400 font-medium capitalize leading-tight">
                    {{ auth()->user()->role ?? 'admin' }}
                  </p>
                </div>
                <i
                  class="fa-solid fa-chevron-down text-xs text-gray-400 group-hover:text-neutral-600 transition ml-1 hidden sm:block"></i>
              </button>

              <!-- Dropdown Menu -->
              <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.outside="open = false"
                class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl shadow-neutral-200/80 border border-zinc-100 py-2 z-50"
                style="display:none;">

                <div class="px-4 py-2.5 border-b border-zinc-100 mb-1">
                  <p class="text-sm font-bold text-neutral-900 truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                  <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>

                <a href="{{ route('admin.settings.index') }}"
                  class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors font-medium">
                  <i class="fa-solid fa-sliders w-4 text-center text-gray-400"></i>
                  Pengaturan
                </a>

                <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
                  class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors font-medium">
                  <i class="fa-solid fa-store w-4 text-center text-gray-400"></i>
                  Lihat Toko
                </a>

                <div class="border-t border-zinc-100 mt-1 pt-1">
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                      class="flex w-full items-center gap-3 px-4 py-2 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 transition-colors font-semibold">
                      <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                      Keluar
                    </button>
                  </form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </header>

      <!-- =============================================
         PAGE CONTENT
         ============================================= -->
      <main class="admin-content flex-1 p-4 sm:p-6 lg:p-8">

        {{-- Flash Messages --}}
        @if(session('success'))
          <div
            class="mb-6 flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-4">
            <i class="fa-solid fa-circle-check text-green-500 mt-0.5 text-lg shrink-0"></i>
            <div>
              <p class="font-bold text-sm">Berhasil!</p>
              <p class="text-sm font-medium mt-0.5">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="this.closest('div').remove()"
              class="ml-auto text-green-500 hover:text-green-700 transition shrink-0">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
        @endif

        @if(session('error'))
          <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4">
            <i class="fa-solid fa-circle-xmark text-red-500 mt-0.5 text-lg shrink-0"></i>
            <div>
              <p class="font-bold text-sm">Gagal!</p>
              <p class="text-sm font-medium mt-0.5">{{ session('error') }}</p>
            </div>
            <button type="button" onclick="this.closest('div').remove()"
              class="ml-auto text-red-500 hover:text-red-700 transition shrink-0">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
        @endif

        @if(session('warning'))
          <div
            class="mb-6 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl px-5 py-4">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 text-lg shrink-0"></i>
            <div>
              <p class="font-bold text-sm">Peringatan</p>
              <p class="text-sm font-medium mt-0.5">{{ session('warning') }}</p>
            </div>
            <button type="button" onclick="this.closest('div').remove()"
              class="ml-auto text-amber-500 hover:text-amber-700 transition shrink-0">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
        @endif

        @yield('content')

      </main>

    </div>
  </div>

  <!-- Mobile Sidebar Overlay -->
  <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 lg:hidden hidden"
    aria-hidden="true">
  </div>

  @stack('scripts')

</body>

</html>
