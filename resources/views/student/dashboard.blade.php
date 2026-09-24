{{-- Dashboard: Student --}}
@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('styles')
    @include('partials.styles.bento')
@endsection

@section('content')
<div class="bento-content">
    <div class="bento-header">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your assignments, quizzes, and upcoming events at a glance.</p>
    </div>

    {{-- Stat cards: pending work at a glance --}}
    <div class="bento-grid">
        <a href="{{ route('student.assignments.index') }}" class="bento-card">
            <div class="bento-card__label">Academics</div>
            <h2 class="bento-card__title">Pending Assignments</h2>
            <p class="bento-card__description">Assignments awaiting submission</p>
            <div class="bento-card__value">{{ $pendingAssignmentsCount }}</div>
        </a>

        <a href="{{ route('student.quizzes.index') }}" class="bento-card">
            <div class="bento-card__label">Academics</div>
            <h2 class="bento-card__title">Pending Quizzes</h2>
            <p class="bento-card__description">Quizzes not yet attempted</p>
            <div class="bento-card__value">{{ $pendingQuizzesCount }}</div>
        </a>

        <a href="{{ route('calendar.index') }}" class="bento-card">
            <div class="bento-card__label">Schedule</div>
            <h2 class="bento-card__title">Upcoming Events</h2>
            <p class="bento-card__description">Events in the next 14 days</p>
            <div class="bento-card__value">{{ $upcomingEvents->count() }}</div>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-6xl mx-auto px-3">
        {{-- Calendar widget: next few upcoming events --}}
        <x-cards.list title="Upcoming Events" href="{{ route('calendar.index') }}" hrefLabel="View calendar" empty="No upcoming events in the next 14 days.">
            @foreach($upcomingEvents as $event)
                <li class="flex items-start justify-between gap-3 py-1.5 border-b border-gray-100 dark:border-gray-700 last:border-0">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $event->title }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $event->start_datetime->format('M j, Y g:i A') }}</p>
                    </div>
                    <span class="shrink-0 text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">{{ $event->event_type }}</span>
                </li>
            @endforeach
        </x-cards.list>

        {{-- Latest announcement --}}
        <x-cards.list title="Latest Announcement" href="{{ route('announcements.index') }}" hrefLabel="View all announcements" empty="No announcements yet.">
            @if($latestAnnouncement)
                <li>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $latestAnnouncement->title }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ \Illuminate\Support\Str::limit($latestAnnouncement->body, 160) }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $latestAnnouncement->posted_at->format('M j, Y g:i A') }}</p>
                </li>
            @endif
        </x-cards.list>
    </div>
</div>
@endsection

