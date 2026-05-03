@extends('layouts.admin')

@section('title', 'Tambah Anggota Tim')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
    <i class="fa-solid fa-chevron-right text-[10px]"></i>
    <a href="{{ route('admin.teams.index') }}" class="hover:text-indigo-600 transition-colors">Tim</a>
    <i class="fa-solid fa-chevron-right text-[10px]"></i>
    <span class="text-neutral-900 font-semibold">Tambah Baru</span>
@endsection

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 md:p-8">
            <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-neutral-900 mb-2">Nama <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-bold text-neutral-900 mb-2">Jabatan / Peran
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="role" name="role" value="{{ old('role') }}"
                                placeholder="Mis. Founder & CEO" required
                                class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
                            @error('role')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-bold text-neutral-900 mb-2">Status Penayangan
                                <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required
                                class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200">
                                <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>
                                    Published (Tampil)</option>
                                <option value="unpublished" {{ old('status') == 'unpublished' ? 'selected' : '' }}>
                                    Unpublished (Sembunyi)</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto -->
                        <div>
                            <label class="block text-sm font-bold text-neutral-900 mb-2">Foto / Avatar (Opsional)</label>
                            <input type="file" id="photo" name="photo" accept="image/*"
                                class="w-full bg-neutral-50 rounded-xl py-2 px-3 text-sm border border-zinc-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="text-[11px] text-gray-500 mt-1.5">Disarankan menggunakan gambar rasio 1:1 (persegi).
                            </p>
                            @error('photo')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-zinc-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.teams.index') }}"
                        class="btn bg-white border border-zinc-200 text-neutral-700 hover:bg-neutral-50 text-sm">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary text-sm shadow-md shadow-indigo-500/20">
                        Simpan Anggota
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
