{{--
    Layout: Student
    Purpose: Student-specific layout extending the main app layout.
    Includes student-specific sidebar navigation and learning views.
--}}
@extends('layouts.app')

@section('sidebar')
    @include('partials.sidebar.student')
@endsection

@section('title', 'Student Dashboard')

@push('styles')
    {{-- Student-specific styles --}}
@endpush
