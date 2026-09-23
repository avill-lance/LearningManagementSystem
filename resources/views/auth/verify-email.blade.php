{{--
    View: Verify Email
    Purpose: Email verification notice for new accounts.
    Role: Unverified users
--}}
@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <svg class="mx-auto h-16 w-16 text-yellow-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />>
        </svg>
        <h2 class="text-2xl font-bold mb-4">Verify Your Email</h2>
        <p class="text-gray-600 mb-4">We've sent a verification link to your email. Please check your inbox.</p>
        <p class="text-sm text-gray-500 mb-6">If you haven't received the email, you can <a href="{{ route('verification.resend') }}" class="text-primary font-medium hover:underline">resend the verification link</a>.</p>
    </div>
@endsection
