<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use App\Models\Vendor;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];

    public $recentUsers = [];

    public $recentVendors = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentUsers();
        $this->loadRecentVendors();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_users' => (int) User::count(),
            'active_users' => (int) User::where('is_active', true)->count(),
            'admin_users' => (int) User::where('is_admin', true)->count(),
            'total_vendors' => (int) Vendor::count(),
            'active_vendors' => (int) Vendor::where('is_active', true)->count(),
            'new_users_today' => (int) User::whereDate('created_at', today())->count(),
            'new_vendors_today' => (int) Vendor::whereDate('created_at', today())->count(),
        ];
    }

    public function loadRecentUsers()
    {
        $this->recentUsers = User::latest()
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => (int) $user->id,
                    'name' => (string) $user->name,
                    'email' => (string) $user->email,
                    'role' => (string) ($user->role ?? 'user'),
                    'is_active' => (bool) ($user->is_active ?? true),
                    'created_at' => (string) $user->created_at->diffForHumans(),
                    'avatar' => (string) 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7C3AED&background=EBF4FF',
                ];
            })
            ->toArray();
    }

    public function loadRecentVendors()
    {
        $this->recentVendors = Vendor::latest()
            ->limit(5)
            ->get()
            ->map(function ($vendor) {
                return [
                    'id' => (int) $vendor->id,
                    'name' => (string) $vendor->name,
                    'email' => (string) $vendor->email,
                    'business_type' => (string) $vendor->business_type,
                    'is_active' => (bool) ($vendor->is_active ?? true),
                    'is_verified' => (bool) ($vendor->is_verified ?? false),
                    'created_at' => (string) $vendor->created_at->diffForHumans(),
                    'logo' => (string) $vendor->getLogoUrl(),
                ];
            })
            ->toArray();
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => ! $user->is_active]);

        $this->loadStats();
        $this->loadRecentUsers();

        session()->flash('message', "User {$user->name} status updated successfully.");
    }

    public function toggleVendorStatus($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $vendor->update(['is_active' => ! $vendor->is_active]);

        $this->loadStats();
        $this->loadRecentVendors();

        session()->flash('message', "Vendor {$vendor->name} status updated successfully.");
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin', [
                'title' => 'Admin Dashboard',
                'description' => 'Manage users, vendors, and system settings',
            ]);
    }
}
