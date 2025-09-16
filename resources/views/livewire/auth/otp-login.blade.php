<div class="auth-container" x-data="modernOTP()">
    <div class="auth-card animate-fade-in">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-logo animate-bounce-in">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="auth-title animate-slide-in-up animate-delay-100">
                @if($step === 'email')
                    OTP Login
                @else
                    Enter Verification Code
                @endif
            </h1>
            <p class="auth-subtitle animate-slide-in-up animate-delay-200">
                @if($step === 'email')
                    Enter your email to receive a one-time password
                @else
                    We've sent a 6-digit code to {{ $email }}
                @endif
            </p>
        </div>
        
        @if($step === 'email')
            <form class="auth-form" wire:submit.prevent="sendOtp" @submit="handleFormSubmit($event, 'otp')">
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
                        <span wire:loading.remove>Send OTP Code</span>
                        <span wire:loading class="loading-dots">Sending</span>
                    </span>
                </button>
            </form>
        @else
            <form class="auth-form" wire:submit.prevent="verifyOtp" @submit="handleFormSubmit($event, 'verify')">
                <div class="form-group animate-slide-in-left animate-delay-100">
                    <label class="form-label" for="code">
                        <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Verification Code
                    </label>
                    <input 
                        id="code" 
                        name="code" 
                        type="text" 
                        maxlength="6"
                        autocomplete="one-time-code"
                        required 
                        wire:model="code"
                        class="form-input @error('code') error @enderror text-center text-2xl tracking-widest" 
                        placeholder="Enter 6-digit code"
                        @blur="validateField($event.target)"
                        @input="clearFieldError($event.target)">
                    @error('code') 
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <div class="flex space-x-4 mb-4 animate-slide-in-up animate-delay-200">
                    <button 
                        type="button" 
                        wire:click="backToEmail"
                        class="btn-modern flex-1 bg-gray-500 hover:bg-gray-600">
                        Back
                    </button>
                    <button 
                        type="submit" 
                        class="btn-modern flex-1"
                        :disabled="isLoading"
                        wire:loading.attr="disabled">
                        <span class="flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="loading-spinner" wire:loading></div>
                            <span wire:loading.remove>Verify</span>
                            <span wire:loading class="loading-dots">Verifying</span>
                        </span>
                    </button>
                </div>

                <div class="text-center animate-slide-in-up animate-delay-300">
                    <button 
                        type="button" 
                        wire:click="resendOtp"
                        id="resendBtn"
                        class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Resend code
                    </button>
                </div>
            </form>
        @endif

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mt-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-xl animate-slide-in-up animate-delay-400">
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('message') }}</p>
            </div>
        @endif

        <!-- Alternative Login Options -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 animate-slide-in-up animate-delay-500">
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Or continue with</p>
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('login') }}" class="btn-modern bg-gray-500 hover:bg-gray-600 text-center animate-slide-in-left animate-delay-100">
                        Password Login
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="w-full px-4 py-3 text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-semibold transition-colors text-center animate-bounce animate-delay-200">
                            Create Account
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function modernOTP() {
    return {
        isLoading: false,
        resendCooldown: 0,
        resendTimer: null,
        
        init() {
            // Auto-focus first input
            this.$nextTick(() => {
                const firstInput = this.$el.querySelector('input:not([type="hidden"]):not([disabled])');
                if (firstInput) {
                    firstInput.focus();
                }
            });
            
            // Auto-submit when 6 digits are entered
            this.$el.addEventListener('input', (e) => {
                if (e.target.id === 'code' && e.target.value.length === 6) {
                    setTimeout(() => {
                        @this.verifyOtp();
                    }, 500);
                }
            });
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
        },
        
        startResendCooldown() {
            this.resendCooldown = 60;
            const resendBtn = document.getElementById('resendBtn');
            if (resendBtn) {
                resendBtn.disabled = true;
                resendBtn.textContent = `Resend code in ${this.resendCooldown}s`;
                
                this.resendTimer = setInterval(() => {
                    this.resendCooldown--;
                    if (this.resendCooldown > 0) {
                        resendBtn.textContent = `Resend code in ${this.resendCooldown}s`;
                    } else {
                        resendBtn.disabled = false;
                        resendBtn.textContent = 'Resend code';
                        clearInterval(this.resendTimer);
                    }
                }, 1000);
            }
        }
    }
}

// Global event listeners for Livewire
document.addEventListener('livewire:load', function () {
    if (window.Livewire) {
        window.Livewire.on('otpSent', () => {
            const component = document.querySelector('[x-data="modernOTP()"]');
            if (component && component._x_dataStack && component._x_dataStack[0]) {
                component._x_dataStack[0].startResendCooldown();
            }
        });
    }
});
</script>
