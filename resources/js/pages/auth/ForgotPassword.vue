<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import TextLink from '@/components/TextLink.vue';
import { Mail, Shield, ArrowLeft } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-navy-100 dark:from-slate-900 dark:via-purple-900/20 dark:to-navy-900 flex items-center justify-center p-4">
        <Head title="Forgot Password" />
        
        <!-- Enhanced Background Pattern -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-purple-400/40 to-navy-600/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-blue-400/40 to-purple-600/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-1/2 left-1/2 w-80 h-80 -translate-x-1/2 -translate-y-1/2 bg-gradient-to-r from-navy-400/20 to-purple-600/20 rounded-full blur-2xl"></div>
        </div>

        <Card class="w-full max-w-md glass-strong border border-purple-200/50 dark:border-purple-700/50 card-hover">
            <CardHeader class="space-y-1 text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 via-navy-600 to-blue-600 rounded-2xl flex items-center justify-center shadow-2xl transform rotate-6 hover:rotate-0 transition-transform duration-300">
                        <Shield class="w-10 h-10 text-white" />
                    </div>
                </div>
                <CardTitle class="text-3xl font-bold bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 bg-clip-text text-transparent">Forgot Password</CardTitle>
                <CardDescription class="text-slate-600 dark:text-slate-400 text-lg">
                    Enter your email to receive a password reset link
                </CardDescription>
            </CardHeader>

            <CardContent class="space-y-6">
                <form @submit.prevent="form.post('/forgot-password')" class="space-y-5">
                    <div class="space-y-2">
                        <Label for="email" class="text-slate-700 dark:text-slate-300 font-medium">Email Address</Label>
                        <div class="relative group">
                            <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                autocomplete="email"
                                class="pl-12 pr-4 py-3 border-2 border-slate-300 dark:border-slate-600 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-slate-800 dark:text-white rounded-xl transition-all duration-300"
                                placeholder="Enter your email address"
                            />
                        </div>
                        <div v-if="form.errors.email" class="text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <Button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 hover:from-purple-700 hover:via-navy-700 hover:to-blue-700 text-white py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                    >
                        <div v-if="form.processing" class="flex items-center space-x-2">
                            <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span>Sending reset link...</span>
                        </div>
                        <span v-else>Send Password Reset Link</span>
                    </Button>
                </form>

                <!-- Success Message -->
                <div v-if="status" class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200/50 dark:border-green-800/50">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-green-800 dark:text-green-200">Success!</span>
                    </div>
                    <p class="text-sm text-green-600 dark:text-green-300 mt-1">{{ status }}</p>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <TextLink href="/login" class="inline-flex items-center text-sm text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 font-medium transition-all duration-300 hover:scale-105">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to login
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
</style>
