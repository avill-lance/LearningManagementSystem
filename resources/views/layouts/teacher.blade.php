{{--
    Layout: Teacher
    Purpose: Teacher-specific layout extending the main app layout.
    Includes teacher-specific sidebar navigation and class management views.
--}}
@extends('layouts.app')

@section('sidebar')
    @include('partials.sidebar.teacher')
@endsection

@section('title', 'Teacher Dashboard')

@push('styles')
    {{-- Teacher-specific styles --}}
@endpush
