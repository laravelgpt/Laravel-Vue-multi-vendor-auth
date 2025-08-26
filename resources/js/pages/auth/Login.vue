<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import SocialLoginButton from '@/components/SocialLoginButton.vue';
import { home, register } from '@/routes';
import { request } from '@/routes/password';
import { Form, Head, Link } from '@inertiajs/vue3';
import { LoaderCircle, Mail, Lock, Eye, EyeOff, Shield, User } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const showPassword = ref(false);
const activeTab = ref('password'); // 'password' or 'otp'

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const switchToOtp = () => {
    activeTab.value = 'otp';
};

const switchToPassword = () => {
    activeTab.value = 'password';
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <Head title="Sign in" />
        
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full bg-gradient-to-br from-blue-400/20 to-purple-600/20 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 h-80 w-80 rounded-full bg-gradient-to-tr from-indigo-400/20 to-pink-600/20 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 h-60 w-60 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-to-r from-cyan-400/10 to-blue-600/10 blur-2xl"></div>
        </div>

        <!-- Main Content -->
        <div class="relative flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-md">
                <!-- Logo and Header -->
                <div class="mb-8 text-center">
                    <Link :href="home()" class="inline-flex items-center gap-3 text-2xl font-bold text-slate-900 dark:text-white">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
                            <Shield class="h-6 w-6 text-white" />
                        </div>
                        <span>Laravel Vue</span>
                    </Link>
                </div>

                <!-- Auth Card -->
                <Card class="glass-strong card-hover animate-bounce-in">
                    <CardHeader class="text-center">
                        <CardTitle class="text-2xl font-bold text-slate-900 dark:text-white">Welcome back</CardTitle>
                        <CardDescription class="text-slate-600 dark:text-slate-400">
                            Sign in to your account to continue
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="p-6">
                        <div v-if="status" class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
                            {{ status }}
                        </div>

                        <!-- Tab Navigation -->
                        <div class="mb-6">
                            <div class="flex rounded-lg bg-slate-100 p-1 dark:bg-slate-700">
                                <button
                                    @click="switchToPassword"
                                    :class="[
                                        'flex-1 rounded-md px-3 py-2 text-sm font-medium transition-all',
                                        activeTab === 'password'
                                            ? 'bg-white text-slate-900 shadow-sm dark:bg-slate-600 dark:text-white'
                                            : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'
                                    ]"
                                >
                                    <Lock class="mr-2 h-4 w-4 inline" />
                                    Password
                                </button>
                                <button
                                    @click="switchToOtp"
                                    :class="[
                                        'flex-1 rounded-md px-3 py-2 text-sm font-medium transition-all',
                                        activeTab === 'otp'
                                            ? 'bg-white text-slate-900 shadow-sm dark:bg-slate-600 dark:text-white'
                                            : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white'
                                    ]"
                                >
                                    <Mail class="mr-2 h-4 w-4 inline" />
                                    OTP Code
                                </button>
                            </div>
                        </div>

                        <!-- Password Login -->
                        <div v-if="activeTab === 'password'">
                            <Form v-bind="store.form()" :reset-on-success="['password']" v-slot="{ errors, processing }" class="space-y-6">
                                <div class="space-y-4">
                                    <div>
                                        <Label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300">Email address</Label>
                                        <div class="relative mt-1">
                                            <Input
                                                id="email"
                                                type="email"
                                                name="email"
                                                required
                                                autofocus
                                                autocomplete="email"
                                                placeholder="Enter your email"
                                                class="pl-10"
                                            />
                                            <Mail class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                        </div>
                                        <InputError :message="errors.email" />
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between">
                                            <Label for="password" class="text-sm font-medium text-slate-700 dark:text-slate-300">Password</Label>
                                            <TextLink v-if="canResetPassword" :href="request()" class="text-xs text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                                Forgot password?
                                            </TextLink>
                                        </div>
                                        <div class="relative mt-1">
                                            <Input
                                                id="password"
                                                :type="showPassword ? 'text' : 'password'"
                                                name="password"
                                                required
                                                autocomplete="current-password"
                                                placeholder="Enter your password"
                                                class="pl-10 pr-10"
                                            />
                                            <Lock class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                            <button
                                                type="button"
                                                @click="togglePassword"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                            >
                                                <Eye v-if="!showPassword" class="h-4 w-4" />
                                                <EyeOff v-else class="h-4 w-4" />
                                            </button>
                                        </div>
                                        <InputError :message="errors.password" />
                                    </div>

                                    <div class="flex items-center">
                                        <Checkbox id="remember" name="remember" />
                                        <Label for="remember" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Remember me</Label>
                                    </div>

                                    <Button type="submit" class="w-full btn-gradient" :disabled="processing">
                                        <LoaderCircle v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                                        <span v-else>Sign in</span>
                                    </Button>
                                </div>
                            </Form>
                        </div>

                        <!-- OTP Login -->
                        <div v-else>
                            <form action="/login/otp" method="get" class="space-y-6">
                                <div class="space-y-4">
                                    <div>
                                        <Label for="otp-email" class="text-sm font-medium text-slate-700 dark:text-slate-300">Email address</Label>
                                        <div class="relative mt-1">
                                            <Input
                                                id="otp-email"
                                                type="email"
                                                name="email"
                                                required
                                                autofocus
                                                autocomplete="email"
                                                placeholder="Enter your email"
                                                class="pl-10"
                                            />
                                            <Mail class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                        </div>
                                    </div>

                                    <Button type="submit" class="w-full">
                                        Send OTP Code
                                    </Button>
                                </div>
                            </form>
                        </div>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                            </div>
                            <div class="relative flex justify-center text-xs uppercase">
                                <span class="bg-white px-2 text-slate-500 dark:bg-slate-800 dark:text-slate-400">Or continue with</span>
                            </div>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="space-y-3">
                            <SocialLoginButton provider="google" href="/login/google" />
                            <SocialLoginButton provider="facebook" href="/login/facebook" />
                            <SocialLoginButton provider="github" href="/login/github" />
                        </div>

                        <!-- Sign Up Link -->
                        <div class="mt-6 text-center text-sm text-slate-600 dark:text-slate-400">
                            Don't have an account?
                            <TextLink :href="register()" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                Sign up
                            </TextLink>
                        </div>
                    </CardContent>
                </Card>

                <!-- Footer -->
                <div class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
                    <p>&copy; {{ new Date().getFullYear() }} Laravel Vue. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</template>
