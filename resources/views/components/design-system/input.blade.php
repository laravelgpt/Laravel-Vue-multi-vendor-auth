@props([
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
    'error' => null,
    'success' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'size' => 'md',
    'disabled' => false,
    'required' => false,
    'class' => '',
])

@php
    $baseClasses = 'w-full transition-all duration-200 ease-out focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $sizeClasses = [
        'sm' => 'px-3 py-2 text-sm rounded-lg',
        'md' => 'px-4 py-3 text-base rounded-xl',
        'lg' => 'px-5 py-4 text-lg rounded-xl',
    ];
    
    $stateClasses = match(true) {
        $error => 'border-red-500 focus:ring-red-500 bg-red-50 dark:bg-red-900/20',
        $success => 'border-green-500 focus:ring-green-500 bg-green-50 dark:bg-green-900/20',
        default => 'border-gray-300 dark:border-gray-600 focus:ring-purple-500 bg-white dark:bg-gray-800'
    };
    
    $iconClasses = $icon ? ($iconPosition === 'left' ? 'pl-10' : 'pr-10') : '';
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $stateClasses . ' ' . $iconClasses . ' ' . $class;
@endphp

<div class="space-y-2">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-dynamic-component :component="'heroicon-o-' . $icon" class="h-5 w-5 text-gray-400" />
            </div>
        @endif
        
        <input 
            {{ $attributes->merge([
                'type' => $type,
                'placeholder' => $placeholder,
                'disabled' => $disabled,
                'required' => $required,
                'class' => $classes
            ]) }}
        />
        
        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <x-dynamic-component :component="'heroicon-o-' . $icon" class="h-5 w-5 text-gray-400" />
            </div>
        @endif
    </div>
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
            <x-heroicon-o-exclamation-circle class="w-4 h-4 mr-1" />
            {{ $error }}
        </p>
    @endif
    
    @if($success)
        <p class="text-sm text-green-600 dark:text-green-400 flex items-center">
            <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
            {{ $success }}
        </p>
    @endif
</div>
