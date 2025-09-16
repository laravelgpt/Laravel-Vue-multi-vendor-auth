<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use App\Services\PasswordBreachService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name = '';

    public $username = '';

    public $email = '';

    public $phone = '';

    public $country_code = '+1';

    public $address = '';

    public $country = '';

    public $state = '';

    public $city = '';

    public $zip_code = '';

    public $password = '';

    public $password_confirmation = '';

    public $passwordBreachCheck = null;

    public $passwordValidation = null;

    public function mount()
    {
        // Service will be injected when needed
    }

    protected $rules = [
        'name' => 'required|string|max:255|min:2',
        'username' => 'required|string|max:255|min:3|unique:users|regex:/^[a-zA-Z0-9_]+$/',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'nullable|string|max:20',
        'country_code' => 'required|string|max:5',
        'address' => 'nullable|string|max:500',
        'country' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'city' => 'nullable|string|max:100',
        'zip_code' => 'nullable|string|max:20',
        'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
    ];

    protected $messages = [
        'name.required' => 'Please enter your full name.',
        'name.min' => 'Name must be at least 2 characters long.',
        'username.required' => 'Please enter a username.',
        'username.min' => 'Username must be at least 3 characters long.',
        'username.unique' => 'This username is already taken.',
        'username.regex' => 'Username can only contain letters, numbers, and underscores.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already registered.',
        'phone.max' => 'Phone number is too long.',
        'country_code.required' => 'Please select a country code.',
        'address.max' => 'Address is too long.',
        'country.max' => 'Country name is too long.',
        'state.max' => 'State name is too long.',
        'city.max' => 'City name is too long.',
        'zip_code.max' => 'ZIP code is too long.',
        'password.required' => 'Please enter a password.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.',
    ];

    public function updated($propertyName)
    {
        // Trim and normalize data when properties are updated
        if ($propertyName === 'name') {
            $this->name = trim($this->name);
        }
        if ($propertyName === 'username') {
            $this->username = trim($this->username);
        }
        if ($propertyName === 'email') {
            $this->email = strtolower(trim($this->email));
        }
        if ($propertyName === 'phone') {
            $this->phone = trim($this->phone);
        }
        if ($propertyName === 'address') {
            $this->address = trim($this->address);
        }
        if ($propertyName === 'zip_code') {
            $this->zip_code = trim($this->zip_code);
        }

        // Real-time password validation and breach checking
        if ($propertyName === 'password' && !empty($this->password)) {
            $this->performPasswordValidation();
        }

        $this->validateOnly($propertyName);
    }

    public function performPasswordValidation()
    {
        if (empty($this->password)) {
            $this->passwordValidation = null;
            $this->passwordBreachCheck = null;
            return;
        }

        $passwordBreachService = app(PasswordBreachService::class);

        // Perform comprehensive password validation
        $this->passwordValidation = $passwordBreachService->validatePassword($this->password);
        
        // Check for breaches separately for real-time feedback
        $this->passwordBreachCheck = $passwordBreachService->checkPasswordBreach($this->password);
    }

    public function getPasswordStrengthProperty()
    {
        if (empty($this->password)) {
            return [
                'score' => 0,
                'level' => 'none',
                'message' => '',
                'requirements' => []
            ];
        }

        $requirements = [
            'length' => strlen($this->password) >= 8,
            'lowercase' => preg_match('/[a-z]/', $this->password),
            'uppercase' => preg_match('/[A-Z]/', $this->password),
            'number' => preg_match('/\d/', $this->password),
            'special' => preg_match('/[@$!%*?&]/', $this->password),
        ];

        $score = array_sum($requirements);
        $total = count($requirements);

        $level = match (true) {
            $score <= 1 => 'weak',
            $score <= 2 => 'fair',
            $score <= 3 => 'medium',
            $score === $total => 'strong',
            default => 'fair'
        };

        $messages = [
            'weak' => 'Password is too weak',
            'fair' => 'Password is fair',
            'medium' => 'Password is medium strength',
            'strong' => 'Password is strong'
        ];

        return [
            'score' => $score,
            'total' => $total,
            'level' => $level,
            'message' => $messages[$level],
            'requirements' => [
                ['text' => 'At least 8 characters', 'met' => $requirements['length']],
                ['text' => 'Contains lowercase letter', 'met' => $requirements['lowercase']],
                ['text' => 'Contains uppercase letter', 'met' => $requirements['uppercase']],
                ['text' => 'Contains number', 'met' => $requirements['number']],
                ['text' => 'Contains special character (@$!%*?&)', 'met' => $requirements['special']],
            ]
        ];
    }

    public function getPasswordMatchProperty()
    {
        if (empty($this->password_confirmation)) {
            return null;
        }

        return $this->password === $this->password_confirmation;
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => trim($this->name),
            'username' => trim($this->username),
            'email' => strtolower(trim($this->email)),
            'phone' => trim($this->phone),
            'country_code' => $this->country_code,
            'address' => trim($this->address),
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'zip_code' => trim($this->zip_code),
            'password' => Hash::make($this->password),
            'role' => 'customer',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.auth');
    }
}
