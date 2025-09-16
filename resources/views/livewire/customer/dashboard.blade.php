<x-dashboard.layout 
    title="Welcome back, {{ auth()->user()->name }}!"
    subtitle="Here's what's happening with your account"
    :stats="[
        [
            'title' => 'Total Orders',
            'value' => $stats['total_orders'] ?? 0,
            'icon' => 'heroicon-o-shopping-cart',
            'color' => 'blue'
        ],
        [
            'title' => 'Pending Orders',
            'value' => $stats['pending_orders'] ?? 0,
            'icon' => 'heroicon-o-clock',
            'color' => 'orange'
        ],
        [
            'title' => 'Completed Orders',
            'value' => $stats['completed_orders'] ?? 0,
            'icon' => 'heroicon-o-chart-bar',
            'color' => 'green'
        ],
        [
            'title' => 'Total Spent',
            'value' => '$' . number_format($stats['total_spent'] ?? 0, 2),
            'icon' => 'heroicon-o-currency-dollar',
            'color' => 'purple'
        ]
    ]"
    :quickActions="[
        [
            'title' => 'Browse Products',
            'description' => 'Discover new products from our vendors',
            'url' => '#',
            'icon' => 'heroicon-o-eye',
            'color' => 'bg-blue-500'
        ],
        [
            'title' => 'My Orders',
            'description' => 'Track your order history and status',
            'url' => '#',
            'icon' => 'heroicon-o-shopping-cart',
            'color' => 'bg-green-500'
        ],
        [
            'title' => 'Wishlist',
            'description' => 'View your saved items',
            'url' => '#',
            'icon' => 'heroicon-o-heart',
            'color' => 'bg-purple-500'
        ]
    ]"
>
    <x-slot name="mainContent">
        <x-dashboard.customer-section 
            :recentOrders="$recentOrders"
            :wishlistItems="$wishlistItems"
            :recommendedProducts="$recommendedProducts"
        />
    </x-slot>
</x-dashboard.layout>