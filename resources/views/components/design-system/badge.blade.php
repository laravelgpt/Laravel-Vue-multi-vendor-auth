@props([
    'variant' => 'default',
    'size' => 'md',
    'icon' => null,
    'class' => '',
])

@php
    $baseClasses = 'inline-flex items-center font-medium rounded-full transition-all duration-200';
    
    $sizeClasses = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-1 text-xs',
        'md' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-4 py-2 text-base',
    ];
    
    $variantClasses = [
        'default' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
        'primary' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'secondary' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'danger' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'gradient' => 'bg-gradient-primary text-white shadow-lg',
    ];
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant] . ' ' . $class;
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-3 h-3 mr-1" />
    @endif
    
    {{ $slot }}
</span>
