@extends('layouts.admin')

@section('title', 'Edit Promo Code')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <a href="{{ route('admin.promo-codes.index') }}" class="hover:text-indigo-600 transition-colors">Promo</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">{{ $promoCode->code }}</span>
@endsection

@section('content')
<div class="max-w-3xl">
  <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 md:p-8">
    <form action="{{ route('admin.promo-codes.update', $promoCode->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="space-y-6">
        <h2 class="text-lg font-bold text-neutral-900 mb-4 border-b border-zinc-100 pb-2 flex items-center justify-between">
           <span>Detail Promo</span>
           <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded font-bold">Terpakai: {{ $promoCode->usage_count }}x</span>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
           <!-- Kode Promo -->
           <div>
             <label for="code" class="block text-sm font-bold text-neutral-900 mb-2">Kode Promo <span class="text-red-500">*</span></label>
             <input type="text" id="code" name="code" value="{{ old('code', $promoCode->code) }}" required
               class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-bold uppercase tracking-widest focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
             @error('code') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
           </div>

           <!-- Label / Keterangan -->
           <div>
             <label for="label" class="block text-sm font-bold text-neutral-900 mb-2">Label Deskripsi</label>
             <input type="text" id="label" name="label" value="{{ old('label', $promoCode->label) }}"
               class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
             @error('label') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
           </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
           <!-- Tipe Promo -->
           <div x-data="{ type: '{{ old('type', $promoCode->type) }}' }">
             <label for="type" class="block text-sm font-bold text-neutral-900 mb-2">Tipe Diskon <span class="text-red-500">*</span></label>
             <select id="type" name="type" required x-model="type" class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
               <option value="percentage">Persentase (%)</option>
               <option value="fixed">Nominal Tetap (Rp)</option>
             </select>
             @error('type') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror

             <!-- Nilai -->
             <div class="mt-5">
               <label for="value" class="block text-sm font-bold text-neutral-900 mb-2">Nilai Diskon <span class="text-red-500">*</span></label>
               <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold" x-text="type == 'fixed' ? 'Rp' : '%'"></span>
                  <input type="number" id="value" name="value" value="{{ old('value', number_format($promoCode->value, 2, '.', '')) }}" required step="0.01" min="0"
                    class="w-full bg-neutral-50 rounded-xl py-3 pl-11 pr-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
               </div>
               <p class="text-[10px] text-gray-500 mt-1.5" x-show="type == 'percentage'">Gunakan nilai antara 1 sampai 100.</p>
               @error('value') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
             </div>
           </div>

           <div>
              <!-- Minimum Pembelian -->
              <div class="mb-5">
                <label for="min_purchase" class="block text-sm font-bold text-neutral-900 mb-2">Batas Min. Belanja (Rp)</label>
                <input type="number" id="min_purchase" name="min_purchase" value="{{ old('min_purchase', number_format($promoCode->min_purchase, 2, '.', '')) }}" min="0" placeholder="0"
                  class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
                <p class="text-[10px] text-gray-500 mt-1.5">Kosongkan jika tidak ada batas minimal.</p>
                @error('min_purchase') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
              </div>

              <!-- Maksimum Diskon -->
              <div>
                <label for="max_discount" class="block text-sm font-bold text-neutral-900 mb-2">Batas Maks. Diskon (Rp)</label>
                <input type="number" id="max_discount" name="max_discount" value="{{ old('max_discount', $promoCode->max_discount !== null ? number_format($promoCode->max_discount, 2, '.', '') : '') }}" min="0" placeholder="0"
                  class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
                <p class="text-[10px] text-gray-500 mt-1.5">Berguna untuk tipe Diskon Persentase agar jumlah diskon tidak terlalu besar.</p>
                @error('max_discount') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
              </div>
           </div>
        </div>

        <h2 class="text-lg font-bold text-neutral-900 mb-4 border-b border-zinc-100 pb-2 mt-8">Batasan Penggunaan</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
           <!-- Usage Limit -->
           <div>
             <label for="usage_limit" class="block text-sm font-bold text-neutral-900 mb-2">Kuota Penggunaan</label>
             <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $promoCode->usage_limit) }}" min="1" placeholder="Mis. 100"
               class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
             <p class="text-[10px] text-gray-500 mt-1.5">Jumlah maksimal kode ini bisa digunakan. Saat ini terpakai: {{ $promoCode->usage_count }}x</p>
             @error('usage_limit') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
           </div>

           <!-- Expired At -->
           <div>
             <label for="expired_at" class="block text-sm font-bold text-neutral-900 mb-2">Tanggal Berakhir</label>
             <input type="date" id="expired_at" name="expired_at" value="{{ old('expired_at', $promoCode->expired_at ? $promoCode->expired_at->format('Y-m-d') : null) }}"
               class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200 uppercase">
             @error('expired_at') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
           </div>
        </div>

        <!-- Status -->
        <div class="mt-4 pt-4 border-t border-zinc-100">
          <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border border-zinc-200 bg-neutral-50 w-full hover:border-indigo-500 transition max-w-sm">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promoCode->is_active) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 border-zinc-300 rounded focus:ring-indigo-500">
            <div>
              <span class="block font-bold text-sm text-neutral-900">Aktifkan Kode Promo Ini</span>
              <span class="block text-xs text-gray-500 mt-0.5">Kode promo dapat digunakan oleh pelanggan saat ini.</span>
            </div>
          </label>
        </div>

      </div>

      <div class="mt-8 pt-6 border-t border-zinc-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin.promo-codes.index') }}" class="btn bg-white border border-zinc-200 text-neutral-700 hover:bg-neutral-50 text-sm">
          Batal
        </a>
        <button type="submit" class="btn btn-primary text-sm shadow-md shadow-indigo-500/20">
          Simpan Perubahan
        </button>
      </div>

    </form>
  </div>
</div>
@endsection
