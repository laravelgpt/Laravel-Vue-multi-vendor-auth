@props([
    'recentOrders' => [],
    'recentCustomers' => [],
    'topProducts' => []
])

<!-- Wholeseller Dashboard Content -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Orders -->
    <x-dashboard.activity-list 
        title="Recent Orders" 
        :items="$recentOrders" 
        empty-message="No recent orders"
    />
    
    <!-- Recent Customers -->
    <x-dashboard.activity-list 
        title="Recent Customers" 
        :items="$recentCustomers" 
        empty-message="No recent customers"
    />
</div>

<!-- Top Products -->
@if(!empty($topProducts))
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Top Selling Products</h2>
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Best Performers</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($topProducts as $product)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <img class="h-12 w-12 rounded-lg object-cover" src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($product['price'], 2) }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product['sales_count'] }} sales</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
