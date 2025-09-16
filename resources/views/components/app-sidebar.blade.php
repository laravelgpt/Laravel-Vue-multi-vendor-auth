@props(['variant' => 'sidebar'])

@if($variant === 'header')
    <div class="flex min-h-screen w-full flex-col">
        {{ $slot }}
    </div>
@else
    <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg">
        <div class="flex flex-col h-full">
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <x-app-logo />
                </a>
            </div>
            
            <!-- Sidebar Content -->
            <div class="flex-1 p-4">
                <x-nav-main />
            </div>
            
            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <x-nav-footer />
                <x-nav-user />
            </div>
        </div>
    </aside>
@endif
