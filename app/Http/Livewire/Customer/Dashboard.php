<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];

    public $recentOrders = [];

    public $recommendedProducts = [];

    public $wishlistItems = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentOrders();
        $this->loadRecommendedProducts();
        $this->loadWishlistItems();
    }

    public function loadStats()
    {
        $user = auth()->user();

        // Mock stats for now - in real app, these would come from actual order relationships
        $this->stats = [
            'total_orders' => 0, // $user->orders()->count(),
            'pending_orders' => 0, // $user->orders()->where('status', 'pending')->count(),
            'completed_orders' => 0, // $user->orders()->where('status', 'completed')->count(),
            'total_spent' => 0, // $user->orders()->where('status', 'completed')->sum('total'),
        ];
    }

    public function loadRecentOrders()
    {
        $user = auth()->user();

        // Mock recent orders for now - in real app, these would come from actual order relationships
        $this->recentOrders = [
            // Mock data - replace with actual orders when relationships are set up
        ];
    }

    public function loadRecommendedProducts()
    {
        // Mock recommended products - in real app, this would be based on user preferences, purchase history, etc.
        $this->recommendedProducts = [
            [
                'id' => 1,
                'name' => 'Premium Smartphone',
                'price' => 599.99,
                'image' => '/images/placeholder-product.jpg',
                'vendor' => 'TechStore Pro',
                'rating' => 4.5,
            ],
            [
                'id' => 2,
                'name' => 'Wireless Headphones',
                'price' => 199.99,
                'image' => '/images/placeholder-product.jpg',
                'vendor' => 'Audio Solutions',
                'rating' => 4.8,
            ],
            [
                'id' => 3,
                'name' => 'Smart Watch',
                'price' => 299.99,
                'image' => '/images/placeholder-product.jpg',
                'vendor' => 'Wearable Tech',
                'rating' => 4.3,
            ],
        ];
    }

    public function loadWishlistItems()
    {
        $user = auth()->user();

        // Mock wishlist items - in real app, this would come from a wishlist table
        $this->wishlistItems = [
            [
                'id' => 1,
                'name' => 'Gaming Laptop',
                'price' => 1299.99,
                'image' => '/images/placeholder-product.jpg',
                'vendor' => 'Gaming Gear Co',
            ],
            [
                'id' => 2,
                'name' => 'Mechanical Keyboard',
                'price' => 149.99,
                'image' => '/images/placeholder-product.jpg',
                'vendor' => 'Keyboard Masters',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.customer.dashboard')
            ->layout('layouts.customer', [
                'title' => 'Customer Dashboard',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'url' => route('dashboard')],
                ],
            ]);
    }
}
