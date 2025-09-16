<x-dashboard.layout 
    title="Wholeseller Dashboard"
    subtitle="Manage your business and track performance"
    :stats="[
        [
            'title' => 'Total Products',
            'value' => $stats['total_products'] ?? 0,
            'icon' => 'heroicon-o-chart-bar',
            'color' => 'blue'
        ],
        [
            'title' => 'Total Orders',
            'value' => $stats['total_orders'] ?? 0,
            'icon' => 'heroicon-o-shopping-cart',
            'color' => 'green'
        ],
        [
            'title' => 'Total Customers',
            'value' => $stats['total_customers'] ?? 0,
            'icon' => 'heroicon-o-users',
            'color' => 'purple'
        ],
        [
            'title' => 'Monthly Revenue',
            'value' => '$' . number_format($stats['monthly_revenue'] ?? 0, 2),
            'icon' => 'heroicon-o-currency-dollar',
            'color' => 'orange'
        ]
    ]"
    :quickActions="[
        [
            'title' => 'Add Product',
            'description' => 'Add a new product to your inventory',
            'url' => '#',
            'icon' => 'heroicon-o-plus',
            'color' => 'bg-green-500'
        ],
        [
            'title' => 'View Orders',
            'description' => 'Manage and track your orders',
            'url' => route('wholeseller.orders.index'),
            'icon' => 'heroicon-o-eye',
            'color' => 'bg-blue-500'
        ],
        [
            'title' => 'Customer Management',
            'description' => 'View and manage your customers',
            'url' => route('wholeseller.customers.index'),
            'icon' => 'heroicon-o-cog',
            'color' => 'bg-purple-500'
        ]
    ]"
>
    <x-slot name="mainContent">
        <x-dashboard.wholeseller-section 
            :recentOrders="$recentOrders"
            :recentCustomers="$recentCustomers"
            :topProducts="$topProducts"
        />
    </x-slot>
</x-dashboard.layout>