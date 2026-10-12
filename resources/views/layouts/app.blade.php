<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CampusPulse - Platform Info Kondisi Fasilitas Kampus Berbasis Laporan Komunitas Mahasiswa">
    <title>{{ config('app.name', 'CampusPulse') }} @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $cssFile = $manifest['resources/css/app.css']['file'] ?? 'assets/app.css';
        $jsFile = $manifest['resources/js/app.js']['file'] ?? 'assets/app.js';
    @endphp
    <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    <script src="{{ asset('build/' . $jsFile) }}" defer></script>

    @yield('head')
</head>
<body class="bg-surface text-primary font-sans antialiased" @yield('body-class')>
    @include('components.navbar')

    <main class="min-h-screen" @yield('main-class')>
        @yield('content')
    </main>

    @include('components.footer')

    @yield('scripts')
</body>
</html>