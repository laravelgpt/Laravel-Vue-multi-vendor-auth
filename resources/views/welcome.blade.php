<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} - Modern Web Application</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="min-h-screen">
            <!-- Hero Section -->
            <div class="relative overflow-hidden">
                <!-- Background Elements -->
                <div class="absolute inset-0">
                    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-primary rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
                    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-secondary rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
                    <div class="absolute top-40 left-40 w-80 h-80 bg-gradient-success rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 4s;"></div>
                </div>

                <!-- Navigation -->
                <nav class="relative z-10 px-6 py-8">
                    <div class="max-w-7xl mx-auto flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold gradient-text">{{ config('app.name', 'Laravel') }}</span>
                        </div>
                        
                        @if (Route::has('login'))
                            <div class="flex items-center space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn-gradient px-6 py-2 rounded-xl text-white font-semibold hover-lift">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 dark:text-gray-300 font-medium hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        Log in
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn-gradient px-6 py-2 rounded-xl text-white font-semibold hover-lift">
                                            Get Started
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </nav>

                <!-- Hero Content -->
                <div class="relative z-10 px-6 py-20">
                    <div class="max-w-7xl mx-auto text-center">
                        <div class="animate-fade-in">
                            <h1 class="text-5xl md:text-7xl font-bold gradient-text mb-6 text-shadow-lg">
                                Welcome to the Future
                            </h1>
                            <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed">
                                Experience the power of Laravel Livewire with modern design, real-time interactions, and seamless user experience.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn-gradient px-8 py-4 rounded-2xl text-white font-semibold text-lg hover-lift">
                                        Go to Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="btn-gradient px-8 py-4 rounded-2xl text-white font-semibold text-lg hover-lift">
                                        Get Started Free
                                    </a>
                                    <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold text-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        Sign In
                                    </a>
                                @endauth
                            </div>
                        </div>

                        <!-- Feature Cards -->
                        <div class="grid md:grid-cols-3 gap-8 mt-20 animate-scale-in">
                            <div class="card-3d rounded-2xl p-8 hover-lift group">
                                <div class="w-16 h-16 bg-gradient-primary rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Lightning Fast</h3>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Built with Laravel Livewire for real-time interactions without the complexity of JavaScript frameworks.
                                </p>
                            </div>

                            <div class="card-3d rounded-2xl p-8 hover-lift group">
                                <div class="w-16 h-16 bg-gradient-success rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Modern Design</h3>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Beautiful glassmorphism effects, smooth animations, and responsive design that works on all devices.
                                </p>
                            </div>

                            <div class="card-3d rounded-2xl p-8 hover-lift group">
                                <div class="w-16 h-16 bg-gradient-secondary rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Secure & Reliable</h3>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Enterprise-grade security with Laravel's built-in authentication, validation, and protection features.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="relative py-20 px-6">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-6 text-shadow">
                            Powerful Features
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                            Everything you need to build modern web applications with Laravel Livewire
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="text-center animate-slide-in-left">
                            <div class="w-20 h-20 bg-gradient-primary rounded-3xl flex items-center justify-center mx-auto mb-6 float">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Real-time UI</h3>
                            <p class="text-gray-600 dark:text-gray-300">Livewire components update in real-time without page refreshes</p>
                        </div>

                        <div class="text-center animate-slide-in-left" style="animation-delay: 0.1s;">
                            <div class="w-20 h-20 bg-gradient-success rounded-3xl flex items-center justify-center mx-auto mb-6 float" style="animation-delay: 1s;">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Mobile First</h3>
                            <p class="text-gray-600 dark:text-gray-300">Responsive design that works perfectly on all devices</p>
                        </div>

                        <div class="text-center animate-slide-in-right" style="animation-delay: 0.2s;">
                            <div class="w-20 h-20 bg-gradient-warning rounded-3xl flex items-center justify-center mx-auto mb-6 float" style="animation-delay: 2s;">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Analytics</h3>
                            <p class="text-gray-600 dark:text-gray-300">Built-in analytics and monitoring for your application</p>
                        </div>

                        <div class="text-center animate-slide-in-right" style="animation-delay: 0.3s;">
                            <div class="w-20 h-20 bg-gradient-danger rounded-3xl flex items-center justify-center mx-auto mb-6 float" style="animation-delay: 3s;">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Secure Auth</h3>
                            <p class="text-gray-600 dark:text-gray-300">Advanced authentication with social login and OTP support</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="relative py-20 px-6">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="card-3d rounded-3xl p-12 animate-bounce-in">
                        <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-6 text-shadow">
                            Ready to Get Started?
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                            Join thousands of developers building amazing applications with Laravel Livewire
                        </p>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-gradient px-10 py-4 rounded-2xl text-white font-semibold text-lg hover-lift inline-block">
                                Go to Dashboard
                            </a>
                        @else
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('register') }}" class="btn-gradient px-10 py-4 rounded-2xl text-white font-semibold text-lg hover-lift">
                                    Create Account
                                </a>
                                <a href="{{ route('login') }}" class="px-10 py-4 rounded-2xl border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold text-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Sign In
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="relative py-12 px-6 border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto text-center">
                    <div class="flex items-center justify-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-primary rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold gradient-text">{{ config('app.name', 'Laravel') }}</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Built with Laravel {{ Illuminate\Foundation\Application::VERSION }} and Livewire
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">
                        © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
