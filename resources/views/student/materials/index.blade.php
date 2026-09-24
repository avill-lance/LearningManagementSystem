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
            @forelse ($materialsBySubject as $subjectName => $materials)
                <article class="bento-card bento-card--span-2">
                    <div class="bento-card__label">Subject</div>
                    <h2 class="bento-card__title">{{ $subjectName }}</h2>
                    <p class="bento-card__description">{{ $materials->count() }} {{ Str::plural('material', $materials->count()) }} available</p>

                    <ul class="mt-3 divide-y divide-gray-100 dark:divide-white/10">
                        @foreach ($materials as $material)
                            <li class="flex items-center justify-between gap-3 py-2.5">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ $material->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Posted {{ $material->uploaded_at?->format('M d, Y') }}</p>
                                </div>
                                <div class="flex shrink-0 items-center gap-1.5">
                                    <a href="{{ route('materials.preview', $material->material_id) }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-1.5 rounded-lg bg-slate-500/10 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-500 hover:text-white dark:text-slate-300">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Preview
                                    </a>
                                    <a href="{{ route('materials.download', $material->material_id) }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg bg-blue-500/10 px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-500 hover:text-white dark:text-blue-300">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                                        Download
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @empty
                <article class="bento-card bento-card--span-2">
                    <div class="bento-card__label">LMS Content</div>
                    <h2 class="bento-card__title">No materials yet</h2>
                    <p class="bento-card__description">Your teachers haven't published any learning materials for your section yet.</p>
                </article>
            @endforelse
        </div>
    </div>
@endsection

