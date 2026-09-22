<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)"
    x-init="
        $watch('dark', val => {
            if (val) {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            }
        });
        if (document.documentElement.classList.contains('dark')) {
            $set('dark', true);
        }
    "
    x-cloak
    :class="{ 'dark': dark }"
    class="">
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
    {{-- Dark Mode Toggle --}}
    <button x-show="$matchMedia('(min-width: 640px)').matches"
            @click="dark = !dark"
            class="fixed bottom-4 right-4 z-50 flex items-center justify-center w-12 h-12 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg cursor-pointer hover:scale-110 transition-transform"
            aria-label="Toggle dark mode">
        <template x-if="!dark">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </template>
        <template x-if="dark">
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewbox="0 0 24 24">
                <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </template>
    </button>
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
