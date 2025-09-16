<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    public $name;

    public $email;

    public $current_password;

    public $password;

    public $password_confirmation;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore(Auth::user()->id)],
        ];
    }

    protected function passwordRules()
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfile()
    {
        $this->validate();

        Auth::user()->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('message', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        $this->validate($this->passwordRules());

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('message', 'Password updated successfully.');
    }

    public function render()
    {
        $breadcrumbs = [
            [
                'title' => 'Profile',
                'href' => route('profile.edit'),
            ],
        ];

        return view('livewire.profile', compact('breadcrumbs'))
            ->layout('layouts.app', ['breadcrumbs' => $breadcrumbs]);
    }
}
