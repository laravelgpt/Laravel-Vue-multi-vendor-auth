<template>
    <div class="space-y-3">
        <!-- Password Strength Bar -->
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-slate-600 dark:text-slate-400">Password Strength</span>
                <span :class="strengthColorClass">{{ strengthLabel }}</span>
            </div>
            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                <div 
                    :class="strengthBarClass"
                    class="h-2 rounded-full transition-all duration-300 ease-in-out"
                    :style="{ width: `${strengthScore}%` }"
                ></div>
            </div>
        </div>

        <!-- Breach Warning -->
        <div v-if="breachCount > 0" class="flex items-center space-x-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <ShieldX class="w-5 h-5 text-red-500" />
            <div class="text-sm">
                <p class="font-medium text-red-800 dark:text-red-200">
                    Password found in {{ breachCount }} data breach{{ breachCount > 1 ? 'es' : '' }}
                </p>
                <p class="text-red-600 dark:text-red-300">
                    This password has been compromised and should not be used.
                </p>
            </div>
        </div>

        <!-- Strength Feedback -->
        <div v-if="feedback.length > 0" class="space-y-2">
            <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300">Suggestions:</h4>
            <ul class="space-y-1">
                <li 
                    v-for="(suggestion, index) in feedback" 
                    :key="index"
                    class="flex items-start space-x-2 text-sm text-slate-600 dark:text-slate-400"
                >
                    <CheckCircle v-if="suggestion.startsWith('✓')" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" />
                    <AlertCircle v-else class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" />
                    <span>{{ suggestion.replace('✓ ', '') }}</span>
                </li>
            </ul>
        </div>

        <!-- Requirements Checklist -->
        <div class="space-y-2">
            <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300">Requirements:</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div class="flex items-center space-x-2 text-sm">
                    <CheckCircle v-if="hasMinLength" class="w-4 h-4 text-green-500" />
                    <XCircle v-else class="w-4 h-4 text-red-500" />
                    <span :class="hasMinLength ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                        At least 8 characters
                    </span>
                </div>
                <div class="flex items-center space-x-2 text-sm">
                    <CheckCircle v-if="hasLowercase" class="w-4 h-4 text-green-500" />
                    <XCircle v-else class="w-4 h-4 text-red-500" />
                    <span :class="hasLowercase ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                        Lowercase letter
                    </span>
                </div>
                <div class="flex items-center space-x-2 text-sm">
                    <CheckCircle v-if="hasUppercase" class="w-4 h-4 text-green-500" />
                    <XCircle v-else class="w-4 h-4 text-red-500" />
                    <span :class="hasUppercase ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                        Uppercase letter
                    </span>
                </div>
                <div class="flex items-center space-x-2 text-sm">
                    <CheckCircle v-if="hasNumber" class="w-4 h-4 text-green-500" />
                    <XCircle v-else class="w-4 h-4 text-red-500" />
                    <span :class="hasNumber ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                        Number
                    </span>
                </div>
                <div class="flex items-center space-x-2 text-sm">
                    <CheckCircle v-if="hasSymbol" class="w-4 h-4 text-green-500" />
                    <XCircle v-else class="w-4 h-4 text-red-500" />
                    <span :class="hasSymbol ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                        Special character
                    </span>
                </div>
                <div class="flex items-center space-x-2 text-sm">
                    <CheckCircle v-if="!isBreached" class="w-4 h-4 text-green-500" />
                    <XCircle v-else class="w-4 h-4 text-red-500" />
                    <span :class="!isBreached ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                        Not in data breaches
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { CheckCircle, XCircle, AlertCircle, ShieldX } from 'lucide-vue-next';

interface Props {
    password: string;
}

const props = defineProps<Props>();

const strengthScore = ref(0);
const strengthLabel = ref('Very Weak');
const feedback = ref<string[]>([]);
const breachCount = ref(0);
const isBreached = ref(false);

