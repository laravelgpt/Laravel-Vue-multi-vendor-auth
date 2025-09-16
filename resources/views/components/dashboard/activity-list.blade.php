@props([
    'title' => 'Recent Activity',
    'items' => [],
    'emptyMessage' => 'No recent activity',
    'showActions' => false,
    'actionMethod' => null
])

<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $title }}</h3>
    </div>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($items as $item)
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    @if(isset($item['avatar']))
                        <img class="h-10 w-10 rounded-full" src="{{ $item['avatar'] }}" alt="{{ $item['name'] }}">
                    @else
                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['name'] ?? 'Unknown' }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item['email'] ?? $item['description'] ?? '' }}</div>
                        @if(isset($item['created_at']))
                            <div class="text-xs text-gray-400 dark:text-gray-500">{{ $item['created_at'] }}</div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if(isset($item['is_active']))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item['is_active'] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $item['is_active'] ? 'Active' : 'Inactive' }}
                        </span>
                    @endif
                    @if(isset($item['is_verified']))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item['is_verified'] ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                            {{ $item['is_verified'] ? 'Verified' : 'Pending' }}
                        </span>
                    @endif
                    @if($showActions && $actionMethod && isset($item['id']))
                        <button wire:click="{{ $actionMethod }}({{ $item['id'] }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">{{ $emptyMessage }}</div>
        @endforelse
    </div>
</div>
