<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-cyan-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="container-professional">
        <div class="text-center space-professional-lg animate-slide-in-up">
            <div class="flex justify-center space-professional-md animate-float">
                <div class="w-20 h-20 bg-gradient-to-br from-cyan-600 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-heading-1 text-gray-900 dark:text-white animate-slide-in-up animate-delay-100">
                Verify Your Email
            </h1>
            <p class="text-body-large text-gray-600 dark:text-gray-300 animate-slide-in-up animate-delay-200">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
            </p>
        </div>
        
        <div class="card-professional-elevated padding-professional-lg animate-fade-in-scale animate-delay-300">
            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-xl animate-slide-in-up">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-green-700 dark:text-green-300 font-medium">
                            A new verification link has been sent to the email address you provided during registration.
                        </p>
                    </div>
                </div>
            @endif

            <div class="space-professional-lg">
                <div class="text-center space-professional-md animate-slide-in-up animate-delay-100">
                    <p class="text-body-medium text-gray-600 dark:text-gray-300">
                        If you didn't receive the email, we will gladly send you another.
                    </p>
                </div>

                <form method="POST" action="{{ route('verification.send') }}" class="space-professional-md animate-slide-in-up animate-delay-200">
                    @csrf
                    <button type="submit" 
                            class="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed animate-pulse">
                        <span class="flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>Resend Verification Email</span>
                        </span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="animate-slide-in-up animate-delay-300">
                    @csrf
                    <button type="submit" 
                            class="btn-ghost w-full">
                        <span class="flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Log Out</span>
                        </span>
                    </button>
                </form>
            </div>

            <!-- Alternative Options -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 animate-slide-in-up animate-delay-400">
                <div class="text-center space-y-3">
                    <a href="{{ route('dashboard') }}" class="btn-2d-secondary w-full animate-slide-in-left animate-delay-100">
                        Go to Dashboard
                    </a>
                    <a href="{{ route('login.otp') }}" class="btn-ghost w-full animate-slide-in-right animate-delay-200">
                        Try OTP Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>