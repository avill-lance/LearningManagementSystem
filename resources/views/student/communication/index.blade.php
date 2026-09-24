{{-- Student: Communication --}}
@extends('layouts.student')
@section('title', 'Communications')

@section('styles')
    @include('partials.styles.bento')
@endsection

@section('content')
    <div class="bento-content">
        <div class="bento-header">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Communications</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Announcements and messages from your teachers.</p>
        </div>
        <div class="bento-grid">
            <article class="bento-card">
                <div class="bento-card__label">Inbox</div>
                <h2 class="bento-card__title">Announcements</h2>
                <p class="bento-card__description">Latest posts from your school</p>
                {{-- Announcements --}}
            </article>
            <article class="bento-card">
                <div class="bento-card__label">Inbox</div>
                <h2 class="bento-card__title">Messages</h2>
                <p class="bento-card__description">Direct messages with teachers and staff</p>
                {{-- Messages --}}
            </article>
        </div>
    </div>
@endsection

