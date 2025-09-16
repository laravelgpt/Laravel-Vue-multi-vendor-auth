@props([
    'recentUsers' => [],
    'recentVendors' => [],
    'showActions' => true
])

<!-- Admin Dashboard Content -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Users -->
    <x-dashboard.activity-list 
        title="Recent Users" 
        :items="$recentUsers" 
        empty-message="No recent users"
        :show-actions="$showActions"
        action-method="toggleUserStatus"
    />
    
    <!-- Recent Vendors -->
    <x-dashboard.activity-list 
        title="Recent Vendors" 
        :items="$recentVendors" 
        empty-message="No recent vendors"
        :show-actions="$showActions"
        action-method="toggleVendorStatus"
    />
</div>
