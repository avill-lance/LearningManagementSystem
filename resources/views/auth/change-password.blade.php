{{-- Auth: Forced Password Change (first login for Teacher/Student) --}}
@extends('layouts.guest')
@section('title', 'Change Your Password')

@section('bodyClass', 'bg-gradient-to-br from-sky-200 via-blue-100 to-cyan-100')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">
        <h1 class="text-center text-xl font-semibold text-gray-900 mb-2">Change Your Password</h1>
        <p class="text-center text-sm text-gray-600 mb-6">
            For your security, you must set a new password before continuing.
        </p>

        @include('partials.flash-messages')

        @if ($errors->any())
            <div class="relative mb-6 overflow-hidden rounded-xl border border-white/80 bg-white/70 py-2.5 pl-3.5 pr-3 text-xs text-red-800 shadow-md shadow-rose-500/10 ring-1 ring-rose-200/70 backdrop-blur-md" role="alert">
                <span class="absolute inset-y-0 left-0 w-0.5 bg-gradient-to-b from-rose-400 to-red-500" aria-hidden="true"></span>
                <div class="flex items-start gap-2.5">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-gradient-to-br from-rose-400 to-red-500 text-white shadow-sm shadow-rose-500/30">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 8v5"/>
                            <circle cx="12" cy="16.5" r=".6" fill="currentColor"/>
                            <path d="M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                        </svg>
                    </span>
                    <div class="min-w-0 pt-px">
                        <p class="font-semibold text-gray-900">Couldn't update your password</p>
                        @if ($errors->count() === 1)
                            <p class="leading-snug text-red-700/90">{{ $errors->first() }}</p>
                        @else
                            <ul class="mt-0.5 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-start gap-1.5 leading-snug text-red-700/90">
                                        <span class="mt-[6px] h-1 w-1 shrink-0 rounded-full bg-rose-400" aria-hidden="true"></span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="sr-only">Current Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sky-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="5" y="11" width="14" height="10" rx="2"/>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <input type="password" name="current_password" id="current_password" required autofocus
                        placeholder="Current Password"
                        class="block w-full rounded-xl border border-sky-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-sky-400 focus:outline-none focus:ring-4 focus:ring-sky-200/60">
                </div>
            </div>

            <div>
                <label for="password" class="sr-only">New Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sky-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="5" y="11" width="14" height="10" rx="2"/>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <input type="password" name="password" id="password" required minlength="8"
                        placeholder="New Password"
                        class="block w-full rounded-xl border border-sky-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-sky-400 focus:outline-none focus:ring-4 focus:ring-sky-200/60">
                </div>
                <p class="mt-1.5 ml-1 text-xs text-gray-500">Minimum 8 characters.</p>
            </div>

            <div>
                <label for="password_confirmation" class="sr-only">Confirm New Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sky-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="5" y="11" width="14" height="10" rx="2"/>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                        placeholder="Confirm New Password"
                        class="block w-full rounded-xl border border-sky-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-sky-400 focus:outline-none focus:ring-4 focus:ring-sky-200/60">
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 transition hover:from-sky-600 hover:to-blue-700 hover:shadow-sky-500/40 focus:outline-none focus:ring-4 focus:ring-sky-300 active:scale-[.98]">
                    Update Password
                </button>
            </div>
        </form>
    </div>
@endsection