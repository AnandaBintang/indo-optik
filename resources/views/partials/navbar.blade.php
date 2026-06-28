{{-- resources/views/partials/navbar.blade.php --}}
<header id="main-navbar"
        class="sticky top-0 z-50"
        data-wa-number="{{ $waNumber ?? $settings['whatsapp_number'] ?? '6281234567890' }}">

  <div class="navbar-inner">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="navbar-logo" aria-label="IndoOptik Beranda">IndoOptik</a>

    {{-- Desktop Navigation --}}
    <nav aria-label="Navigasi utama">
      <ul class="navbar-links">
        <li>
          <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            Beranda
          </a>
        </li>
        <li>
          <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-info"></i>
            Tentang Kami
          </a>
        </li>
        <li>
          <a href="{{ route('catalog.index') }}" class="{{ request()->routeIs('catalog.*', 'products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-border-all"></i>
            Katalog
          </a>
        </li>
        <li>
          <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i>
            Layanan
          </a>
        </li>
        <li>
          <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">
            <i class="fa-solid fa-newspaper"></i>
            Blog
          </a>
        </li>
      </ul>
    </nav>

    {{-- Right Controls --}}
    <div class="navbar-right">

      {{-- Cart Icon --}}
      <a href="{{ route('cart.index') }}" class="navbar-icon-btn" aria-label="Keranjang belanja">
        <i class="fa-solid fa-cart-shopping"></i>
        @if(session('cart_count', 0) > 0)
          <span class="navbar-cart-badge" id="cart-badge">{{ session('cart_count', 0) }}</span>
        @else
          <span class="navbar-cart-badge" id="cart-badge" style="display:none;">0</span>
        @endif
      </a>

      {{-- Auth Controls (Desktop) --}}
      <div class="navbar-auth-desktop">
        @guest
          <a href="{{ route('login') }}" class="btn-outline-primary btn-sm">
            <i class="fa-solid fa-right-to-bracket"></i>
            Masuk
          </a>
          <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-user-plus"></i>
            Daftar
          </a>
        @endguest

        @auth
          {{-- User Dropdown --}}
          <div class="relative" id="user-dropdown-wrapper">
            <button type="button"
                    id="user-dropdown-btn"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl border border-zinc-200 bg-white hover:bg-neutral-50 hover:border-indigo-300 transition-all duration-150 text-sm font-semibold text-neutral-800 shadow-sm"
                    aria-haspopup="true"
                    aria-expanded="false">
              <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                {{ substr(auth()->user()->name, 0, 1) }}
              </div>
              <span class="hidden sm:inline max-w-[100px] truncate">{{ auth()->user()->name }}</span>
              <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-0.5"></i>
            </button>

            {{-- Dropdown --}}
              <div id="user-dropdown-menu"
                class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl shadow-neutral-200/80 border border-zinc-100 py-2 z-50 overflow-hidden"
                 style="display:none;">

              <div class="px-4 py-2.5 border-b border-zinc-100 mb-1">
                <p class="text-sm font-bold text-neutral-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
              </div>

              @if(in_array(auth()->user()->role ?? '', ['admin', 'staff']))
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-50 transition-colors">
                  <i class="fa-solid fa-gauge-high w-4 text-center"></i>
                  Panel Admin
                </a>
                <div class="border-t border-zinc-100 my-1"></div>
              @endif

              <a href="{{ route('cart.index') }}"
                 class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors font-medium">
                <i class="fa-solid fa-cart-shopping w-4 text-center text-gray-400"></i>
                Keranjang
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
        @endauth
      </div>

      {{-- Hamburger (Mobile) --}}
      <button type="button"
              class="hamburger-btn"
              id="hamburger-btn"
              aria-label="Buka menu navigasi"
              aria-expanded="false"
              aria-controls="mobile-drawer">
        <i class="fa-solid fa-bars" id="hamburger-icon"></i>
      </button>

    </div>
  </div>
</header>

{{-- Mobile Drawer --}}
<nav class="mobile-drawer" id="mobile-drawer" role="dialog" aria-modal="true" aria-label="Menu navigasi mobile">
  <div class="mobile-drawer-overlay backdrop-blur-sm" id="drawer-overlay"></div>
  <div class="mobile-drawer-panel" role="document">

    {{-- Drawer Header --}}
    <div class="mobile-drawer-header">
      <a href="{{ route('home') }}" class="navbar-logo" style="font-size:1.25rem;">IndoOptik</a>
      <button type="button" class="mobile-drawer-close" id="drawer-close" aria-label="Tutup menu">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    {{-- Drawer Nav --}}
    <div class="mobile-drawer-nav" aria-label="Navigasi mobile">
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>
        <span>Beranda</span>
      </a>
      <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
        <i class="fa-solid fa-circle-info"></i>
        <span>Tentang Kami</span>
      </a>
      <a href="{{ route('catalog.index') }}" class="{{ request()->routeIs('catalog.*', 'products.*') ? 'active' : '' }}">
        <i class="fa-solid fa-border-all"></i>
        <span>Katalog</span>
      </a>
      <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
        <i class="fa-solid fa-clipboard-list"></i>
        <span>Layanan</span>
      </a>
      <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">
        <i class="fa-solid fa-newspaper"></i>
        <span>Blog</span>
      </a>
      <a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.*') ? 'active' : '' }}">
        <i class="fa-solid fa-cart-shopping"></i>
        <span>
          Keranjang
          @if(session('cart_count', 0) > 0)
            <span class="inline-flex items-center justify-center ml-2 w-5 h-5 bg-indigo-500 text-white text-[10px] font-bold rounded-full">{{ session('cart_count', 0) }}</span>
          @endif
        </span>
      </a>

      @auth
        @if(in_array(auth()->user()->role ?? '', ['admin', 'staff']))
          <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 font-bold">
            <i class="fa-solid fa-gauge-high"></i>
            <span>Panel Admin</span>
          </a>
        @endif
      @endauth
    </div>

    {{-- Drawer Footer --}}
    <div class="mobile-drawer-footer">
      @guest
        <a href="{{ route('login') }}" class="btn btn-ghost btn-block" style="justify-content:center;">
          <i class="fa-solid fa-right-to-bracket"></i>
          Masuk
        </a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-block" style="justify-content:center;">
          <i class="fa-solid fa-user-plus"></i>
          Daftar Sekarang
        </a>
      @endguest

      @auth
        <div class="flex items-center gap-3 px-2 py-2 mb-2">
          <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
            {{ substr(auth()->user()->name, 0, 1) }}
          </div>
          <div>
            <p class="text-sm font-bold text-neutral-900 leading-tight">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-ghost btn-block" style="justify-content:center;color:#ef4444;">
            <i class="fa-solid fa-right-from-bracket"></i>
            Keluar
          </button>
        </form>
      @endauth
    </div>

  </div>
</nav>
