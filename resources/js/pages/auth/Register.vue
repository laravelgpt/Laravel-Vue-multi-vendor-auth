<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Auth/RegisteredUserController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import SocialLoginButton from '@/components/SocialLoginButton.vue';
import { home, login } from '@/routes';
import { Form, Head, Link } from '@inertiajs/vue3';
import { LoaderCircle, User, Mail, Lock, Eye, EyeOff, Shield } from 'lucide-vue-next';
import { ref } from 'vue';

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const toggleConfirmPassword = () => {
    showConfirmPassword.value = !showConfirmPassword.value;
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <Head title="Sign up" />
        
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
                <Card class="border-0 shadow-xl bg-white/80 backdrop-blur-xl">
                    <CardHeader class="text-center">
                        <CardTitle class="text-2xl font-bold text-slate-900 dark:text-white">Create your account</CardTitle>
                        <CardDescription class="text-slate-600 dark:text-slate-400">
                            Join us and start your journey today
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="p-6">
                        <Form
                            v-bind="store.form()"
                            :reset-on-success="['password', 'password_confirmation']"
                            v-slot="{ errors, processing }"
                            class="space-y-6"
                        >
                            <div class="space-y-4">
                                <div>
                                    <Label for="name" class="text-sm font-medium text-slate-700 dark:text-slate-300">Full name</Label>
                                    <div class="relative mt-1">
                                        <Input
                                            id="name"
                                            type="text"
                                            name="name"
                                            required
                                            autofocus
                                            autocomplete="name"
                                            placeholder="Enter your full name"
                                            class="pl-10"
                                        />
                                        <User class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    </div>
                                    <InputError :message="errors.name" />
                                </div>

                                <div>
                                    <Label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300">Email address</Label>
                                    <div class="relative mt-1">
                                        <Input
                                            id="email"
                                            type="email"
                                            name="email"
                                            required
                                            autocomplete="email"
                                            placeholder="Enter your email"
                                            class="pl-10"
                                        />
                                        <Mail class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    </div>
                                    <InputError :message="errors.email" />
                                </div>

                                <div>
                                    <Label for="password" class="text-sm font-medium text-slate-700 dark:text-slate-300">Password</Label>
                                    <div class="relative mt-1">
                                        <Input
                                            id="password"
                                            :type="showPassword ? 'text' : 'password'"
                                            name="password"
                                            required
                                            autocomplete="new-password"
                                            placeholder="Create a strong password"
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

                                <div>
                                    <Label for="password_confirmation" class="text-sm font-medium text-slate-700 dark:text-slate-300">Confirm password</Label>
                                    <div class="relative mt-1">
                                        <Input
                                            id="password_confirmation"
                                            :type="showConfirmPassword ? 'text' : 'password'"
                                            name="password_confirmation"
                                            required
                                            autocomplete="new-password"
                                            placeholder="Confirm your password"
                                            class="pl-10 pr-10"
                                        />
                                        <Lock class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                        <button
                                            type="button"
                                            @click="toggleConfirmPassword"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                        >
                                            <Eye v-if="!showConfirmPassword" class="h-4 w-4" />
                                            <EyeOff v-else class="h-4 w-4" />
                                        </button>
                                    </div>
                                    <InputError :message="errors.password_confirmation" />
                                </div>

                                <Button type="submit" class="w-full" :disabled="processing">
                                    <LoaderCircle v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                                    Create account
                                </Button>
                            </div>
                        </Form>

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

                        <!-- Sign In Link -->
                        <div class="text-center text-sm text-slate-600 dark:text-slate-400">
                            Already have an account?
                            <TextLink :href="login()" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                Sign in
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
