<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import TextLink from '@/components/TextLink.vue';
import { Mail, Lock, Shield, Eye, EyeOff, Check, ArrowLeft } from 'lucide-vue-next';
import { ref } from 'vue';

const showPassword = ref(false);
const showConfirmPassword = ref(false);

defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/reset-password', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-navy-100 dark:from-slate-900 dark:via-purple-900/20 dark:to-navy-900 flex items-center justify-center p-4">
        <Head title="Reset Password" />
        
        <!-- Enhanced Background Pattern -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-purple-400/40 to-navy-600/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-blue-400/40 to-purple-600/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-1/2 left-1/2 w-80 h-80 -translate-x-1/2 -translate-y-1/2 bg-gradient-to-r from-navy-400/20 to-purple-600/20 rounded-full blur-2xl"></div>
        </div>

        <Card class="w-full max-w-md glass-strong border border-purple-200/50 dark:border-purple-700/50 card-hover">
            <CardHeader class="space-y-1 text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 via-navy-600 to-blue-600 rounded-2xl flex items-center justify-center shadow-2xl transform -rotate-6 hover:rotate-0 transition-transform duration-300">
                        <Shield class="w-10 h-10 text-white" />
                    </div>
                </div>
                <CardTitle class="text-3xl font-bold bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 bg-clip-text text-transparent">Reset Password</CardTitle>
                <CardDescription class="text-slate-600 dark:text-slate-400 text-lg">
                    Enter your new password to complete the reset
                </CardDescription>
            </CardHeader>

            <CardContent class="space-y-6">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Hidden fields for token and email -->
                    <input type="hidden" v-model="form.token" />
                    <input type="hidden" v-model="form.email" />

                    <div class="space-y-2">
                        <Label for="email-display" class="text-slate-700 dark:text-slate-300 font-medium">Email Address</Label>
                        <div class="relative group">
                            <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" />
                            <Input
                                id="email-display"
                                :value="email"
                                type="email"
                                disabled
                                class="pl-12 pr-4 py-3 border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl"
                            />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="password" class="text-slate-700 dark:text-slate-300 font-medium">New Password</Label>
                        <div class="relative group">
                            <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                            <Input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autofocus
                                autocomplete="new-password"
                                class="pl-12 pr-12 py-3 border-2 border-slate-300 dark:border-slate-600 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-slate-800 dark:text-white rounded-xl transition-all duration-300"
                                placeholder="Enter your new password"
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
                        <div v-if="form.errors.password" class="text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="password_confirmation" class="text-slate-700 dark:text-slate-300 font-medium">Confirm New Password</Label>
                        <div class="relative group">
                            <Check class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                            <Input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                required
                                autocomplete="new-password"
                                class="pl-12 pr-12 py-3 border-2 border-slate-300 dark:border-slate-600 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-slate-800 dark:text-white rounded-xl transition-all duration-300"
                                placeholder="Confirm your new password"
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
                        <div v-if="form.errors.password_confirmation" class="text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.password_confirmation }}
                        </div>
                    </div>

                    <Button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 hover:from-purple-700 hover:via-navy-700 hover:to-blue-700 text-white py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                    >
                        <div v-if="form.processing" class="flex items-center space-x-2">
                            <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span>Resetting password...</span>
                        </div>
                        <span v-else>Reset Password</span>
                    </Button>
                </form>

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
