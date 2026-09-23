{{--
    Layout: Admin
    Purpose: Admin-specific layout extending the main app layout.
    Includes admin-specific sidebar navigation and dashboard widgets.
--}}
@extends('layouts.app')

@section('sidebar')
    @include('partials.sidebar.admin')
@endsection

@section('navbar')
    @include('partials.navigation.admin')
@endsection

@section('title', 'Admin Dashboard')

@section('styles')
    {{-- Admin-specific styles --}}
@endsection
