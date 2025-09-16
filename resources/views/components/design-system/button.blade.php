@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'href' => null,
    'target' => null,
    'class' => '',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden';
    
    $sizeClasses = [
        'xs' => 'px-2.5 py-1.5 text-xs rounded-md',
        'sm' => 'px-3 py-2 text-sm rounded-lg',
        'md' => 'px-4 py-2.5 text-sm rounded-lg',
        'lg' => 'px-6 py-3 text-base rounded-xl',
        'xl' => 'px-8 py-4 text-lg rounded-xl',
    ];
    
    $variantClasses = [
        'primary' => 'bg-gradient-primary text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 focus:ring-purple-500',
        'secondary' => 'bg-gradient-secondary text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 focus:ring-purple-500',
        'accent' => 'bg-gradient-accent text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 focus:ring-blue-500',
        'outline' => 'border-2 border-purple-500 text-purple-600 bg-transparent hover:bg-purple-500 hover:text-white focus:ring-purple-500',
        'ghost' => 'text-purple-600 bg-transparent hover:bg-purple-50 focus:ring-purple-500',
        'success' => 'bg-gradient-success text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 focus:ring-green-500',
        'warning' => 'bg-gradient-warning text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 focus:ring-yellow-500',
        'danger' => 'bg-gradient-danger text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 focus:ring-red-500',
    ];
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant] . ' ' . $class;
    
    $tag = $href ? 'a' : 'button';
    $attributes = $href ? ['href' => $href, 'target' => $target] : ['type' => $type, 'disabled' => $disabled];
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icon && $iconPosition === 'left')
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4 mr-2" />
    @endif
    
    <span>{{ $slot }}</span>
    
    @if($icon && $iconPosition === 'right')
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4 ml-2" />
    @endif
    
    <!-- Shimmer effect -->
    <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-700 group-hover:translate-x-full"></div>
</{{ $tag }}>
