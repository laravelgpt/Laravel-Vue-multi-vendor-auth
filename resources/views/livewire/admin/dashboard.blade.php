<div>
    <x-dashboard.layout 
        title="Admin Dashboard"
        subtitle="Manage your multi-vendor platform"
        :stats="[
            [
                'title' => 'Total Users',
                'value' => $stats['total_users'] ?? 0,
                'icon' => 'heroicon-o-users',
                'color' => 'blue'
            ],
            [
                'title' => 'Active Users',
                'value' => $stats['active_users'] ?? 0,
                'icon' => 'heroicon-o-chart-bar',
                'color' => 'green'
            ],
            [
                'title' => 'Total Vendors',
                'value' => $stats['total_vendors'] ?? 0,
                'icon' => 'heroicon-o-building-office',
                'color' => 'purple'
            ],
            [
                'title' => 'New Today',
                'value' => ($stats['new_users_today'] ?? 0) + ($stats['new_vendors_today'] ?? 0),
                'icon' => 'heroicon-o-clock',
                'color' => 'orange'
            ]
        ]"
    >
        <x-slot name="mainContent">
            <x-dashboard.admin-section 
                :recentUsers="$recentUsers"
                :recentVendors="$recentVendors"
                :showActions="true"
            />
        </x-slot>
    </x-dashboard.layout>
</div>