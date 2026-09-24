{{-- Student: Assignments --}}
@extends('layouts.student')
@section('title', 'My Assignments')

@section('styles')
    @include('partials.styles.bento')
@endsection

@section('content')
    <div class="bento-content">
        <div class="bento-header">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Assignments</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track due dates and submissions.</p>
        </div>
        <div class="bento-grid">
            <article class="bento-card bento-card--span-2">
                <div class="bento-card__label">Academics</div>
                <h2 class="bento-card__title">Assignment List</h2>
                <p class="bento-card__description">Assignments across your enrolled subjects</p>
                {{-- Assignment list and submission --}}
            </article>
        </div>
    </div>
@endsection

