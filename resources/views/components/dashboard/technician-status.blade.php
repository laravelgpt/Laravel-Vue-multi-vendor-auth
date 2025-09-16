@props([
    'currentStatus' => 'available',
    'updateMethod' => 'updateStatus'
])

<div class="flex space-x-2">
    <button wire:click="{{ $updateMethod }}('available')" 
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md {{ $currentStatus === 'available' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }} transition-colors duration-200">
        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
        Available
    </button>
    <button wire:click="{{ $updateMethod }}('busy')" 
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md {{ $currentStatus === 'busy' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }} transition-colors duration-200">
        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
        Busy
    </button>
    <button wire:click="{{ $updateMethod }}('offline')" 
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md {{ $currentStatus === 'offline' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }} transition-colors duration-200">
        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
        Offline
    </button>
</div>
