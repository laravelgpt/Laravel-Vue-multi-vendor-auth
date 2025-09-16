<div class="auth-container" x-data="modernAuth()">
    <div class="auth-card animate-fade-in">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-logo animate-bounce-in">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="auth-title animate-slide-in-up animate-delay-100">Welcome Back</h1>
            <p class="auth-subtitle animate-slide-in-up animate-delay-200">
                Sign in to your account or 
                <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                    create a new account
                </a>
            </p>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-nav">
            <button class="tab-button active" @click="activeTab = 'login'">Login</button>
            <button class="tab-button" @click="activeTab = 'otp'">OTP Login</button>
        </div>

        <!-- Login Form -->
        <div x-show="activeTab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <form class="auth-form" wire:submit.prevent="login" @submit="handleFormSubmit($event, 'login')">
                <div class="form-group animate-slide-in-left animate-delay-100">
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

                <div class="form-group animate-slide-in-left animate-delay-200">
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
                            :type="showPassword ? 'text' : 'password'" 
                            autocomplete="current-password" 
                            required 
                            wire:model="password"
                            class="form-input @error('password') error @enderror pr-12" 
                            placeholder="Enter your password"
                            @blur="validateField($event.target)"
                            @input="clearFieldError($event.target)">
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword"
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
                    @error('password') 
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6 animate-slide-in-up animate-delay-300">
                    <div class="flex items-center">
                        <input 
                            id="remember-me" 
                            name="remember-me" 
                            type="checkbox" 
                            wire:model="remember"
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded transition-colors">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm space-y-1">
                        @if (Route::has('password.request'))
                            <div>
                                <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
                                    Forgot password?
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="btn-modern animate-slide-in-up animate-delay-400"
                    :disabled="isLoading"
                    wire:loading.attr="disabled">
                    <span class="flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <div class="loading-spinner" wire:loading></div>
                        <span wire:loading.remove>Sign in</span>
                        <span wire:loading class="loading-dots">Signing in</span>
                    </span>
                </button>
            </form>
        </div>

        <!-- OTP Login Form -->
        <div x-show="activeTab === 'otp'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <form class="auth-form" wire:submit.prevent="sendOtp" @submit="handleFormSubmit($event, 'otp')">
                <div class="form-group animate-slide-in-left animate-delay-100">
                    <label class="form-label" for="otp-email">
                        <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email Address
                    </label>
                    <input 
                        id="otp-email" 
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

                <button 
                    type="submit" 
                    class="btn-modern animate-slide-in-up animate-delay-200"
                    :disabled="isLoading"
                    wire:loading.attr="disabled">
                    <span class="flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div class="loading-spinner" wire:loading></div>
                        <span wire:loading.remove>Send OTP</span>
                        <span wire:loading class="loading-dots">Sending OTP</span>
                    </span>
                </button>
            </form>
        </div>

        <!-- Social Login -->
        <div class="social-login animate-slide-in-up animate-delay-500">
            <div class="social-divider">
                <span>Or continue with</span>
            </div>
            <div class="social-buttons">
                <button 
                    class="btn-social btn-google animate-slide-in-left animate-delay-100" 
                    data-tooltip="Continue with Google"
                    @click="handleSocialLogin('google')">
                    <svg viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                </button>
                <button 
                    class="btn-social btn-github animate-slide-in-left animate-delay-200" 
                    data-tooltip="Continue with GitHub"
                    @click="handleSocialLogin('github')">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                </button>
                <button 
                    class="btn-social btn-facebook animate-slide-in-left animate-delay-300" 
                    data-tooltip="Continue with Facebook"
                    @click="handleSocialLogin('facebook')">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </button>
                <button 
                    class="btn-social btn-apple animate-slide-in-left animate-delay-400" 
                    data-tooltip="Continue with Apple"
                    @click="handleSocialLogin('apple')">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function modernAuth() {
    return {
        activeTab: 'login',
        showPassword: false,
        isLoading: false,
        
        init() {
            // Auto-focus first input
            this.$nextTick(() => {
                const firstInput = this.$el.querySelector('input:not([type="hidden"]):not([disabled])');
                if (firstInput) {
                    firstInput.focus();
                }
            });
        },
        
        handleFormSubmit(event, type) {
            if (window.handleFormSubmit) {
                return window.handleFormSubmit(event, type);
            }
            return true;
        },
        
        handleSocialLogin(provider) {
            if (window.handleSocialLogin) {
                window.handleSocialLogin(provider);
            } else {
                window.location.href = `/auth/social/${provider}`;
            }
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