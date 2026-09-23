{{--
    Component: Stat Card
    Usage: <x-cards.stat label="Pending Assignments" :value="3" href="{{ route('student.assignments.index') }}">
               <svg>...</svg>
           </x-cards.stat>
    The icon is passed as the default slot so any Heroicon-style SVG can be reused.
--}}
@props(['label', 'value', 'href' => null])

@php($tag = $href ? 'a' : 'div')

<{{ $tag }} @if($href) href="{{ $href }}" @endif
    class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-800 {{ $href ? 'hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors' : '' }}">
    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 shrink-0">
        {{ $slot }}
    </span>
    <span class="min-w-0">
        <span class="block text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</span>
        <span class="block text-sm text-gray-500 dark:text-gray-400 truncate">{{ $label }}</span>
    </span>
</{{ $tag }}>

