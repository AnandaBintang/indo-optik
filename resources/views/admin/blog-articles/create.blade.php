@extends('layouts.admin')

@section('title', 'Tambah Artikel')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <a href="{{ route('admin.blog-articles.index') }}" class="hover:text-indigo-600 transition-colors">Artikel Blog</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Tambah</span>
@endsection

@section('content')
<form action="{{ route('admin.blog-articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
  @csrf
  @include('admin.blog-articles.partials.form', ['article' => null])
</form>
@endsection
