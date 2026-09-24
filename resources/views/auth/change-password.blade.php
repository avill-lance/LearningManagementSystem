{{-- Auth: Forced Password Change (first login for Teacher/Student) --}}
@extends('layouts.guest')
@section('title', 'Change Your Password')
@section('content')
    <div class="bg-white shadow rounded-lg p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Change Your Password</h1>
        <p class="text-sm text-gray-600 mb-6">
            For your security, you must set a new password before continuing.
        </p>

        @include('partials.flash-messages')

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input type="password" name="current_password" id="current_password" required autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" name="password" id="password" required minlength="8"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                <p class="mt-1 text-xs text-gray-500">Minimum 8 characters.</p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>

            <button type="submit"
                class="w-full inline-flex justify-center py-2 px-4 rounded-md bg-primary text-white text-sm font-medium hover:opacity-90">
                Update Password
            </button>
        </form>
    </div>
@endsection
