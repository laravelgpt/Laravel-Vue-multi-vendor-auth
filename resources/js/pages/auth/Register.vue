<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import TextLink from '@/components/TextLink.vue';
import { Eye, EyeOff, Mail, Lock, User, Check, Shield, Smartphone } from 'lucide-vue-next';
import { ref } from 'vue';

const showPassword = ref(false);
const showConfirmPassword = ref(false);
const activeTab = ref('password'); // 'password' or 'otp'

const passwordForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const otpForm = useForm({
    name: '',
    email: '',
    terms: false,
});

const submitPassword = () => {
    passwordForm.post('/register', {
        onFinish: () => passwordForm.reset('password', 'password_confirmation'),
    });
};

const submitOtp = () => {
    otpForm.post('/register/otp', {
        onFinish: () => otpForm.reset(),
    });
};

const switchToOtp = () => {
    activeTab.value = 'otp';
};

const switchToPassword = () => {
    activeTab.value = 'password';
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-navy-100 dark:from-slate-900 dark:via-purple-900/20 dark:to-navy-900 flex items-center justify-center p-4">
        <Head title="Register" />
        
        <!-- Enhanced Background Pattern -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-purple-400/40 to-navy-600/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-blue-400/40 to-purple-600/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-1/2 left-1/2 w-80 h-80 -translate-x-1/2 -translate-y-1/2 bg-gradient-to-r from-navy-400/20 to-purple-600/20 rounded-full blur-2xl"></div>
        </div>

        <Card class="w-full max-w-md glass-strong border border-purple-200/50 dark:border-purple-700/50 card-hover">
            <CardHeader class="space-y-1 text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 via-navy-600 to-blue-600 rounded-2xl flex items-center justify-center shadow-2xl transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                        <Shield class="w-10 h-10 text-white" />
                        </div>
                </div>
                <CardTitle class="text-3xl font-bold bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 bg-clip-text text-transparent">Create account</CardTitle>
                <CardDescription class="text-slate-600 dark:text-slate-400 text-lg">
                    Choose your preferred registration method
                        </CardDescription>
                    </CardHeader>

            <CardContent class="space-y-6">
                <!-- Tab Navigation -->
                <div class="flex rounded-xl bg-slate-100 dark:bg-slate-800 p-1">
                    <button
                        @click="switchToPassword"
                        :class="[
                            'flex-1 flex items-center justify-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-300',
                            activeTab === 'password'
                                ? 'bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 text-white shadow-lg transform scale-105'
                                : 'text-slate-600 dark:text-slate-400 hover:text-purple-600 dark:hover:text-purple-400'
                        ]"
                    >
                        <Lock class="w-4 h-4 mr-2" />
                        Password
                    </button>
                    <button
                        @click="switchToOtp"
                        :class="[
                            'flex-1 flex items-center justify-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-300',
                            activeTab === 'otp'
                                ? 'bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 text-white shadow-lg transform scale-105'
                                : 'text-slate-600 dark:text-slate-400 hover:text-purple-600 dark:hover:text-purple-400'
                        ]"
                    >
                        <Smartphone class="w-4 h-4 mr-2" />
                        OTP Code
                    </button>
                </div>

                <!-- Password Registration -->
                <div v-if="activeTab === 'password'" class="space-y-5 animate-fade-in">
                    <form @submit.prevent="submitPassword" class="space-y-5">
                        <div class="space-y-2">
                            <Label for="name" class="text-slate-700 dark:text-slate-300 font-medium">Full Name</Label>
                            <div class="relative group">
                                <User class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                                        <Input
                                            id="name"
                                    v-model="passwordForm.name"
                                            type="text"
                                            required
                                            autofocus
                                            autocomplete="name"
                                    class="pl-12 pr-4 py-3 border-2 border-slate-300 dark:border-slate-600 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-slate-800 dark:text-white rounded-xl transition-all duration-300"
                                            placeholder="Enter your full name"
                                        />
                            </div>
                            <div v-if="passwordForm.errors.name" class="text-sm text-red-600 dark:text-red-400">
                                {{ passwordForm.errors.name }}
                                    </div>
                                </div>

                        <div class="space-y-2">
                            <Label for="email" class="text-slate-700 dark:text-slate-300 font-medium">Email</Label>
                            <div class="relative group">
                                <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                                        <Input
                                            id="email"
                                    v-model="passwordForm.email"
                                            type="email"
                                            required
                                    autocomplete="username"
                                    class="pl-12 pr-4 py-3 border-2 border-slate-300 dark:border-slate-600 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-slate-800 dark:text-white rounded-xl transition-all duration-300"
                                            placeholder="Enter your email"
                                        />
                            </div>
                            <div v-if="passwordForm.errors.email" class="text-sm text-red-600 dark:text-red-400">
                                {{ passwordForm.errors.email }}
                                    </div>
                                </div>

                        <div class="space-y-2">
                            <Label for="password" class="text-slate-700 dark:text-slate-300 font-medium">Password</Label>
                            <div class="relative group">
                                <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                                        <Input
                                            id="password"
                                    v-model="passwordForm.password"
                                            :type="showPassword ? 'text' : 'password'"
                                            required
                                            autocomplete="new-password"
                                    class="pl-12 pr-12 py-3 border-2 border-slate-300 dark:border-slate-600 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-slate-800 dark:text-white rounded-xl transition-all duration-300"
                                    placeholder="Create a password"
                                        />
                                        <button
                                            type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors"
                                        >
                                    <Eye v-if="!showPassword" class="w-5 h-5" />
                                    <EyeOff v-else class="w-5 h-5" />
                                        </button>
                                    </div>
                            <div v-if="passwordForm.errors.password" class="text-sm text-red-600 dark:text-red-400">
                                {{ passwordForm.errors.password }}
                            </div>
                                </div>

                        <div class="space-y-2">
                            <Label for="password_confirmation" class="text-slate-700 dark:text-slate-300 font-medium">Confirm Password</Label>
                            <div class="relative group">
                                <Check class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                                        <Input
                                            id="password_confirmation"
                                    v-model="passwordForm.password_confirmation"
                                            :type="showConfirmPassword ? 'text' : 'password'"
                                            required
                                            autocomplete="new-password"
                                    class="pl-12 pr-12 py-3 border-2 border-slate-300 dark:border-slate-600 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-slate-800 dark:text-white rounded-xl transition-all duration-300"
                                            placeholder="Confirm your password"
                                        />
                                        <button
                                            type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors"
                                        >
                                    <Eye v-if="!showConfirmPassword" class="w-5 h-5" />
                                    <EyeOff v-else class="w-5 h-5" />
                                        </button>
                                    </div>
                            <div v-if="passwordForm.errors.password_confirmation" class="text-sm text-red-600 dark:text-red-400">
                                {{ passwordForm.errors.password_confirmation }}
                            </div>
                        </div>

                        <div class="flex items-start space-x-2">
                            <input
                                v-model="passwordForm.terms"
                                type="checkbox"
                                required
                                class="mt-1 rounded border-slate-300 text-purple-600 focus:ring-purple-500 dark:border-slate-600 dark:bg-slate-800"
                            />
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                I agree to the 
                                <TextLink href="/terms" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300">
                                    Terms of Service
                                </TextLink>
                                and 
                                <TextLink href="/privacy" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300">
                                    Privacy Policy
                                </TextLink>
                            </div>
                                </div>

                        <Button
                            type="submit"
                            :disabled="passwordForm.processing"
                            class="w-full bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 hover:from-purple-700 hover:via-navy-700 hover:to-blue-700 text-white py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                        >
                            <div v-if="passwordForm.processing" class="flex items-center space-x-2">
                                <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                <span>Creating account...</span>
                            </div>
                            <span v-else>Create account</span>
                                </Button>
                    </form>
                </div>

                <!-- OTP Registration -->
                <div v-else class="space-y-5 animate-fade-in">
                    <form @submit.prevent="submitOtp" class="space-y-5">
                        <div class="space-y-2">
                            <Label for="otp-name" class="text-slate-700 dark:text-slate-300 font-medium">Full Name</Label>
                            <div class="relative group">
                                <User class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                                <Input
                                    id="otp-name"
                                    v-model="otpForm.name"
                                    type="text"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    class="pl-12 pr-4 py-3 border-2 border-slate-300 dark:border-slate-600 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-slate-800 dark:text-white rounded-xl transition-all duration-300"
                                    placeholder="Enter your full name"
                                />
                            </div>
                            <div v-if="otpForm.errors.name" class="text-sm text-red-600 dark:text-red-400">
                                {{ otpForm.errors.name }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="otp-email" class="text-slate-700 dark:text-slate-300 font-medium">Email</Label>
                            <div class="relative group">
                                <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                                <Input
                                    id="otp-email"
                                    v-model="otpForm.email"
                                    type="email"
                                    required
                                    autocomplete="email"
                                    class="pl-12 pr-4 py-3 border-2 border-slate-300 dark:border-slate-600 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-slate-800 dark:text-white rounded-xl transition-all duration-300"
                                    placeholder="Enter your email"
                                />
                            </div>
                            <div v-if="otpForm.errors.email" class="text-sm text-red-600 dark:text-red-400">
                                {{ otpForm.errors.email }}
                            </div>
                        </div>

                        <div class="flex items-start space-x-2">
                            <input
                                v-model="otpForm.terms"
                                type="checkbox"
                                required
                                class="mt-1 rounded border-slate-300 text-purple-600 focus:ring-purple-500 dark:border-slate-600 dark:bg-slate-800"
                            />
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                I agree to the 
                                <TextLink href="/terms" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300">
                                    Terms of Service
                                </TextLink>
                                and 
                                <TextLink href="/privacy" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300">
                                    Privacy Policy
                                </TextLink>
                            </div>
                        </div>

                        <Button
                            type="submit"
                            :disabled="otpForm.processing"
                            class="w-full bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 hover:from-purple-700 hover:via-navy-700 hover:to-blue-700 text-white py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                        >
                            <div v-if="otpForm.processing" class="flex items-center space-x-2">
                                <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                <span>Sending OTP...</span>
                            </div>
                            <span v-else>Send OTP Code</span>
                        </Button>
                    </form>
                </div>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t border-slate-300 dark:border-slate-600"></span>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white dark:bg-slate-900 px-4 text-slate-500 dark:text-slate-400 font-medium">Or continue with</span>
                    </div>
                </div>

                <!-- Social Login Icons Only -->
                <div class="flex flex-wrap justify-center gap-4">
                    <Link
                        href="/auth/google"
                        class="w-12 h-12 bg-white hover:bg-gray-50 dark:bg-slate-800 dark:hover:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:shadow-lg hover:scale-110 transform"
                    >
                        <svg class="w-6 h-6 text-red-600" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </Link>
                    
                    <Link
                        href="/auth/facebook"
                        class="w-12 h-12 bg-white hover:bg-gray-50 dark:bg-slate-800 dark:hover:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:shadow-lg hover:scale-110 transform"
                    >
                        <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </Link>
                    
                    <Link
                        href="/auth/github"
                        class="w-12 h-12 bg-white hover:bg-gray-50 dark:bg-slate-800 dark:hover:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:shadow-lg hover:scale-110 transform"
                    >
                        <svg class="w-6 h-6 text-gray-900 dark:text-white" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </Link>
                    
                    <!-- Apple Sign-In (Now Functional) -->
                    <Link
                        href="/auth/apple"
                        class="w-12 h-12 bg-white hover:bg-gray-50 dark:bg-slate-800 dark:hover:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:shadow-lg hover:scale-110 transform"
                    >
                        <svg class="w-6 h-6 text-gray-900 dark:text-white" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                    </Link>
                        </div>

                        <!-- Sign In Link -->
                <div class="text-center">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Already have an account? </span>
                    <TextLink href="/login" class="text-sm text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 font-medium">
                                Sign in
                            </TextLink>
                        </div>
                    </CardContent>
                </Card>
    </div>
</template>

<style scoped>
.glass-strong {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.dark .glass-strong {
    background: rgba(15, 23, 42, 0.95);
    border: 1px solid rgba(139, 92, 246, 0.3);
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.animate-fade-in {
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(10px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
