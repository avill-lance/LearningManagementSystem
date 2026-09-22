{{--
    View: 403 Forbidden
    Purpose: Displayed when the user lacks permission to access a resource.
    HTTP Status: 403 Forbidden
--}}
@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <div class="bg-white rounded-lg shadow-md p-12 max-w-md">
            <svg class="mx-auto h-24 w-24 text-red-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />>
            </svg>
            <h1 class="text-6xl font-bold text-gray-800 mb-2">403</h1>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Access Denied</h2>
            <p class="text-gray-500 mb-6">You don't have permission to access this page. Contact your administrator if you believe this is an error.</p>
            <a href="{{ url('/dashboard') }}" class="inline-block px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition">
                Go to Dashboard
            </a>
        </div>
    </div>
@endsection
