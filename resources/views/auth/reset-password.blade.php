<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-violet-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="container-professional">
        <div class="text-center space-professional-lg animate-slide-in-up">
            <div class="flex justify-center space-professional-md animate-float">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-600 via-violet-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-heading-1 text-gray-900 dark:text-white animate-slide-in-up animate-delay-100">
                Reset Password
            </h1>
            <p class="text-body-large text-gray-600 dark:text-gray-300 animate-slide-in-up animate-delay-200">
                Enter your new password below to complete the reset process.
            </p>
        </div>
        
        <div class="card-professional-elevated padding-professional-lg animate-fade-in-scale animate-delay-300">
            <form method="POST" action="{{ route('password.store') }}" class="space-professional-lg">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-professional-lg">
                    <!-- Email Address -->
                    <div class="form-group-professional animate-slide-in-left animate-delay-100">
                        <label for="email">Email address</label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email', $email) }}"
                               class="input-professional @error('email') error @enderror" 
                               placeholder="Enter your email address"
                               autofocus>
                        @error('email') 
                            <span class="error-message">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group-professional animate-slide-in-right animate-delay-200">
                        <label for="password">New Password</label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="new-password" 
                               required 
                               class="input-professional @error('password') error @enderror" 
                               placeholder="Enter your new password">
                        @error('password') 
                            <span class="error-message">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group-professional animate-slide-in-left animate-delay-300">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required 
                               class="input-professional @error('password_confirmation') error @enderror" 
                               placeholder="Confirm your new password">
                        @error('password_confirmation') 
                            <span class="error-message">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>

                <div class="space-professional-md animate-slide-in-up animate-delay-400">
                    <button type="submit" 
                            class="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed animate-pulse">
                        <span class="flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Reset Password</span>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Alternative Options -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 animate-slide-in-up animate-delay-500">
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