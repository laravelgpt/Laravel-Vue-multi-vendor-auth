<?php

namespace App\Http\Livewire\Wholeseller;

use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];

    public $recentOrders = [];

    public $recentCustomers = [];

    public $topProducts = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentOrders();
        $this->loadRecentCustomers();
        $this->loadTopProducts();
    }

    public function loadStats()
    {
        $vendor = auth()->user()->vendor;

        // Mock stats for now - in real app, these would come from actual relationships
        $this->stats = [
            'total_products' => 0, // $vendor ? $vendor->products()->count() : 0,
            'total_orders' => 0, // $vendor ? $vendor->orders()->count() : 0,
            'total_customers' => 0, // $vendor ? $vendor->customers()->count() : 0,
            'monthly_revenue' => 0, // $vendor ? $vendor->orders()->whereMonth('created_at', now()->month)->sum('total') : 0,
        ];
    }

    public function loadRecentOrders()
    {
        $vendor = auth()->user()->vendor;

        // Mock recent orders for now - in real app, these would come from actual relationships
        $this->recentOrders = [
            // Mock data - replace with actual orders when relationships are set up
        ];
    }

    public function loadRecentCustomers()
    {
        $vendor = auth()->user()->vendor;

        // Mock recent customers for now - in real app, these would come from actual relationships
        $this->recentCustomers = [
            // Mock data - replace with actual customers when relationships are set up
        ];
    }

    public function loadTopProducts()
    {
        $vendor = auth()->user()->vendor;

        // Mock top products for now - in real app, these would come from actual relationships
        $this->topProducts = [
            // Mock data - replace with actual products when relationships are set up
        ];
    }

    public function render()
    {
        return view('livewire.wholeseller.dashboard')
            ->layout('layouts.wholeseller', [
                'title' => 'Wholeseller Dashboard',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'url' => route('wholeseller.dashboard')],
                ],
            ]);
    }
}
