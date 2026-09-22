{{
    View: Main Signup Page
    Purpose: Page for users to choose between student and teacher registration.
    Route: /signup
    Role: Public
}}
@extends('layouts.guest')

@section('title', 'Sign Up')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Sign Up</h2>
        <p class="text-center text-gray-600 mb-6">Choose your role to begin registration:</p>
        
        <div class="grid gap-6 md:grid-cols-2">
            <a href="{{ route('auth.register.student.get') }}" 
               class="block bg-primary text-white py-6 px-4 rounded-lg hover:bg-primary/90 transition-colors text-center font-medium">
                Student Registration
            </a>
            <a href="{{ route('auth.register.teacher.get') }}" 
               class="block bg-primary text-white py-6 px-4 rounded-lg hover:bg-primary/90 transition-colors text-center font-medium">
                Teacher Registration
            </a>
        </div>
        
        <div class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-primary hover:underline">Sign in</a>
        </div>
    </div>
@endsection