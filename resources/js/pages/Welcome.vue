<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { 
    Shield, 
    Zap, 
    Users, 
    Lock, 
    Smartphone, 
    Globe, 
    CheckCircle, 
    ArrowRight,
    Star,
    Github,
    Twitter,
    Linkedin,
    Mail,
    Phone,
    MapPin,
    Calendar,
    UserCheck,
    Key,
    Eye,
    EyeOff,
    Camera,
    Settings,
    BarChart3,
    Database,
    Cpu,
    Server,
    Cloud,
    ShieldCheck,
    Zap as Lightning,
    Users as Team,
    Lock as Security,
    Smartphone as Mobile,
    Globe as World,
    CheckCircle as Verified,
    ArrowRight as Next,
    Star as Award,
    Github as Git,
    Twitter as Tweet,
    Linkedin as LinkedInIcon,
    Mail as Email,
    Phone as Call,
    MapPin as Location,
    Calendar as Date,
    UserCheck as Profile,
    Key as Password,
    Eye as View,
    EyeOff as Hide,
    Camera as Photo,
    Settings as Config,
    BarChart3 as Analytics,
    Database as Storage,
    Cpu as Performance,
    Server as Hosting,
    Cloud as CloudService,
    ShieldCheck as Secure
} from 'lucide-vue-next';

const features = [
    {
        icon: Shield,
        title: 'Secure Authentication',
        description: 'Multi-factor authentication, OTP login, and social login integration with Google, GitHub, Facebook, and Apple.'
    },
    {
        icon: Zap,
        title: 'Lightning Fast',
        description: 'Built with Laravel 12, Vue 3, and Inertia.js for optimal performance and user experience.'
    },
    {
        icon: Users,
        title: 'User Management',
        description: 'Comprehensive user profiles, role-based access control, and admin dashboard for user management.'
    },
    {
        icon: Lock,
        title: 'Advanced Security',
        description: 'Password breach detection, secure password validation, and comprehensive security measures.'
    },
    {
        icon: Smartphone,
        title: 'Mobile First',
        description: 'Responsive design that works perfectly on all devices with modern mobile-first approach.'
    },
    {
        icon: Globe,
        title: 'Social Integration',
        description: 'Seamless social media login and profile integration with major platforms.'
    }
];

const techStack = [
    { name: 'Laravel 12', description: 'Latest PHP framework with modern features' },
    { name: 'Vue 3', description: 'Progressive JavaScript framework' },
    { name: 'Inertia.js', description: 'Modern monolith approach' },
    { name: 'Tailwind CSS 4', description: 'Utility-first CSS framework' },
    { name: 'TypeScript', description: 'Type-safe JavaScript development' },
    { name: 'Pest', description: 'Modern testing framework' }
];

const stats = [
    { number: '99.9%', label: 'Uptime' },
    { number: '<100ms', label: 'Response Time' },
    { number: '100%', label: 'Mobile Responsive' },
    { number: '24/7', label: 'Support' }
];
</script>

