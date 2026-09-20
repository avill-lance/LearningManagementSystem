<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LMS') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased min-h-screen">
    {{-- Sidebar --}}
    @section('sidebar')
        @include('partials.sidebar.main')
    @show

    {{-- Main Content Area with sm:ml-64 --}}
    <div class="p-4 sm:ml-64 min-h-screen flex flex-col justify-between">
        <div class="flex-1">
            {{-- Flash Messages --}}
            @include('partials.flash-messages')

            @yield('content')
        </div>

        {{-- Footer --}}
        @include('partials.footer.main')
    </div>

    @yield('scripts')
</body>
</html>
