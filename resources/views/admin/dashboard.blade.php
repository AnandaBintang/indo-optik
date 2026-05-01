@extends('layouts.admin')

@section('title', 'Ringkasan Toko')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Ringkasan Toko</span>
@endsection

@section('content')
<div class="space-y-6">

  <div class="bg-gradient-to-br from-slate-950 via-indigo-950 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white overflow-hidden relative">
    <div class="absolute -right-16 -top-20 w-64 h-64 rounded-full bg-white/10 blur-2xl"></div>
    <div class="absolute right-10 bottom-0 text-white/10 text-8xl pointer-events-none">
      <i class="fa-brands fa-whatsapp"></i>
    </div>
    <div class="relative max-w-3xl">
      <p class="inline-flex items-center gap-2 bg-white/10 border border-white/15 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider mb-4">
        <i class="fa-solid fa-store"></i>
        WhatsApp-only storefront
      </p>
      <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-3">Dashboard fokus untuk katalog dan konten.</h2>
      <p class="text-indigo-100 leading-relaxed max-w-2xl">
        Semua transaksi pelanggan diarahkan ke WhatsApp, jadi panel ini menampilkan kesehatan katalog, promo, testimoni, dan pengguna tanpa modul pesanan internal.
      </p>
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
        <i class="fa-solid fa-glasses"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-0.5 sm:mb-1 truncate">Produk Aktif</p>
        <h3 class="text-lg sm:text-2xl font-bold text-neutral-900 leading-none truncate">{{ number_format($activeProducts, 0, ',', '.') }}</h3>
        <p class="text-[11px] text-gray-400 mt-1">dari {{ number_format($totalProducts, 0, ',', '.') }} produk</p>
      </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
        <i class="fa-solid fa-layer-group"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-0.5 sm:mb-1 truncate">Kategori</p>
        <h3 class="text-lg sm:text-2xl font-bold text-neutral-900 leading-none truncate">{{ number_format($totalCategories, 0, ',', '.') }}</h3>
        <p class="text-[11px] text-gray-400 mt-1">struktur katalog</p>
      </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
        <i class="fa-solid fa-tag"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-0.5 sm:mb-1 truncate">Promo Aktif</p>
        <h3 class="text-lg sm:text-2xl font-bold text-neutral-900 leading-none truncate">{{ number_format($activePromos, 0, ',', '.') }}</h3>
        <p class="text-[11px] text-gray-400 mt-1">siap dipakai pelanggan</p>
      </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
        <i class="fa-solid fa-users"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-0.5 sm:mb-1 truncate">Pelanggan</p>
        <h3 class="text-lg sm:text-2xl font-bold text-neutral-900 leading-none truncate">{{ number_format($totalUsers, 0, ',', '.') }}</h3>
        <p class="text-[11px] text-gray-400 mt-1">akun terdaftar</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
      <div class="px-6 py-5 border-b border-zinc-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h2 class="text-lg font-bold text-neutral-900">Produk Terbaru</h2>
          <p class="text-sm text-gray-500 mt-1">Kelola produk yang tampil di katalog publik.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm self-start sm:self-auto">Kelola Produk</a>
      </div>

      <div class="divide-y divide-zinc-100">
        @forelse($latestProducts as $product)
          <div class="px-6 py-4 flex items-center gap-4 hover:bg-neutral-50/70 transition">
            <div class="w-14 h-14 rounded-xl bg-neutral-100 overflow-hidden flex items-center justify-center shrink-0 text-gray-300">
              @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
              @else
                <i class="fa-solid fa-glasses text-xl"></i>
              @endif
            </div>
            <div class="min-w-0 flex-1">
              <p class="font-bold text-neutral-900 truncate">{{ $product->name }}</p>
              <p class="text-xs text-gray-500 mt-1 truncate">
                {{ $product->category->name ?? 'Tanpa kategori' }} · Stok {{ number_format($product->stock, 0, ',', '.') }} · Rp {{ number_format($product->effective_price, 0, ',', '.') }}
              </p>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $product->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
              {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
            </span>
          </div>
        @empty
          <div class="px-6 py-10 text-center text-gray-500">
            Belum ada produk. Tambahkan produk agar katalog bisa dipakai pelanggan.
          </div>
        @endforelse
      </div>
    </div>

    <div class="space-y-6">
      <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-zinc-100">
          <h2 class="text-lg font-bold text-neutral-900">Konten Toko</h2>
          <p class="text-sm text-gray-500 mt-1">Ringkasan testimoni yang tampil di halaman utama.</p>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4">
          <div class="rounded-2xl bg-indigo-50 p-4 text-center">
            <p class="text-[10px] uppercase font-bold tracking-wider text-indigo-500 mb-1">Published</p>
            <p class="text-2xl font-black text-indigo-700">{{ number_format($publishedReviews, 0, ',', '.') }}</p>
          </div>
          <div class="rounded-2xl bg-neutral-50 p-4 text-center">
            <p class="text-[10px] uppercase font-bold tracking-wider text-gray-500 mb-1">Total</p>
            <p class="text-2xl font-black text-neutral-900">{{ number_format($totalTestimonials, 0, ',', '.') }}</p>
          </div>
        </div>
        <div class="px-6 pb-6">
          <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline btn-block">Kelola Testimoni</a>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between gap-3">
          <h2 class="text-lg font-bold text-neutral-900">Promo Terbaru</h2>
          <a href="{{ route('admin.promo-codes.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">Kelola</a>
        </div>
        <div class="divide-y divide-zinc-100">
          @forelse($latestPromos as $promo)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
              <div class="min-w-0">
                <p class="font-bold text-neutral-900 truncate">{{ $promo->label ?: $promo->code }}</p>
                <p class="text-xs text-gray-500 mt-1 truncate">{{ $promo->code }} · {{ $promo->type === 'percentage' ? number_format($promo->value, 2, '.', '') . '%' : 'Rp ' . number_format($promo->value, 2, '.', '') }}</p>
              </div>
              <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $promo->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </div>
          @empty
            <div class="px-6 py-8 text-center text-gray-500">
              Belum ada kode promo.
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
