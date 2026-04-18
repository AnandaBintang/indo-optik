@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <a href="{{ route('admin.categories.index') }}" class="hover:text-indigo-600 transition-colors">Kategori</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">{{ $category->name }}</span>
@endsection

@section('content')
<div class="max-w-3xl">
  <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 md:p-8">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="space-y-6">
        <!-- Nama Kategori -->
        <div>
          <label for="name" class="block text-sm font-bold text-neutral-900 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
          <input 
            type="text" 
            id="name" 
            name="name" 
            value="{{ old('name', $category->name) }}" 
            required 
            class="w-full bg-neutral-50 text-neutral-900 rounded-xl py-3 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-zinc-200 transition-all placeholder:text-gray-400"
          >
          @error('name')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
          @enderror
        </div>

        <!-- Deskripsi -->
        <div>
          <label for="description" class="block text-sm font-bold text-neutral-900 mb-2">Deskripsi (Opsional)</label>
          <textarea 
            id="description" 
            name="description" 
            rows="4" 
            class="w-full bg-neutral-50 text-neutral-900 rounded-xl py-3 px-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white border border-zinc-200 transition-all placeholder:text-gray-400"
          >{{ old('description', $category->description) }}</textarea>
          @error('description')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
          @enderror
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-bold text-neutral-900 mb-2">Status Visibilitas</label>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status" value="active" {{ old('status', $category->status) == 'active' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-zinc-300">
              <span class="text-sm font-medium text-neutral-700">Aktif</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status" value="inactive" {{ old('status', $category->status) == 'inactive' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-zinc-300">
              <span class="text-sm font-medium text-gray-500">Nonaktif</span>
            </label>
          </div>
          @error('status')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="mt-8 pt-6 border-t border-zinc-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin.categories.index') }}" class="btn bg-white border border-zinc-200 text-neutral-700 hover:bg-neutral-50 text-sm">
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
