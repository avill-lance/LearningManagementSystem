{{-- Partial: Flash Messages --}}
@if (session('success'))
    <div class="p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="p-3 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
@endif
@if (session('warning'))
    <div class="p-3 bg-yellow-100 text-yellow-700 rounded">{{ session('warning') }}</div>
@endif
