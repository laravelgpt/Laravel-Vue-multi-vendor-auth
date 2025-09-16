<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="container-professional">
        <div class="text-center space-professional-lg animate-slide-in-up">
            <div class="flex justify-center space-professional-md animate-float">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-heading-1 text-gray-900 dark:text-white animate-slide-in-up animate-delay-100">
                Confirm Password
            </h1>
            <p class="text-body-large text-gray-600 dark:text-gray-300 animate-slide-in-up animate-delay-200">
                This is a secure area of the application. Please confirm your password before continuing.
            </p>
        </div>
        
        <div class="card-professional-elevated padding-professional-lg animate-fade-in-scale animate-delay-300">
            <form method="POST" action="{{ route('password.confirm') }}" class="space-professional-lg">
                @csrf

                <div class="form-group-professional animate-slide-in-left animate-delay-100">
                    <label for="password">Password</label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required 
                           class="input-professional @error('password') error @enderror" 
                           placeholder="Enter your password"
                           autofocus>
                    @error('password') 
                        <span class="error-message">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="space-professional-md animate-slide-in-up animate-delay-200">
                    <button type="submit" 
                            class="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed animate-pulse">
                        <span class="flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Confirm</span>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Alternative Options -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 animate-slide-in-up animate-delay-300">
                <div class="text-center space-y-3">
                    <a href="{{ route('dashboard') }}" class="btn-ghost w-full animate-slide-in-left animate-delay-100">
                        Back to Dashboard
                    </a>
                    <a href="{{ route('login.otp') }}" class="btn-2d-secondary w-full animate-slide-in-right animate-delay-200">
                        Try OTP Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>