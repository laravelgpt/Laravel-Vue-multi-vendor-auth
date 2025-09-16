<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $breadcrumbs = [
            [
                'title' => 'Dashboard',
                'href' => route('dashboard'),
            ],
        ];

        return view('livewire.dashboard', compact('breadcrumbs'))
            ->layout('layouts.app', ['breadcrumbs' => $breadcrumbs]);
    }
}
