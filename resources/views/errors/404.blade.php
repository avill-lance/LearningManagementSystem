{{--
    View: 404 Not Found
    Purpose: Displayed when a requested page does not exist.
    HTTP Status: 404 Not Found
--}}
@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <div class="bg-white rounded-lg shadow-md p-12 max-w-md">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />>
            </svg>
            <h1 class="text-6xl font-bold text-gray-800 mb-2">404</h1>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Page Not Found</h2>
            <p class="text-gray-500 mb-6">The page you're looking for doesn't exist or has been moved.</p>
            <a href="{{ url('/dashboard') }}" class="inline-block px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition">
                Go to Dashboard
            </a>
        </div>
    </div>
@endsection
