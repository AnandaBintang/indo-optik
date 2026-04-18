<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'IndoOptik')</title>
  <meta name="description" content="@yield('description', 'IndoOptik menyediakan kacamata dan lensa berkualitas tinggi.')">
  @yield('og-tags')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-neutral-900 antialiased flex flex-col min-h-screen">
  @include('partials.navbar')
  <main class="flex-1 w-full">
    @yield('content')
  </main>
  @include('partials.footer')
  @stack('scripts')
</body>
</html>
