@extends('layouts.admin')

@section('title', 'Edit Artikel')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <a href="{{ route('admin.blog-articles.index') }}" class="hover:text-indigo-600 transition-colors">Artikel Blog</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold truncate">{{ $blogArticle->title }}</span>
@endsection

@section('content')
<form action="{{ route('admin.blog-articles.update', $blogArticle) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
  @csrf
  @method('PUT')
  @include('admin.blog-articles.partials.form', ['article' => $blogArticle])
</form>
@endsection
