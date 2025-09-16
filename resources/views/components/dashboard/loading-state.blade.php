@props([
    'message' => 'Loading...',
    'size' => 'medium' // small, medium, large
])

@php
    $sizeClasses = [
        'small' => 'w-4 h-4',
        'medium' => 'w-6 h-6',
        'large' => 'w-8 h-8'
    ];
@endphp

<div class="flex items-center justify-center p-4">
    <div class="flex items-center space-x-3">
        <svg class="{{ $sizeClasses[$size] }} text-indigo-600 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-600 dark:text-gray-400">{{ $message }}</span>
    </div>
</div>
