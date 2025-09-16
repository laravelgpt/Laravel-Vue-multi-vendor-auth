@props([
    'variant' => 'default',
    'hover' => true,
    'padding' => 'md',
    'class' => '',
])

@php
    $baseClasses = 'bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300 ease-out';
    
    $variantClasses = [
        'default' => 'border border-gray-200 dark:border-gray-700',
        'elevated' => 'shadow-xl border-0',
        'glass' => 'bg-white/10 backdrop-blur-lg border border-white/20',
        'gradient' => 'bg-gradient-to-br from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 border border-purple-200 dark:border-purple-700',
    ];
    
    $paddingClasses = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
        'xl' => 'p-10',
    ];
    
    $hoverClasses = $hover ? 'hover:shadow-xl hover:-translate-y-1' : '';
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $paddingClasses[$padding] . ' ' . $hoverClasses . ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($header))
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
            {{ $header }}
        </div>
    @endif
    
    @if(isset($body))
        <div class="space-y-4">
            {{ $body }}
        </div>
    @else
        {{ $slot }}
    @endif
    
    @if(isset($footer))
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">
            {{ $footer }}
        </div>
    @endif
</div>
