{{-- Admin: View User --}}
@extends('layouts.admin')
@section('title', 'View Account')
@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Account Details</h1>
            <a href="{{ route('admin.users.index') }}" class="btn-cancel">← Back</a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><dt class="text-sm font-medium text-gray-500">Name</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ trim($user->first_name . ' ' . $user->last_name) }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->email }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Role</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->role }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Status</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->status }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Gender</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->gender ?? 'N/A' }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Birthdate</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->birthdate?->format('M d, Y') ?? 'N/A' }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Contact</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->contact_number ?? 'N/A' }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Address</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->address ?? 'N/A' }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Created</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('M d, Y') }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500">Is Deleted</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->is_deleted ? 'Yes' : 'No' }}</dd></div>
                @if($user->teacher)
                    <div class="md:col-span-2 border-t pt-4 mt-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Teacher Details</h3>
                        <div><dt class="text-sm font-medium text-gray-500">Teacher Number</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->teacher->teacher_number ?? 'N/A' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Specialization</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->teacher->specialization ?? 'N/A' }}</dd></div>
                    </div>
                @endif
                @if($user->student)
                    <div class="md:col-span-2 border-t pt-4 mt-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Student Details</h3>
                        <div><dt class="text-sm font-medium text-gray-500">LRN</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->student->lrn ?? 'N/A' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Student Number</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->student->student_number ?? 'N/A' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Grade Level</dt><dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $user->student->grade_level ?? 'N/A' }}</dd></div>
                    </div>
                @endif
            </dl>
        </div>
    </div>
@endsection
