{{-- Dashboard: Student --}}
@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Stat cards: pending work at a glance --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-cards.stat label="Pending Assignments" :value="$pendingAssignmentsCount" href="{{ route('student.assignments.index') }}">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/></svg>
        </x-cards.stat>

        <x-cards.stat label="Pending Quizzes" :value="$pendingQuizzesCount" href="{{ route('student.quizzes.index') }}">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        </x-cards.stat>

        <x-cards.stat label="Upcoming Events" :value="$upcomingEvents->count()" href="{{ route('calendar.index') }}">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"/></svg>
        </x-cards.stat>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
