<?php

namespace App\Http\Livewire\Technician;

use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];

    public $activeRepairs = [];

    public $recentRepairs = [];

    public $urgentRepairs = [];

    public $technicianStatus = 'available';

    public function mount()
    {
        $this->loadStats();
        $this->loadActiveRepairs();
        $this->loadRecentRepairs();
        $this->loadUrgentRepairs();
    }

    public function loadStats()
    {
        // Mock stats for now - in real app, these would come from actual repair relationships
        $this->stats = [
            'total_repairs' => 0, // $user->repairs()->count(),
            'active_repairs' => 0, // $user->repairs()->where('status', 'in_progress')->count(),
            'completed_today' => 0, // $user->repairs()->where('status', 'completed')->whereDate('completed_at', today())->count(),
            'pending_repairs' => 0, // $user->repairs()->where('status', 'pending')->count(),
        ];
    }

    public function loadActiveRepairs()
    {
        // Mock active repairs for now - in real app, these would come from actual repair relationships
        $this->activeRepairs = [
            // Mock data - replace with actual repairs when relationships are set up
        ];
    }

    public function loadRecentRepairs()
    {
        // Mock recent repairs for now - in real app, these would come from actual repair relationships
        $this->recentRepairs = [
            // Mock data - replace with actual repairs when relationships are set up
        ];
    }

    public function loadUrgentRepairs()
    {
        // Mock urgent repairs for now - in real app, these would come from actual repair relationships
        $this->urgentRepairs = [
            // Mock data - replace with actual repairs when relationships are set up
        ];
    }

    public function updateStatus($status)
    {
        $this->technicianStatus = $status;
        // In real app, this would update the technician's status in the database
        session()->flash('message', 'Status updated successfully!');
    }

    public function render()
    {
        return view('livewire.technician.dashboard')
            ->layout('layouts.technician', [
                'title' => 'Technician Dashboard',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'url' => route('technician.dashboard')],
                ],
            ]);
    }
}
