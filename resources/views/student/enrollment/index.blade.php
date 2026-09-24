{{-- Student: Enrollment --}}
@extends('layouts.student')
@section('title', 'My Enrollment')

@section('styles')
    @include('partials.styles.bento')
@endsection

@section('content')
    <div class="bento-content">
        <div class="bento-header">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Enrollment</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your enrollment status and subject load.</p>
        </div>
        <div class="bento-grid">
            <article class="bento-card bento-card--span-2">
                <div class="bento-card__label">Records</div>
                <h2 class="bento-card__title">Enrollment Details</h2>
                <p class="bento-card__description">Current school year, grade level, and status</p>
                {{-- Student enrollment details --}}
            </article>
        </div>
    </div>
@endsection

