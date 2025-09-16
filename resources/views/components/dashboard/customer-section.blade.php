@props([
    'recentOrders' => [],
    'wishlistItems' => [],
    'recommendedProducts' => []
])

<!-- Customer Dashboard Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Orders -->
    <div class="lg:col-span-2">
        <x-dashboard.activity-list 
            title="Recent Orders" 
            :items="$recentOrders" 
            empty-message="No recent orders"
        />
    </div>

    <!-- Wishlist -->
    <div>
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Wishlist</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($wishlistItems as $item)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <img class="h-12 w-12 rounded-lg object-cover" src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item['vendor'] }}</div>
                                <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">${{ number_format($item['price'], 2) }}</div>
                            </div>
                        </div>
                        <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No items in wishlist</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recommended Products -->
@if(!empty($recommendedProducts))
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recommended for You</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recommendedProducts as $product)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <img class="h-48 w-full object-cover" src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $product['name'] }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product['vendor'] }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $product['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ $product['rating'] }}</span>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xl font-bold text-gray-900 dark:text-white">${{ number_format($product['price'], 2) }}</span>
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
