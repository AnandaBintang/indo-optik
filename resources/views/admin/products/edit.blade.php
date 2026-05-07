@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <a href="{{ route('admin.products.index') }}" class="hover:text-indigo-600 transition-colors">Produk</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold truncate max-w-[150px] sm:max-w-xs">{{ $product->name }}</span>
@endsection

@section('content')
<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Informasi Dasar -->
      <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 md:p-8">
        <h2 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
          <i class="fa-solid fa-circle-info text-gray-400"></i> Informasi Dasar
        </h2>

        <div class="space-y-5">
          <div>
            <label for="name" class="block text-sm font-bold text-neutral-900 mb-2">Nama Produk <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
              class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
            @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label for="category_id" class="block text-sm font-bold text-neutral-900 mb-2">Kategori <span class="text-red-500">*</span></label>
              <select id="category_id" name="category_id" required class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
              </select>
              @error('category_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="sku" class="block text-sm font-bold text-neutral-900 mb-2">SKU (Kode Barang)</label>
              <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200 uppercase">
              @error('sku') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
          </div>

          <div>
            <label for="description" class="block text-sm font-bold text-neutral-900 mb-2">Deskripsi Produk</label>
            <textarea id="description" name="description" rows="5"
              class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>

        @php
          $colorVariants = old('color_variants', $product->color_variants ?? []);
          $lensVariants = old('lens_variants', $product->lens_variants ?? []);
          if (!is_array($colorVariants) || count($colorVariants) === 0) {
            $colorVariants = [
              ['key' => '', 'label' => '', 'color' => '#111827', 'images' => ''],
            ];
          }
          if (!is_array($lensVariants) || count($lensVariants) === 0) {
            $lensVariants = [
              [
                'key' => 'standard',
                'label' => 'Standar',
                'desc' => 'Lensa bening standar',
                'price' => 0,
                'icon' => 'fa-solid fa-eye',
              ],
            ];
          }
          $lensIconOptions = [
            'fa-solid fa-eye' => 'Mata (Standar)',
            'fa-solid fa-display' => 'Layar (Blue Light)',
            'fa-solid fa-shield-halved' => 'Shield (Anti Radiasi)',
            'fa-solid fa-sun' => 'Matahari (Photochromic)',
            'fa-solid fa-glasses' => 'Kacamata (Premium)',
          ];
        @endphp

        <!-- Varian Produk -->
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 md:p-8">
          <h2 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-layer-group text-gray-400"></i> Varian Produk
          </h2>

          <div class="space-y-6">
            <div>
              <div class="flex items-center justify-between mb-3">
                <div>
                  <h3 class="text-sm font-bold text-neutral-900">Varian Warna</h3>
                  <p class="text-xs text-gray-500">Atur label, warna, dan gambar (opsional).</p>
                </div>
                <button type="button" id="add-color-variant" class="btn btn-ghost btn-sm">
                  <i class="fa-solid fa-plus"></i> Tambah Warna
                </button>
              </div>

              <div id="color-variant-list" class="space-y-3">
                @foreach($colorVariants as $index => $variant)
                  @php
                    $imagesValue = '';
                    if (isset($variant['images'])) {
                      $imagesValue = is_array($variant['images']) ? implode(', ', $variant['images']) : $variant['images'];
                    }
                  @endphp
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end" data-variant-row>
                    <div class="md:col-span-4">
                      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Label</label>
                      <input type="text" name="color_variants[{{ $index }}][label]" value="{{ $variant['label'] ?? '' }}"
                        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="Hitam">
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Warna</label>
                      <input type="color" name="color_variants[{{ $index }}][color]" value="{{ $variant['color'] ?? '#111827' }}"
                        class="w-full h-11 rounded-xl border border-zinc-200 bg-white">
                    </div>
                    <div class="md:col-span-3">
                      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Gambar (URL)</label>
                      <input type="text" name="color_variants[{{ $index }}][images]" value="{{ $imagesValue }}"
                        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="url1, url2 (pisahkan koma)">
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Upload Gambar</label>
                      <input type="file" name="color_variants[{{ $index }}][image_uploads][]" multiple accept="image/*"
                        class="w-full bg-neutral-50 rounded-xl py-2 px-3 text-[11px] font-medium border border-zinc-200">
                    </div>
                    <div class="md:col-span-1 flex justify-end">
                      <button type="button" class="btn btn-ghost btn-sm" data-variant-remove>
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div>
              <div class="flex items-center justify-between mb-3">
                <div>
                  <h3 class="text-sm font-bold text-neutral-900">Varian Lensa</h3>
                  <p class="text-xs text-gray-500">Atur label, deskripsi, harga tambahan, dan ikon.</p>
                </div>
                <button type="button" id="add-lens-variant" class="btn btn-ghost btn-sm">
                  <i class="fa-solid fa-plus"></i> Tambah Lensa
                </button>
              </div>

              <div id="lens-variant-list" class="space-y-3">
                @foreach($lensVariants as $index => $variant)
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end" data-variant-row>
                    <div class="md:col-span-4">
                      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Label</label>
                      <input type="text" name="lens_variants[{{ $index }}][label]" value="{{ $variant['label'] ?? '' }}"
                        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="Standar">
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Harga</label>
                      <input type="number" name="lens_variants[{{ $index }}][price]" value="{{ $variant['price'] ?? ($variant['priceAddon'] ?? 0) }}" min="0"
                        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="0">
                    </div>
                    <div class="md:col-span-3">
                      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi</label>
                      <input type="text" name="lens_variants[{{ $index }}][desc]" value="{{ $variant['desc'] ?? '' }}"
                        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="Lensa bening standar">
                    </div>
                    <div class="md:col-span-1">
                      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Ikon</label>
                      <select name="lens_variants[{{ $index }}][icon]"
                        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200">
                        @foreach($lensIconOptions as $iconClass => $iconLabel)
                          <option value="{{ $iconClass }}" {{ ($variant['icon'] ?? 'fa-solid fa-eye') === $iconClass ? 'selected' : '' }}>
                            {{ $iconLabel }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="md:col-span-1 flex justify-end">
                      <button type="button" class="btn btn-ghost btn-sm" data-variant-remove>
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>

      <!-- Harga & Stok -->
      <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 md:p-8">
        <h2 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
          <i class="fa-solid fa-tag text-gray-400"></i> Harga & Stok
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label for="price" class="block text-sm font-bold text-neutral-900 mb-2">Harga Normal (Rp) <span class="text-red-500">*</span></label>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0" step="1000"
              class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
            @error('price') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="discount_price" class="block text-sm font-bold text-neutral-900 mb-2">Harga Diskon (Rp) opsional</label>
            <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" min="0" step="1000"
              class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
            @error('discount_price') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
          </div>

          <div>
             <label for="stock" class="block text-sm font-bold text-neutral-900 mb-2">Stok <span class="text-red-500">*</span></label>
            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
              class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
            @error('stock') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan -->
    <div class="space-y-6">
      <!-- Media -->
      <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
        <h2 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
          <i class="fa-solid fa-image text-gray-400"></i> Media Produk
        </h2>

        <div class="space-y-5">
          <!-- Gambar Utama -->
          <div x-data="{ mode: 'file', previewUrl: '{{ $product->image_url }}', url: '' }">
            <label class="block text-sm font-bold text-neutral-900 mb-2">Gambar Utama</label>

            <div class="flex items-center space-x-2 mb-3">
               <button type="button" @click="mode = 'file'" :class="mode === 'file' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider rounded-lg transition">Upload File</button>
               <button type="button" @click="mode = 'url'; previewUrl = url" :class="mode === 'url' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider rounded-lg transition">Gunakan URL</button>
            </div>

            <!-- File Upload Mode -->
            <template x-if="mode === 'file'">
              <div class="mt-1 relative rounded-xl hover:border-indigo-400 hover:bg-indigo-50/50 transition-all cursor-pointer group border border-zinc-200 bg-gray-50 p-2">

                <input id="image" name="image" type="file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer"
                  @change="
                    if($event.target.files.length) {
                      url = '';
                      previewUrl = URL.createObjectURL($event.target.files[0]);
                    }
                  "
                >

                <!-- Placeholder if no image -->
                <div x-show="!previewUrl && !url" class="space-y-1 text-center relative z-10 pointer-events-none p-4">
                  <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2 group-hover:scale-110 transition-transform text-indigo-400"></i>
                  <div class="flex text-sm justify-center">
                     <span class="font-bold text-indigo-600">Pilih gambar baru</span>
                  </div>
                </div>

                <!-- Preview Image -->
                <div x-show="previewUrl && !url" class="relative w-full h-48 rounded-lg overflow-hidden flex items-center justify-center">
                  <img :src="previewUrl" class="object-contain w-full h-full absolute inset-0 z-0">
                  <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity z-10 flex items-center justify-center">
                       <span class="text-white font-bold text-sm bg-black/70 px-4 py-2 rounded-full backdrop-blur-sm"><i class="fa-solid fa-pen mr-2"></i>Ganti Gambar</span>
                  </div>
                </div>
              </div>
            </template>

            <!-- URL Mode -->
            <template x-if="mode === 'url'">
              <div class="mt-1">
                <input type="url" id="image_url" name="image_url" x-model="url" @input="previewUrl = url" placeholder="https://unsplash.com/photo-..."
                  class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none mb-3">

                <div x-show="previewUrl" class="relative w-full h-48 rounded-lg overflow-hidden flex items-center justify-center bg-gray-50 border border-zinc-200 p-2">
                  <img :src="previewUrl" class="object-contain w-full h-full rounded-md" @@error="previewUrl = ''">
                </div>
              </div>
            </template>

            <p class="text-[11px] text-gray-500 mt-1.5" x-show="mode === 'file'">Kosongkan jika tidak ingin mengubah gambar utama.</p>
            @error('image') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            @error('image_url') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
          </div>

          <!-- Gambar Tambahan -->
          <div class="border-t border-zinc-100 pt-5">
            <label class="block text-sm font-bold text-neutral-900 mb-2">Gambar Tambahan</label>

            @if($product->images->count() > 0)
              <div class="grid grid-cols-2 gap-3 mb-4">
                @foreach($product->images as $img)
                  <div class="relative rounded-lg overflow-hidden border border-zinc-200 group aspect-square">
                    <img src="{{ $img->image_url }}" class="w-full h-full object-cover">
                    <!-- Delete Image Overlay -->
                    <label class="absolute inset-0 bg-red-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                       <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="sr-only peer">
                       <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-red-500 shadow-lg peer-checked:bg-red-500 peer-checked:text-white transition-colors">
                          <i class="fa-solid fa-trash-can"></i>
                       </div>
                    </label>
                  </div>
                @endforeach
              </div>
              <p class="text-xs text-amber-600 mb-3 font-medium flex items-center gap-1.5">
                 <i class="fa-solid fa-circle-info"></i> Centang tempat sampah untuk menghapus gambar
              </p>
            @endif

            <input type="file" id="additional_images" name="additional_images[]" multiple accept="image/*" class="w-full bg-neutral-50 rounded-xl py-2 px-3 text-sm border border-zinc-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <p class="text-[11px] text-gray-500 mt-1.5">Pilih untuk menambah gambar baru.</p>
            @error('additional_images.*') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>

      <!-- Settings -->
      <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
        <h2 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
          <i class="fa-solid fa-gear text-gray-400"></i> Pengaturan
        </h2>

        <div class="space-y-5">
           <!-- Status Visibility -->
           <div>
            <label class="block text-sm font-bold text-neutral-900 mb-2">Status Visibilitas</label>
            <div class="flex gap-4">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="status" value="active" {{ old('status', $product->status) == 'active' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-zinc-300">
                <span class="text-sm font-medium text-neutral-700">Aktif</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="status" value="inactive" {{ old('status', $product->status) == 'inactive' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-zinc-300">
                <span class="text-sm font-medium text-gray-500">Draft</span>
              </label>
            </div>
            @error('status') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
          </div>

          <!-- Featured Switch -->
          <div class="border-t border-zinc-100 pt-5">
             <label class="flex items-center gap-3 cursor-pointer">
               <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-zinc-300 focus:ring-indigo-500">
               <div>
                  <span class="block text-sm font-bold text-neutral-900">Produk Unggulan</span>
                  <span class="block text-xs text-gray-500">Tampilkan produk ini di halaman depan</span>
               </div>
             </label>
          </div>
        </div>

      </div>

    </div>
  </div>

  <div class="mt-8 pt-6 border-t border-zinc-200 flex items-center justify-end gap-3">
    <a href="{{ route('admin.products.index') }}" class="btn bg-white border border-zinc-200 text-neutral-700 hover:bg-neutral-50 text-sm">
      Batal
    </a>
    <button type="submit" class="btn btn-primary text-sm shadow-md shadow-indigo-500/20">
      <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Perubahan
    </button>
  </div>
</form>

<template id="color-variant-template">
  <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end" data-variant-row>
    <div class="md:col-span-4">
      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Label</label>
      <input type="text" name="color_variants[__INDEX__][label]"
        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="Hitam">
    </div>
    <div class="md:col-span-2">
      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Warna</label>
      <input type="color" name="color_variants[__INDEX__][color]" value="#111827"
        class="w-full h-11 rounded-xl border border-zinc-200 bg-white">
    </div>
    <div class="md:col-span-3">
      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Gambar (URL)</label>
      <input type="text" name="color_variants[__INDEX__][images]"
        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="url1, url2 (pisahkan koma)">
    </div>
    <div class="md:col-span-2">
      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Upload Gambar</label>
      <input type="file" name="color_variants[__INDEX__][image_uploads][]" multiple accept="image/*"
        class="w-full bg-neutral-50 rounded-xl py-2 px-3 text-[11px] font-medium border border-zinc-200">
    </div>
    <div class="md:col-span-1 flex justify-end">
      <button type="button" class="btn btn-ghost btn-sm" data-variant-remove>
        <i class="fa-solid fa-trash"></i>
      </button>
    </div>
  </div>
</template>

<template id="lens-variant-template">
  <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end" data-variant-row>
    <div class="md:col-span-4">
      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Label</label>
      <input type="text" name="lens_variants[__INDEX__][label]"
        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="Standar">
    </div>
    <div class="md:col-span-2">
      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Harga</label>
      <input type="number" name="lens_variants[__INDEX__][price]" value="0" min="0"
        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="0">
    </div>
    <div class="md:col-span-3">
      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi</label>
      <input type="text" name="lens_variants[__INDEX__][desc]"
        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200" placeholder="Lensa bening standar">
    </div>
    <div class="md:col-span-1">
      <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Ikon</label>
      <select name="lens_variants[__INDEX__][icon]"
        class="w-full bg-neutral-50 rounded-xl py-2.5 px-3 text-xs font-medium border border-zinc-200">
        @foreach($lensIconOptions as $iconClass => $iconLabel)
          <option value="{{ $iconClass }}" {{ $iconClass === 'fa-solid fa-eye' ? 'selected' : '' }}>
            {{ $iconLabel }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="md:col-span-1 flex justify-end">
      <button type="button" class="btn btn-ghost btn-sm" data-variant-remove>
        <i class="fa-solid fa-trash"></i>
      </button>
    </div>
  </div>
</template>

@push('scripts')
<script>
  (function () {
    const colorList = document.getElementById('color-variant-list');
    const lensList = document.getElementById('lens-variant-list');
    const colorTemplate = document.getElementById('color-variant-template');
    const lensTemplate = document.getElementById('lens-variant-template');
    const addColorBtn = document.getElementById('add-color-variant');
    const addLensBtn = document.getElementById('add-lens-variant');

    function bindRemove(container) {
      if (!container) return;
      container.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-variant-remove]');
        if (!btn) return;
        const row = btn.closest('[data-variant-row]');
        if (row) row.remove();
      });
    }

    function addRow(container, template, index) {
      if (!container || !template) return;
      const html = template.innerHTML.replace(/__INDEX__/g, String(index));
      const wrapper = document.createElement('div');
      wrapper.innerHTML = html.trim();
      container.appendChild(wrapper.firstElementChild);
    }

    if (addColorBtn && colorList && colorTemplate) {
      let colorIndex = colorList.children.length;
      addColorBtn.addEventListener('click', function () {
        addRow(colorList, colorTemplate, colorIndex++);
      });
    }

    if (addLensBtn && lensList && lensTemplate) {
      let lensIndex = lensList.children.length;
      addLensBtn.addEventListener('click', function () {
        addRow(lensList, lensTemplate, lensIndex++);
      });
    }

    bindRemove(colorList);
    bindRemove(lensList);
  })();
</script>
@endpush
@endsection