// Computed properties for requirements
const hasMinLength = computed(() => props.password.length >= 8);
const hasLowercase = computed(() => /[a-z]/.test(props.password));
const hasUppercase = computed(() => /[A-Z]/.test(props.password));
const hasNumber = computed(() => /[0-9]/.test(props.password));
const hasSymbol = computed(() => /[^a-zA-Z0-9]/.test(props.password));

// Computed properties for styling
const strengthColorClass = computed(() => {
    if (strengthScore.value >= 80) return 'text-green-600 dark:text-green-400';
    if (strengthScore.value >= 60) return 'text-blue-600 dark:text-blue-400';
    if (strengthScore.value >= 40) return 'text-yellow-600 dark:text-yellow-400';
    if (strengthScore.value >= 20) return 'text-orange-600 dark:text-orange-400';
    return 'text-red-600 dark:text-red-400';
});

const strengthBarClass = computed(() => {
    if (strengthScore.value >= 80) return 'bg-gradient-to-r from-green-400 to-green-600';
    if (strengthScore.value >= 60) return 'bg-gradient-to-r from-blue-400 to-blue-600';
    if (strengthScore.value >= 40) return 'bg-gradient-to-r from-yellow-400 to-yellow-600';
    if (strengthScore.value >= 20) return 'bg-gradient-to-r from-orange-400 to-orange-600';
    return 'bg-gradient-to-r from-red-400 to-red-600';
});

// Check password strength
const checkPasswordStrength = async () => {
    if (!props.password) {
        strengthScore.value = 0;
        strengthLabel.value = 'Very Weak';
        feedback.value = [];
        breachCount.value = 0;
        isBreached.value = false;
        return;
    }

    try {
        const response = await fetch('/api/password/check-strength', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify({ password: props.password }),
        });

        if (response.ok) {
            const data = await response.json();
            strengthScore.value = data.score;
            strengthLabel.value = data.strength;
            feedback.value = data.feedback;
            breachCount.value = data.breach_count;
            isBreached.value = data.breach_count > 0;
        }
    } catch (error) {
        console.error('Error checking password strength:', error);
        // Fallback to client-side calculation
        calculateStrengthLocally();
    }
};

// Fallback client-side strength calculation
const calculateStrengthLocally = () => {
    let score = 0;
    const localFeedback: string[] = [];

    // Length check
    if (props.password.length >= 12) {
        score += 25;
        localFeedback.push('✓ Good length');
    } else if (props.password.length >= 8) {
        score += 15;
        localFeedback.push('Consider using a longer password (12+ characters)');
    } else {
        localFeedback.push('Password is too short (minimum 8 characters)');
    }

    // Character variety checks
    if (hasLowercase.value) {
        score += 10;
        localFeedback.push('✓ Contains lowercase letters');
    } else {
        localFeedback.push('Add lowercase letters');
    }

    if (hasUppercase.value) {
        score += 10;
        localFeedback.push('✓ Contains uppercase letters');
    } else {
        localFeedback.push('Add uppercase letters');
    }

    if (hasNumber.value) {
        score += 10;
        localFeedback.push('✓ Contains numbers');
    } else {
        localFeedback.push('Add numbers');
    }

    if (hasSymbol.value) {
        score += 15;
        localFeedback.push('✓ Contains special characters');
    } else {
        localFeedback.push('Add special characters');
    }

    // Check for common patterns
    if (/(.)\1{2,}/.test(props.password)) {
        score -= 10;
        localFeedback.push('Avoid repeated characters');
    }

    if (/(123|abc|qwe|password|admin)/i.test(props.password)) {
        score -= 20;
        localFeedback.push('Avoid common patterns and words');
    }

    // Ensure score is between 0 and 100
    score = Math.max(0, Math.min(100, score));

    strengthScore.value = score;
    strengthLabel.value = score >= 80 ? 'Very Strong' : score >= 60 ? 'Strong' : score >= 40 ? 'Moderate' : score >= 20 ? 'Weak' : 'Very Weak';
    feedback.value = localFeedback;
    breachCount.value = 0;
    isBreached.value = false;
};

// Watch for password changes
watch(() => props.password, () => {
    checkPasswordStrength();
}, { immediate: true });
</script>
