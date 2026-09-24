{{-- Student: Attendance --}}
@extends('layouts.student')
@section('title', 'My Attendance')

@section('styles')
    @include('partials.styles.bento')
@endsection

@section('content')
    <div class="bento-content">
        <div class="bento-header">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Attendance</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Review your attendance history.</p>
        </div>
        <div class="bento-grid">
            <article class="bento-card bento-card--span-2">
                <div class="bento-card__label">Academics</div>
                <h2 class="bento-card__title">Attendance Records</h2>
                <p class="bento-card__description">Your daily attendance across subjects</p>
                {{-- Student attendance records --}}
            </article>
        </div>
    </div>
@endsection

