@php
  $article = $article ?? null;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 md:p-8 space-y-5">
    <div>
      <label for="title" class="block text-sm font-bold text-neutral-900 mb-2">Judul <span class="text-red-500">*</span></label>
      <input type="text" id="title" name="title" value="{{ old('title', $article?->title) }}" required
        class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200 focus:ring-2 focus:ring-indigo-500">
      @error('title') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="slug" class="block text-sm font-bold text-neutral-900 mb-2">Slug</label>
      <input type="text" id="slug" name="slug" value="{{ old('slug', $article?->slug) }}"
        class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200 focus:ring-2 focus:ring-indigo-500">
      @error('slug') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="excerpt" class="block text-sm font-bold text-neutral-900 mb-2">Ringkasan</label>
      <textarea id="excerpt" name="excerpt" rows="3"
        class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200 focus:ring-2 focus:ring-indigo-500">{{ old('excerpt', $article?->excerpt) }}</textarea>
      @error('excerpt') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="content" class="block text-sm font-bold text-neutral-900 mb-2">Konten <span class="text-red-500">*</span></label>
      <textarea id="content" name="content" rows="12" required
        class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200 focus:ring-2 focus:ring-indigo-500">{{ old('content', $article?->content) }}</textarea>
      @error('content') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="space-y-6">
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 space-y-5">
      <div>
        <label class="block text-sm font-bold text-neutral-900 mb-2">Status</label>
        <select name="status" class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200">
          <option value="draft" {{ old('status', $article?->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
          <option value="published" {{ old('status', $article?->status) === 'published' ? 'selected' : '' }}>Published</option>
        </select>
        @error('status') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="published_at" class="block text-sm font-bold text-neutral-900 mb-2">Tanggal Publish</label>
        <input type="datetime-local" id="published_at" name="published_at"
          value="{{ old('published_at', $article?->published_at?->format('Y-m-d\TH:i')) }}"
          class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200">
        @error('published_at') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="cover_image" class="block text-sm font-bold text-neutral-900 mb-2">Cover Image</label>
        <input type="file" id="cover_image" name="cover_image" accept="image/*"
          class="w-full bg-neutral-50 rounded-xl py-2 px-3 text-sm border border-zinc-200">
        <input type="url" name="cover_image_url" value="{{ old('cover_image_url') }}" placeholder="Atau URL gambar"
          class="mt-3 w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200">
        @error('cover_image') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        @error('cover_image_url') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 space-y-5">
      <div>
        <label for="meta_title" class="block text-sm font-bold text-neutral-900 mb-2">Meta Title</label>
        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $article?->meta_title) }}"
          class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200">
      </div>
      <div>
        <label for="meta_description" class="block text-sm font-bold text-neutral-900 mb-2">Meta Description</label>
        <textarea id="meta_description" name="meta_description" rows="3"
          class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium border border-zinc-200">{{ old('meta_description', $article?->meta_description) }}</textarea>
      </div>
    </div>
  </div>
</div>

<div class="pt-6 border-t border-zinc-200 flex items-center justify-end gap-3">
  <a href="{{ route('admin.blog-articles.index') }}" class="btn bg-white border border-zinc-200 text-neutral-700 hover:bg-neutral-50 text-sm">Batal</a>
  <button type="submit" class="btn btn-primary text-sm shadow-md shadow-indigo-500/20">
    <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Artikel
  </button>
</div>
