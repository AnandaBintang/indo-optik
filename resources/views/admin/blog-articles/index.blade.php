@extends('layouts.admin')

@section('title', 'Artikel Blog')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Artikel Blog</span>
@endsection

@section('content')
<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <form method="GET" action="{{ route('admin.blog-articles.index') }}" class="flex flex-col sm:flex-row gap-3">
      <input type="text" name="search" value="{{ $search }}" placeholder="Cari artikel"
        class="bg-white text-neutral-900 rounded-xl py-2.5 px-4 text-sm font-medium border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      <select name="status" class="bg-white text-neutral-900 rounded-xl py-2.5 px-4 text-sm font-medium border border-zinc-200" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
      </select>
    </form>
    <a href="{{ route('admin.blog-articles.create') }}" class="btn btn-primary text-sm">
      <i class="fa-solid fa-plus"></i> Tambah Artikel
    </a>
  </div>

  <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-neutral-50 text-gray-500">
          <tr>
            <th class="text-left font-bold px-5 py-3">Judul</th>
            <th class="text-left font-bold px-5 py-3">Status</th>
            <th class="text-left font-bold px-5 py-3">Tanggal</th>
            <th class="text-right font-bold px-5 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
          @forelse($articles as $article)
            <tr>
              <td class="px-5 py-4">
                <p class="font-bold text-neutral-900">{{ $article->title }}</p>
                <p class="text-xs text-gray-500">{{ $article->slug }}</p>
              </td>
              <td class="px-5 py-4">
                <span class="inline-flex px-2 py-1 rounded-lg text-xs font-bold {{ $article->status === 'published' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                  {{ ucfirst($article->status) }}
                </span>
              </td>
              <td class="px-5 py-4 text-gray-600">{{ $article->published_at?->format('d M Y H:i') ?? '-' }}</td>
              <td class="px-5 py-4">
                <div class="flex justify-end gap-2">
                  <a href="{{ route('admin.blog-articles.edit', $article) }}" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white flex items-center justify-center">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.blog-articles.destroy', $article) }}" data-confirm-form data-confirm="Hapus artikel ini?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-5 py-12 text-center text-gray-500">Belum ada artikel blog.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{ $articles->links() }}
</div>
@endsection
