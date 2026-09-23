{{--
    View: Student Registration
    Purpose: Form for student account registration.
    Route: /signup/student
    Role: Public
--}}
@extends('layouts.guest')

@section('title', 'Student Registration')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Student Registration</h2>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.register.student') }}" class="space-y-4">
            @csrf

            {{-- Full Name --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input id="first_name" type="text" name="first_name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                    <input id="middle_name" type="text" name="middle_name" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input id="last_name" type="text" name="last_name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input id="username" type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>

            {{-- LRN (Learner Reference Number) --}}
            <div>
                <label for="lrn" class="block text-sm font-medium text-gray-700 mb-1">LRN (Learner Reference Number)</label>
                <input id="lrn" type="text" name="lrn" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            {{-- Date of Birth --}}
            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                <input id="birth_date" type="date" name="birth_date" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
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
                    Create Student Account
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-primary hover:underline">Sign in</a>
        </div>
    </div>
@endsection
