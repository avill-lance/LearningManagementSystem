{{-- Student: Learning Materials --}}
@extends('layouts.student')
@section('title', 'Learning Materials')

@section('styles')
    @include('partials.styles.bento')
@endsection

@section('content')
    <div class="bento-content">
        <div class="bento-header">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Learning Materials</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Access study resources for your subjects.</p>
        </div>
        <div class="bento-grid">
            <article class="bento-card bento-card--span-2">
                <div class="bento-card__label">LMS Content</div>
                <h2 class="bento-card__title">Study Resources</h2>
                <p class="bento-card__description">Modules, lessons, and digital resources shared by your teachers</p>
                {{-- Access study resources --}}
            </article>
        </div>
    </div>
@endsection

