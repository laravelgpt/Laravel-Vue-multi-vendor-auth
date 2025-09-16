<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-50 via-pink-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="container-professional">
        <div class="text-center space-professional-lg animate-slide-in-up">
            <div class="flex justify-center space-professional-md animate-float">
                <div class="w-20 h-20 bg-gradient-to-br from-red-600 via-pink-600 to-rose-600 rounded-2xl flex items-center justify-center shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-heading-1 text-gray-900 dark:text-white animate-slide-in-up animate-delay-100">
                Reset Password
            </h1>
            <p class="text-body-large text-gray-600 dark:text-gray-300 animate-slide-in-up animate-delay-200">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
            </p>
        </div>
        
        <div class="card-professional-elevated padding-professional-lg animate-fade-in-scale animate-delay-300">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-xl animate-slide-in-up">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-green-700 dark:text-green-300 font-medium">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-professional-lg">
                @csrf

                <div class="form-group-professional animate-slide-in-left animate-delay-100">
                    <label for="email">Email address</label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           value="{{ old('email') }}"
                           class="input-professional @error('email') error @enderror" 
                           placeholder="Enter your email address"
                           autofocus>
                    @error('email') 
                        <span class="error-message">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="space-professional-md animate-slide-in-up animate-delay-200">
                    <button type="submit" 
                            class="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed animate-pulse">
                        <span class="flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>Send Reset Link</span>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Alternative Options -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 animate-slide-in-up animate-delay-400">
                <div class="text-center space-y-3">
                    <a href="{{ route('login') }}" class="btn-ghost w-full animate-slide-in-left animate-delay-100">
                        Back to Login
                    </a>
                    <a href="{{ route('login.otp') }}" class="btn-2d-secondary w-full animate-slide-in-right animate-delay-200">
                        Try OTP Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
