{{--
    View: Forgot Password
    Purpose: Form to request password reset link.
    Route: /forgot-password
    Role: Public
--}}
@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input id="email" type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md" required value="{{ old('email') }}">
            </div>

            {{-- Submit Button --}}
            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90">
                    Send Reset Link
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <a href="{{ route('login') }}" class="font-medium text-primary hover:underline">Back to login</a>
        </div>
    </div>
@endsection
