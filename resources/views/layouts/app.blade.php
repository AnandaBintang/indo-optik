<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'IndoOptik — Kacamata & Lensa Berkualitas Terbaik')</title>
  <meta name="description" content="@yield('description', 'IndoOptik menyediakan kacamata dan lensa berkualitas tinggi dengan harga terjangkau. Temukan frame modern, periksa mata, dan buat janji temu secara online.')">
  <meta property="og:title" content="@yield('og_title', 'IndoOptik — Kacamata & Lensa Berkualitas Terbaik')" />
  <meta property="og:description" content="@yield('og_description', 'Solusi optik modern yang praktis, terjangkau, dan stylish di Indonesia.')" />
  <meta property="og:type" content="@yield('og_type', 'website')" />
  @yield('og_tags')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body class="bg-neutral-50 text-neutral-900 antialiased flex flex-col min-h-screen">
  @include('partials.navbar')
  <main class="flex-1 w-full">@yield('content')</main>
  @include('partials.footer')
  @stack('scripts')
  
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      @if(session('success'))
        window.showToast && window.showToast('success', @json(session('success')), '', 3000);
      @endif

      @if(session('error'))
        window.showToast && window.showToast('error', @json(session('error')), '', 4000);
      @endif
    });
  </script>
</body>
</html>