<template>
    <Head title="Welcome to Laravel Vue Starter Kit">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Navigation -->
        <nav class="relative z-50 border-b border-slate-200/80 bg-white/80 backdrop-blur-xl dark:border-slate-700/80 dark:bg-slate-900/80">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600">
                            <span class="text-sm font-bold text-white">LV</span>
                        </div>
                        <span class="text-xl font-bold text-slate-900 dark:text-white">Laravel Vue</span>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="dashboard()"
                                class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 text-sm font-medium text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl"
                            >
                                Dashboard
                                <ArrowRight class="ml-2 h-4 w-4" />
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="text-sm font-medium text-slate-700 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white"
                            >
                                Sign In
                            </Link>
                            <Link
                                :href="register()"
                                class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 text-sm font-medium text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl"
                            >
                                Get Started
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative overflow-hidden px-4 py-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl text-center">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-6xl lg:text-7xl dark:text-white">
                        Build Modern Web Apps
                        <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Faster Than Ever
                        </span>
                    </h1>
                    <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-600 dark:text-slate-400">
                        A powerful starter kit combining Laravel 12, Vue 3, and Inertia.js. 
                        Get started with authentication, user management, and modern UI components out of the box.
                    </p>
                </div>
                
                <div class="flex flex-col items-center justify-center space-y-4 sm:flex-row sm:space-x-4 sm:space-y-0">
                    <Link
                        :href="register()"
                        class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-4 text-lg font-medium text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl hover:scale-105"
                    >
                        Start Building
                        <ArrowRight class="ml-2 h-5 w-5" />
                    </Link>
                    <a
                        href="https://github.com/laravel/laravel"
                        target="_blank"
                        class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-8 py-4 text-lg font-medium text-slate-700 shadow-lg transition-all duration-200 hover:bg-slate-50 hover:shadow-xl dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700"
                    >
                        <Github class="mr-2 h-5 w-5" />
                        View on GitHub
                    </a>
                </div>

                <!-- Stats -->
                <div class="mt-16 grid grid-cols-2 gap-8 sm:grid-cols-4">
                    <div v-for="stat in stats" :key="stat.label" class="text-center">
                        <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ stat.number }}</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="relative px-4 py-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                        Everything You Need to Build
                    </h2>
                    <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600 dark:text-slate-400">
                        Our starter kit comes with all the essential features and components you need to build modern web applications.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div 
                        v-for="feature in features" 
                        :key="feature.title"
                        class="group relative rounded-2xl border border-slate-200 bg-white p-8 shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 dark:border-slate-700 dark:bg-slate-800"
                    >
                        <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600">
                            <component :is="feature.icon" class="h-6 w-6 text-white" />
                        </div>
                        <h3 class="mb-4 text-xl font-semibold text-slate-900 dark:text-white">
                            {{ feature.title }}
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            {{ feature.description }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tech Stack Section -->
        <section class="relative bg-white px-4 py-20 sm:px-6 lg:px-8 dark:bg-slate-800">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                        Built with Modern Technologies
                    </h2>
                    <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600 dark:text-slate-400">
                        Leverage the latest technologies and best practices for optimal performance and developer experience.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div 
                        v-for="tech in techStack" 
                        :key="tech.name"
                        class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-center transition-all duration-300 hover:shadow-lg dark:border-slate-700 dark:bg-slate-700"
                    >
                        <h3 class="mb-2 text-lg font-semibold text-slate-900 dark:text-white">
                            {{ tech.name }}
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            {{ tech.description }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative px-4 py-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl text-center">
                <div class="rounded-3xl bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-16 shadow-2xl">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Ready to Get Started?
                    </h2>
                    <p class="mx-auto mt-4 max-w-2xl text-lg text-blue-100">
                        Join thousands of developers building amazing applications with our starter kit.
                    </p>
                    <div class="mt-8 flex flex-col items-center justify-center space-y-4 sm:flex-row sm:space-x-4 sm:space-y-0">
                        <Link
                            :href="register()"
                            class="inline-flex items-center rounded-lg bg-white px-8 py-4 text-lg font-medium text-blue-600 shadow-lg transition-all duration-200 hover:bg-slate-50 hover:shadow-xl"
                        >
                            Start Building Today
                            <ArrowRight class="ml-2 h-5 w-5" />
                        </Link>
                        <a
                            href="https://laravel.com/docs"
                            target="_blank"
                            class="inline-flex items-center rounded-lg border-2 border-white px-8 py-4 text-lg font-medium text-white transition-all duration-200 hover:bg-white hover:text-blue-600"
                        >
                            Read Documentation
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white px-4 py-12 sm:px-6 lg:px-8 dark:border-slate-700 dark:bg-slate-800">
            <div class="mx-auto max-w-7xl">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <div class="flex items-center space-x-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600">
                                <span class="text-sm font-bold text-white">LV</span>
                            </div>
                            <span class="text-xl font-bold text-slate-900 dark:text-white">Laravel Vue</span>
                        </div>
                        <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">
                            Modern web application starter kit built with Laravel 12, Vue 3, and Inertia.js.
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Features</h3>
                        <ul class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                            <li>Authentication</li>
                            <li>User Management</li>
                            <li>Admin Dashboard</li>
                            <li>Social Login</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Resources</h3>
                        <ul class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                            <li><a href="https://laravel.com/docs" target="_blank" class="hover:text-slate-900 dark:hover:text-white">Documentation</a></li>
                            <li><a href="https://laracasts.com" target="_blank" class="hover:text-slate-900 dark:hover:text-white">Laracasts</a></li>
                            <li><a href="https://vuejs.org" target="_blank" class="hover:text-slate-900 dark:hover:text-white">Vue.js</a></li>
                            <li><a href="https://inertiajs.com" target="_blank" class="hover:text-slate-900 dark:hover:text-white">Inertia.js</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Connect</h3>
                        <div class="mt-4 flex space-x-4">
                            <a href="#" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                <Twitter class="h-5 w-5" />
                            </a>
                            <a href="#" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                <Github class="h-5 w-5" />
                            </a>
                            <a href="#" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                <LinkedInIcon class="h-5 w-5" />
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 border-t border-slate-200 pt-8 dark:border-slate-700">
                    <p class="text-center text-sm text-slate-600 dark:text-slate-400">
                        © 2024 Laravel Vue Starter Kit. Built with ❤️ using modern web technologies.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

.animate-bounce-in {
    animation: bounceIn 0.8s ease-out;
}

.animate-slide-in-left {
    animation: slideInLeft 0.6s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes bounceIn {
    0% { opacity: 0; transform: scale(0.3); }
    50% { opacity: 1; transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { opacity: 1; transform: scale(1); }
}

@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}

.glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.btn-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
</style>
