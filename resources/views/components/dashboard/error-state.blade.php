@props([
    'title' => 'Something went wrong',
    'message' => 'An error occurred while loading the data. Please try again.',
    'retryAction' => null,
    'retryText' => 'Try Again'
])

<div class="flex flex-col items-center justify-center p-8 text-center">
    <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
    </div>
    
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ $message }}</p>
    
    @if($retryAction)
        <button wire:click="{{ $retryAction }}" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            {{ $retryText }}
        </button>
    @endif
</div>
