@props([
    'activeRepairs' => [],
    'urgentRepairs' => [],
    'recentRepairs' => [],
    'technicianStatus' => 'available'
])

<!-- Technician Dashboard Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Active Repairs -->
    <div class="lg:col-span-2">
        <x-dashboard.activity-list 
            title="Active Repairs" 
            :items="$activeRepairs" 
            empty-message="No active repairs"
        />
    </div>

    <!-- Urgent Repairs -->
    <div>
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Urgent Repairs</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($urgentRepairs as $repair)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $repair['device'] ?? 'Unknown Device' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $repair['issue'] ?? 'Unknown Issue' }}</div>
                                <div class="text-xs text-red-600 dark:text-red-400">High Priority</div>
                            </div>
                        </div>
                        <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No urgent repairs</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Repairs -->
@if(!empty($recentRepairs))
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Repairs</h2>
        <x-dashboard.activity-list 
            title="Recent Repair History" 
            :items="$recentRepairs" 
            empty-message="No recent repairs"
        />
    </div>
@endif
