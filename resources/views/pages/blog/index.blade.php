@extends('layouts.app')

@section('title', 'Blog — IndoOptik')
@section('description', 'Artikel dan panduan seputar kacamata, lensa, dan kesehatan mata dari IndoOptik.')

@section('content')
<main class="page-shell py-12 flex-1">
  <nav aria-label="Breadcrumb" class="breadcrumb mb-6" data-animate>
    <a href="{{ route('home') }}">Beranda</a>
    <span class="separator">›</span>
    <span class="current">Blog</span>
  </nav>

  <div class="mb-10" data-animate>
    <h1 class="text-3xl md:text-5xl font-extrabold text-neutral-900 mb-3">Blog IndoOptik</h1>
    <p class="text-gray-500 max-w-2xl">Panduan praktis memilih frame, lensa, dan merawat kesehatan mata.</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($articles as $article)
      <article class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
        <a href="{{ route('blog.show', $article->slug) }}" class="block aspect-[16/10] bg-neutral-100">
          @if($article->cover_image_url)
            <img src="{{ $article->cover_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover" loading="lazy">
          @else
            <div class="w-full h-full flex items-center justify-center text-gray-300">
              <i class="fa-solid fa-newspaper text-4xl"></i>
            </div>
          @endif
        </a>
        <div class="p-5">
          <p class="text-xs font-bold text-indigo-600 mb-2">{{ $article->published_at?->format('d M Y') }}</p>
          <h2 class="text-lg font-extrabold text-neutral-900 mb-2">
            <a href="{{ route('blog.show', $article->slug) }}" class="hover:text-indigo-600">{{ $article->title }}</a>
          </h2>
          @if($article->excerpt)
            <p class="text-sm text-gray-500 leading-relaxed">{{ $article->excerpt }}</p>
          @endif
        </div>
      </article>
    @empty
      <div class="md:col-span-2 lg:col-span-3 bg-white rounded-3xl p-12 text-center border border-zinc-100">
        <p class="text-xl font-bold text-neutral-900">Belum ada artikel.</p>
      </div>
    @endforelse
  </div>

  <div class="mt-8">{{ $articles->links() }}</div>
</main>
@endsection
