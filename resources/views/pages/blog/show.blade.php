@extends('layouts.app')

@section('title', ($article->meta_title ?: $article->title) . ' — IndoOptik')
@section('description', $article->meta_description ?: $article->excerpt ?: 'Artikel IndoOptik.')
@section('og_title', $article->title . ' — IndoOptik')
@section('og_description', $article->excerpt ?: 'Artikel IndoOptik.')
@section('og_type', 'article')

@section('content')
<main class="page-shell py-12 flex-1">
  <nav aria-label="Breadcrumb" class="breadcrumb mb-6" data-animate>
    <a href="{{ route('home') }}">Beranda</a>
    <span class="separator">›</span>
    <a href="{{ route('blog.index') }}">Blog</a>
    <span class="separator">›</span>
    <span class="current">{{ $article->title }}</span>
  </nav>

  <article class="max-w-3xl mx-auto">
    <p class="text-sm font-bold text-indigo-600 mb-3">{{ $article->published_at?->format('d M Y') }}</p>
    <h1 class="text-3xl md:text-5xl font-extrabold text-neutral-900 mb-5 leading-tight">{{ $article->title }}</h1>
    @if($article->excerpt)
      <p class="text-lg text-gray-500 leading-relaxed mb-8">{{ $article->excerpt }}</p>
    @endif

    @if($article->cover_image_url)
      <img src="{{ $article->cover_image_url }}" alt="{{ $article->title }}" class="w-full rounded-3xl mb-8 border border-zinc-100 shadow-sm">
    @endif

    <div class="prose max-w-none text-gray-700 leading-relaxed">
      {!! nl2br(e($article->content)) !!}
    </div>
  </article>
</main>
@endsection
