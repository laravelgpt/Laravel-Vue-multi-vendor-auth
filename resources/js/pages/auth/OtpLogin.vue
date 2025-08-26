<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { home, login } from '@/routes';
import { Head } from '@inertiajs/vue3';
import { Mail, ArrowLeft, Shield } from 'lucide-vue-next';
import { ref, onMounted } from 'vue';

const props = defineProps<{
    status?: string;
    email?: string;
}>();

const otpCode = ref(['', '', '', '', '', '']);
const otpInputs = ref<HTMLInputElement[]>([]);

onMounted(() => {
    if (otpInputs.value[0]) {
        otpInputs.value[0].focus();
    }
});

const handleOtpInput = (index: number, value: string) => {
    if (value.length > 1) {
        value = value.slice(-1);
    }
    
    otpCode.value[index] = value;
    
    // Move to next input if value is entered
    if (value && index < 5) {
        otpInputs.value[index + 1]?.focus();
    }
    
    // Move to previous input if value is deleted
    if (!value && index > 0) {
        otpInputs.value[index - 1]?.focus();
    }
};

const handleKeydown = (index: number, event: KeyboardEvent) => {
    if (event.key === 'Backspace' && !otpCode.value[index] && index > 0) {
        otpInputs.value[index - 1]?.focus();
    }
};

const getOtpString = () => {
    return otpCode.value.join('');
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <Head title="OTP Verification" />
        
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
                    <TextLink :href="home()" class="inline-flex items-center gap-3 text-2xl font-bold text-slate-900 dark:text-white">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
                            <Shield class="h-6 w-6 text-white" />
                        </div>
                        <span>Laravel Vue</span>
                    </TextLink>
                </div>

                <!-- Auth Card -->
                <Card class="border-0 shadow-xl bg-white/80 backdrop-blur-xl">
                    <CardHeader class="text-center">
                        <CardTitle class="text-2xl font-bold text-slate-900 dark:text-white">Enter OTP Code</CardTitle>
                        <CardDescription class="text-slate-600 dark:text-slate-400">
                            We've sent a 6-digit code to your email
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="p-6">
                        <div v-if="status" class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
                            {{ status }}
                        </div>

                        <!-- Back to Login -->
                        <div class="mb-6">
                            <TextLink :href="login()" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                                <ArrowLeft class="h-4 w-4" />
                                Back to login
                            </TextLink>
                        </div>

                        <!-- Email Display -->
                        <div class="mb-6 text-center">
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Code sent to <span class="font-medium text-slate-900 dark:text-white">{{ email }}</span>
                            </p>
                        </div>

                        <!-- OTP Form -->
                        <form action="/login/otp/verify" method="post" class="space-y-6">
                            <input type="hidden" name="email" :value="email" />
                            <input type="hidden" name="code" :value="getOtpString()" />
                            
                            <div class="space-y-4">
                                <Label class="text-sm font-medium text-slate-700 dark:text-slate-300">Enter 6-digit code</Label>
                                
                                <div class="flex gap-2 justify-center">
                                    <Input
                                        v-for="(digit, index) in 6"
                                        :key="index"
                                        :ref="(el) => { if (el) otpInputs[index] = el as HTMLInputElement }"
                                        v-model="otpCode[index]"
                                        type="text"
                                        maxlength="1"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        class="w-12 h-12 text-center text-lg font-semibold"
                                        @input="(e) => handleOtpInput(index, (e.target as HTMLInputElement).value)"
                                        @keydown="(e) => handleKeydown(index, e)"
                                    />
                                </div>
                            </div>

                            <Button type="submit" class="w-full" :disabled="getOtpString().length !== 6">
                                Verify Code
                            </Button>
                        </form>
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
