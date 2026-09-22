{{--
    View: Reset Password
    Purpose: Form to reset password using token from email.
    Route: /reset-password/{token}
    Role: Public
--}}
@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Set New Password</h2>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? old('token') }}">

            {{-- Email (hidden, pre-filled) --}}
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            {{-- New Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input id="password" type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md" required autocomplete="new-password">
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md" required autocomplete="new-password">
            </div>

            {{-- Submit Button --}}
            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
@endsection
