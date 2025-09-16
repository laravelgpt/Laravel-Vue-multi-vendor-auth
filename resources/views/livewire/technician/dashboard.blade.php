<x-dashboard.layout 
    title="Technician Dashboard"
    subtitle="Manage your repair orders and track your work"
    :stats="[
        [
            'title' => 'Total Repairs',
            'value' => $stats['total_repairs'] ?? 0,
            'icon' => 'heroicon-o-wrench-screwdriver',
            'color' => 'blue'
        ],
        [
            'title' => 'Active Repairs',
            'value' => $stats['active_repairs'] ?? 0,
            'icon' => 'heroicon-o-clock',
            'color' => 'orange'
        ],
        [
            'title' => 'Completed Today',
            'value' => $stats['completed_today'] ?? 0,
            'icon' => 'heroicon-o-check-circle',
            'color' => 'green'
        ],
        [
            'title' => 'Pending Repairs',
            'value' => $stats['pending_repairs'] ?? 0,
            'icon' => 'heroicon-o-exclamation-triangle',
            'color' => 'red'
        ]
    ]"
    :quickActions="[
        [
            'title' => 'New Repair Order',
            'description' => 'Start a new repair job',
            'url' => '#',
            'icon' => 'heroicon-o-plus',
            'color' => 'bg-green-500'
        ],
        [
            'title' => 'View Active Repairs',
            'description' => 'Check your current repair jobs',
            'url' => route('technician.active-repairs'),
            'icon' => 'heroicon-o-eye',
            'color' => 'bg-blue-500'
        ],
        [
            'title' => 'Parts Inventory',
            'description' => 'Check available parts and tools',
            'url' => route('technician.parts.index'),
            'icon' => 'heroicon-o-cog',
            'color' => 'bg-purple-500'
        ]
    ]"
>
    <x-slot name="customActions">
        <x-dashboard.technician-status 
            :currentStatus="$technicianStatus"
            updateMethod="updateStatus"
        />
    </x-slot>

    <x-slot name="mainContent">
        <x-dashboard.technician-section 
            :activeRepairs="$activeRepairs"
            :urgentRepairs="$urgentRepairs"
            :recentRepairs="$recentRepairs"
            :technicianStatus="$technicianStatus"
        />
    </x-slot>
</x-dashboard.layout>