@extends('layouts.app')

@section('title', 'Admin Registration')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6">Register New Admin</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.register.store') }}">
        @csrf

        <div class="mb-4">
            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">
                Username
            </label>
            <input type="text"
                   id="username"
                   name="username"
                   value="{{ old('username') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
            @if ($errors->has('username'))
                <span class="text-red-500 text-xs italic">{{ $errors->first('username') }}</span>
            @endif
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                Email
            </label>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
            @if ($errors->has('email'))
                <span class="text-red-500 text-xs italic">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                Password
            </label>
            <input type="password"
                   id="password"
                   name="password"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
            @if ($errors->has('password'))
                <span class="text-red-500 text-xs italic">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">
                Confirm Password
            </label>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
            @if ($errors->has('password_confirmation'))
                <span class="text-red-500 text-xs italic">{{ $errors->first('password_confirmation') }}</span>
            @endif
        </div>

        <div class="flex items-center justify-between">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Register Admin
            </button>
            <a href="{{ route('admin.dashboard') }}"
               class="text-blue-500 hover:text-blue-700 underline">
                Back to Dashboard
            </a>
        </div>
    </form>
</div>
@endsection