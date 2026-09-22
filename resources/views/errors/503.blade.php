{{--
    View: 503 Service Unavailable
    Purpose: Displayed during maintenance or system downtime.
    HTTP Status: 503 Service Unavailable
--}}
@extends('layouts.guest')

@section('title', 'Service Unavailable')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <div class="bg-white rounded-lg shadow-md p-12 max-w-md">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />>
            </svg>
            <h1 class="text-6xl font-bold text-gray-800 mb-2">503</h1>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Service Unavailable</h2>
            <p class="text-gray-500 mb-6">We're currently performing scheduled maintenance. Please check back shortly.</p>
            <p class="text-sm text-gray-400">Estimated downtime: Please check with your administrator.</p>
        </div>
    </div>
@endsection
