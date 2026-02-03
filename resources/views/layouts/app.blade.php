<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Safari Tours - Adventure Awaits')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Discover amazing safari adventures in East Africa. Wildlife tours, cultural experiences, and unforgettable journeys await you.')">
    <meta name="keywords" content="@yield('meta_keywords', 'safari, tours, wildlife, Africa, adventure, travel, booking')">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('og_title', 'Safari Tours - Adventure Awaits')">
    <meta property="og:description" content="@yield('og_description', 'Discover amazing safari adventures in East Africa.')">
    <meta property="og:image" content="@yield('og_image', asset('images/safari-og.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation Component -->
    <x-navigation />

    <!-- Page Header Section (Each page can have its own) -->
    @hasSection('page-header')
        @yield('page-header')
    @endif

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer Component -->
    <x-footer />

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>