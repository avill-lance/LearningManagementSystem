{{-- Student: Class Schedule --}}
@extends('layouts.student')
@section('title', 'My Schedule')

@section('styles')
    @include('partials.styles.bento')
@endsection

@section('content')
    <div class="bento-content">
        <div class="bento-header">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Schedule</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your weekly class timetable.</p>
        </div>
        <div class="bento-grid">
            <article class="bento-card bento-card--span-2">
                <div class="bento-card__label">Schedule</div>
                <h2 class="bento-card__title">Class Timetable</h2>
                <p class="bento-card__description">Weekly schedule for your enrolled subjects</p>
                {{-- Student timetable --}}
            </article>
        </div>
    </div>
@endsection

