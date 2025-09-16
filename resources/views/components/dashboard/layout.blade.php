@props([
    'title' => 'Dashboard',
    'subtitle' => 'Welcome to your dashboard',
    'showRefresh' => true,
    'refreshMethod' => 'loadStats',
    'customActions' => null,
    'stats' => [],
    'quickActions' => [],
    'mainContent' => null,
    'sidebarContent' => null,
    'loading' => false,
    'error' => null,
    'errorTitle' => 'Something went wrong',
    'errorMessage' => 'An error occurred while loading the data. Please try again.',
    'retryAction' => 'loadStats'
])

<div>
    <!-- Dashboard Header -->
    <x-dashboard.header 
        :title="$title" 
        :subtitle="$subtitle" 
        :showRefresh="$showRefresh" 
        :refreshMethod="$refreshMethod"
    >
        <x-slot name="customActions">
            {{ $customActions }}
        </x-slot>
    </x-dashboard.header>

    <!-- Error State -->
    @if($error)
        <x-dashboard.error-state 
            :title="$errorTitle"
            :message="$errorMessage"
            :retryAction="$retryAction"
        />
    @else
        <!-- Stats Grid -->
        @if(!empty($stats))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach($stats as $stat)
                    <x-dashboard.stats-card 
                        :title="$stat['title']" 
                        :value="$stat['value']" 
                        :icon="$stat['icon']" 
                        :color="$stat['color']" 
                        :change="$stat['change'] ?? null"
                        :changeType="$stat['changeType'] ?? 'positive'"
                        :loading="$loading"
                    />
                @endforeach
            </div>
        @endif

        <!-- Quick Actions -->
        @if(!empty($quickActions))
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
                <x-dashboard.quick-actions :actions="$quickActions" />
            </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 {{ $sidebarContent ? 'lg:grid-cols-3' : '' }} gap-8">
            <!-- Main Content -->
            <div class="{{ $sidebarContent ? 'lg:col-span-2' : '' }}">
                @if($loading)
                    <x-dashboard.loading-state message="Loading dashboard content..." />
                @else
                    {{ $mainContent }}
                @endif
            </div>

            <!-- Sidebar Content -->
            @if($sidebarContent)
                <div>
                    @if($loading)
                        <x-dashboard.loading-state message="Loading sidebar..." size="small" />
                    @else
                        {{ $sidebarContent }}
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            {{ session('message') }}
        </div>
    @endif

    <!-- Loading Overlay -->
    @if($loading)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
                <svg class="w-6 h-6 text-indigo-600 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-900 dark:text-white">Loading...</span>
            </div>
        </div>
    @endif
</div>
