{{--
    View: 500 Internal Server Error
    Purpose: Displayed when a server-side error occurs.
    HTTP Status: 500 Internal Server Error
--}}
@extends('layouts.app')

@section('title', 'Server Error')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <div class="bg-white rounded-lg shadow-md p-12 max-w-md">
            <svg class="mx-auto h-24 w-24 text-red-500 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />>
            </svg>
            <h1 class="text-6xl font-bold text-gray-800 mb-2">500</h1>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Internal Server Error</h2>
            <p class="text-gray-500 mb-6">Something went wrong on our end. Our team has been notified. Please try again later.</p>
            <div class="space-y-3">
                <a href="{{ url('/dashboard') }}" class="block px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition">
                    Go to Dashboard
                </a>
                <button onclick="window.location.reload()" class="block w-full px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                    Refresh Page
                </button>
            </div>
        </div>
    </div>
@endsection
