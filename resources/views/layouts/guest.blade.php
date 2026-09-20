{{--
    Layout: Guest
    Purpose: Layout for unauthenticated pages (login, register, forgot-password).
    No sidebar, no navigation. Minimal wrapper.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LMS') }} - @yield('title', 'Welcome')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    {{-- Site Header (minimal) --}}
    <header class="w-full p-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-primary">
                {{ config('app.name', 'LMS') }}
            </a>
            @if (Route::has('login'))
                <nav>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm hover:underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm hover:underline">Log in</a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    {{-- Page Content --}}
    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </main>

    {{-- Site Footer --}}
    <footer class="py-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} {{ config('app.name', 'LMS') }}. All rights reserved.
    </footer>

    @yield('scripts')
</body>
</html>
