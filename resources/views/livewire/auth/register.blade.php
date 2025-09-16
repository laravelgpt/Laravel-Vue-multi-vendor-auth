<div class="auth-container" x-data="modernRegistration()">
    <div class="auth-card max-w-4xl animate-fade-in">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-logo animate-bounce-in">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h1 class="auth-title animate-slide-in-up animate-delay-100">Complete Registration</h1>
            <p class="auth-subtitle animate-slide-in-up animate-delay-200">
                Create your account with all details or 
                <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                    sign in to your existing account
                </a>
            </p>
        </div>

        <!-- Registration Form -->
        <form class="auth-form" wire:submit.prevent="register" @submit="handleFormSubmit($event, 'register')">
            <!-- Personal Information Section -->
            <div class="mb-8 animate-slide-in-up animate-delay-300">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div class="form-group animate-slide-in-left animate-delay-100">
                        <label class="form-label" for="name">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Full Name
                        </label>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            autocomplete="name" 
                            required 
                            wire:model="name"
                            class="form-input @error('name') error @enderror" 
                            placeholder="Enter your full name"
                            @blur="validateField($event.target)"
                            @input="clearFieldError($event.target)">
                        @error('name') 
                            <div class="form-error">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div> 
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="form-group animate-slide-in-left animate-delay-200">
                        <label class="form-label" for="username">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Username
                        </label>
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            autocomplete="username" 
                            required 
                            wire:model="username"
                            class="form-input @error('username') error @enderror" 
                            placeholder="Choose a username"
                            @blur="validateField($event.target)"
                            @input="clearFieldError($event.target)">
                        @error('username') 
                            <div class="form-error">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div> 
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group animate-slide-in-left animate-delay-300">
                    <label class="form-label" for="email">
                        <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email Address
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        wire:model="email"
                        class="form-input @error('email') error @enderror" 
                        placeholder="Enter your email address"
                        @blur="validateField($event.target)"
                        @input="clearFieldError($event.target)">
                    @error('email') 
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="form-group animate-slide-in-left animate-delay-400">
                    <label class="form-label" for="phone">
                        <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Phone Number
                    </label>
                    <div class="flex">
                        <div class="relative w-32">
                            <select wire:model="country_code" class="form-input rounded-r-none border-r-0">
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+44">🇬🇧 +44</option>
                                <option value="+91">🇮🇳 +91</option>
                                <option value="+49">🇩🇪 +49</option>
                                <option value="+33">🇫🇷 +33</option>
                                <option value="+86">🇨🇳 +86</option>
                                <option value="+81">🇯🇵 +81</option>
                                <option value="+82">🇰🇷 +82</option>
                                <option value="+55">🇧🇷 +55</option>
                                <option value="+61">🇦🇺 +61</option>
                            </select>
                        </div>
                        <input 
                            id="phone" 
                            name="phone" 
                            type="tel" 
                            autocomplete="tel" 
                            wire:model="phone"
                            class="form-input rounded-l-none @error('phone') error @enderror" 
                            placeholder="Enter your phone number"
                            @blur="validateField($event.target)"
                            @input="clearFieldError($event.target)">
                    </div>
                    @error('phone') 
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>
            </div>

            <!-- Location Information Section -->
            <div class="mb-8 animate-slide-in-up animate-delay-500">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Location Information
                </h3>

                <!-- Address -->
                <div class="form-group">
                    <label class="form-label" for="address">
                        <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Street Address
                    </label>
                    <input 
                        id="address" 
                        name="address" 
                        type="text" 
                        autocomplete="street-address" 
                        wire:model="address"
                        class="form-input @error('address') error @enderror" 
                        placeholder="Enter your street address"
                        @blur="validateField($event.target)"
                        @input="clearFieldError($event.target)">
                    @error('address') 
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- Country and State -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Country -->
                    <div class="form-group">
                        <label class="form-label" for="country">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Country
                        </label>
                        <select wire:model="country" class="form-input @error('country') error @enderror">
                            <option value="">Select Country</option>
                            <option value="United States">🇺🇸 United States</option>
                            <option value="Canada">🇨🇦 Canada</option>
                            <option value="United Kingdom">🇬🇧 United Kingdom</option>
                            <option value="Australia">🇦🇺 Australia</option>
                            <option value="Germany">🇩🇪 Germany</option>
                            <option value="France">🇫🇷 France</option>
                            <option value="India">🇮🇳 India</option>
                            <option value="China">🇨🇳 China</option>
                            <option value="Japan">🇯🇵 Japan</option>
                            <option value="Brazil">🇧🇷 Brazil</option>
                        </select>
                        @error('country') 
                            <div class="form-error">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div> 
                        @enderror
                    </div>

                    <!-- State -->
                    <div class="form-group">
                        <label class="form-label" for="state">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            State/Province
                        </label>
                        <input 
                            id="state" 
                            name="state" 
                            type="text" 
                            wire:model="state"
                            class="form-input @error('state') error @enderror" 
                            placeholder="Enter your state or province"
                            @blur="validateField($event.target)"
                            @input="clearFieldError($event.target)">
                        @error('state') 
                            <div class="form-error">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div> 
                        @enderror
                    </div>
                </div>

                <!-- City and ZIP Code -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- City -->
                    <div class="form-group">
                        <label class="form-label" for="city">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            City
                        </label>
                        <input 
                            id="city" 
                            name="city" 
                            type="text" 
                            wire:model="city"
                            class="form-input @error('city') error @enderror" 
                            placeholder="Enter your city"
                            @blur="validateField($event.target)"
                            @input="clearFieldError($event.target)">
                        @error('city') 
                            <div class="form-error">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div> 
                        @enderror
                    </div>

                    <!-- ZIP Code -->
                    <div class="form-group">
                        <label class="form-label" for="zip_code">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            ZIP/Postal Code
                        </label>
                        <input 
                            id="zip_code" 
                            name="zip_code" 
                            type="text" 
                            wire:model="zip_code"
                            class="form-input @error('zip_code') error @enderror" 
                            placeholder="Enter your ZIP/postal code"
                            @blur="validateField($event.target)"
                            @input="clearFieldError($event.target)">
                        @error('zip_code') 
                            <div class="form-error">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div> 
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Security
                </h3>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">
                        <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            wire:model.live="password"
                            class="form-input @error('password') error @enderror pr-12" 
                            placeholder="Create a strong password"
                            @blur="validateField($event.target)"
                            @input="clearFieldError($event.target)"
                            x-ref="passwordInput">
                        <button 
                            type="button" 
                            @click="togglePasswordVisibility('passwordInput')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!showPassword">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="showPassword">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    
        <!-- Real-time Password Validation & Breach Check -->
        @if($password)
            <div class="mt-3 space-y-3">
                <!-- Password Breach Alert -->
                @if($passwordBreachCheck && $passwordBreachCheck['breached'])
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl animate-fade-in">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-1">
                                    Password Compromised
                                </h4>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    {{ $passwordBreachCheck['message'] }}
                                </p>
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                    This password has been found in {{ $passwordBreachCheck['count'] }} data breach{{ $passwordBreachCheck['count'] > 1 ? 'es' : '' }}.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($passwordBreachCheck && !$passwordBreachCheck['breached'])
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-green-700 dark:text-green-300">
                                {{ $passwordBreachCheck['message'] }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Password Strength Indicator -->
                @if($passwordValidation)
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 animate-fade-in">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 sm:mb-0">Password Strength</span>
                            <div class="flex items-center">
                                <span class="text-sm font-semibold mr-2
                                    @if($passwordValidation['strength']['level'] === 'very-weak') text-red-600
                                    @elseif($passwordValidation['strength']['level'] === 'weak') text-red-500
                                    @elseif($passwordValidation['strength']['level'] === 'fair') text-orange-500
                                    @elseif($passwordValidation['strength']['level'] === 'good') text-yellow-500
                                    @elseif($passwordValidation['strength']['level'] === 'strong') text-green-500
                                    @endif">
                                    {{ ucfirst(str_replace('-', ' ', $passwordValidation['strength']['level'])) }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $passwordValidation['strength']['score'] }}/{{ $passwordValidation['strength']['maxScore'] }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
                            <div class="h-2 rounded-full transition-all duration-500 ease-out
                                @if($passwordValidation['strength']['level'] === 'very-weak') bg-red-500
                                @elseif($passwordValidation['strength']['level'] === 'weak') bg-red-400
                                @elseif($passwordValidation['strength']['level'] === 'fair') bg-orange-500
                                @elseif($passwordValidation['strength']['level'] === 'good') bg-yellow-500
                                @elseif($passwordValidation['strength']['level'] === 'strong') bg-green-500
                                @endif"
                                style="width: {{ ($passwordValidation['strength']['score'] / $passwordValidation['strength']['maxScore']) * 100 }}%">
                            </div>
                        </div>
                        
                        <!-- Requirements Checklist -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                            @foreach($passwordValidation['strength']['requirements'] as $requirement)
                                <div class="flex items-center text-sm">
                                    @if($requirement['met'])
                                        <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-green-600 dark:text-green-400">{{ $requirement['text'] }}</span>
                                    @else
                                        <svg class="w-4 h-4 mr-2 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="text-red-600 dark:text-red-400">{{ $requirement['text'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Recommendations -->
                        @if(!empty($passwordValidation['recommendations']))
                            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <h5 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Recommendations:</h5>
                                <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                                    @foreach($passwordValidation['recommendations'] as $recommendation)
                                        <li class="flex items-start">
                                            <svg class="w-3 h-3 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $recommendation }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Overall Validation Status -->
                        <div class="mt-3 flex items-center justify-center">
                            @if($passwordValidation['valid'])
                                <div class="flex items-center text-green-600 dark:text-green-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Password is secure</span>
                                </div>
                            @else
                                <div class="flex items-center text-red-600 dark:text-red-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Password needs improvement</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif
                    
                    @error('password') 
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">
                        <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Confirm Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            wire:model.live="password_confirmation"
                            class="form-input @error('password_confirmation') error @enderror pr-12" 
                            placeholder="Confirm your password"
                            @blur="validateField($event.target)"
                            @input="clearFieldError($event.target)"
                            x-ref="passwordConfirmationInput">
                        <button 
                            type="button" 
                            @click="togglePasswordVisibility('passwordConfirmationInput')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!showPasswordConfirmation">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="showPasswordConfirmation">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Real-time Password Match Indicator -->
                    @if($password_confirmation)
                        <div class="mt-2 flex items-center text-sm">
                            @if($this->passwordMatch === true)
                                <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-green-600 dark:text-green-400 font-medium">Passwords match</span>
                            @elseif($this->passwordMatch === false)
                                <svg class="w-4 h-4 mr-2 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="text-red-600 dark:text-red-400 font-medium">Passwords do not match</span>
                            @endif
                        </div>
                    @endif
                    
                    @error('password_confirmation') 
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="btn-modern w-full animate-slide-in-up animate-delay-600"
                :disabled="isLoading"
                wire:loading.attr="disabled">
                <span class="flex items-center justify-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <div class="loading-spinner" wire:loading></div>
                    <span wire:loading.remove>Complete Registration</span>
                    <span wire:loading class="loading-dots">Creating Account</span>
                </span>
            </button>
        </form>
    </div>
</div>

<script>
function modernRegistration() {
    return {
        showPassword: false,
        showPasswordConfirmation: false,
        isLoading: false,
        passwordStrength: {
            show: false,
            width: 0,
            color: 'bg-gray-300',
            textColor: 'text-gray-600',
            text: '',
            requirements: []
        },
        
        init() {
            // Auto-focus first input
            this.$nextTick(() => {
                const firstInput = this.$el.querySelector('input:not([type="hidden"]):not([disabled])');
                if (firstInput) {
                    firstInput.focus();
                }
            });
        },
        
        togglePasswordVisibility(inputRef) {
            const input = this.$refs[inputRef];
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
                if (inputRef === 'passwordInput') {
                    this.showPassword = !this.showPassword;
                } else {
                    this.showPasswordConfirmation = !this.showPasswordConfirmation;
                }
            }
        },
        
        checkPasswordStrength(password) {
            if (!password) {
                this.passwordStrength.show = false;
                return;
            }
            
            this.passwordStrength.show = true;
            
            const requirements = [
                { text: 'At least 8 characters', met: password.length >= 8 },
                { text: 'Contains lowercase letter', met: /[a-z]/.test(password) },
                { text: 'Contains uppercase letter', met: /[A-Z]/.test(password) },
                { text: 'Contains number', met: /\d/.test(password) },
                { text: 'Contains special character', met: /[!@#$%^&*(),.?":{}|<>]/.test(password) }
            ];
            
            this.passwordStrength.requirements = requirements;
            
            const score = requirements.filter(r => r.met).length;
            const percentage = (score / requirements.length) * 100;
            
            this.passwordStrength.width = percentage;
            
            if (score < 2) {
                this.passwordStrength.color = 'bg-red-500';
                this.passwordStrength.textColor = 'text-red-600';
                this.passwordStrength.text = 'Weak';
            } else if (score < 4) {
                this.passwordStrength.color = 'bg-yellow-500';
                this.passwordStrength.textColor = 'text-yellow-600';
                this.passwordStrength.text = 'Medium';
            } else {
                this.passwordStrength.color = 'bg-green-500';
                this.passwordStrength.textColor = 'text-green-600';
                this.passwordStrength.text = 'Strong';
            }
        },
        
        handleFormSubmit(event, type) {
            if (window.handleFormSubmit) {
                return window.handleFormSubmit(event, type);
            }
            return true;
        },
        
        validateField(field) {
            if (window.validateField) {
                return window.validateField(field);
            }
            return true;
        },
        
        clearFieldError(field) {
            if (window.clearFieldError) {
                window.clearFieldError(field);
            }
        }
    }
}
</script>