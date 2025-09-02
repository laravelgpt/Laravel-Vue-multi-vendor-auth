<template>
    <div class="registration-progress">
        <!-- Progress Bar -->
        <div class="progress-container mb-8">
            <div class="progress-bar">
                <div 
                    class="progress-fill" 
                    :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
                ></div>
                <div class="progress-glow"></div>
            </div>
            <div class="progress-text">
                <span class="font-semibold">Step {{ currentStep }}</span> of {{ totalSteps }} 
                <span class="text-purple-600 dark:text-purple-400">({{ Math.round((currentStep / totalSteps) * 100) }}%)</span>
            </div>
        </div>

        <!-- Step Indicators -->
        <div class="step-indicators mb-8">
            <div 
                v-for="(step, index) in steps" 
                :key="index"
                class="step-indicator"
                :class="{ 
                    'active': currentStep === index + 1,
                    'completed': currentStep > index + 1,
                    'pending': currentStep < index + 1
                }"
            >
                <div class="step-number">
                    <component 
                        :is="step.icon" 
                        v-if="currentStep > index + 1" 
                        class="w-5 h-5" 
                    />
                    <span v-else>{{ index + 1 }}</span>
                </div>
                <div class="step-title">{{ step.title }}</div>
                <div class="step-line" v-if="index < steps.length - 1"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { User, UserCheck, MapPin, Briefcase, Settings, CheckCircle } from 'lucide-vue-next';

const props = defineProps({
    currentStep: {
        type: Number,
        required: true
    },
    totalSteps: {
        type: Number,
        required: true
    }
});

const steps = [
    { title: 'Account Info', icon: User },
    { title: 'Personal Details', icon: UserCheck },
    { title: 'Address', icon: MapPin },
    { title: 'Professional', icon: Briefcase },
    { title: 'Preferences', icon: Settings },
    { title: 'Review', icon: CheckCircle }
];
</script>

<style scoped>
.registration-progress {
    width: 100%;
}

.progress-container {
    position: relative;
}

.progress-bar {
    width: 100%;
    height: 12px;
    background-color: rgb(226 232 240);
    border-radius: 9999px;
    overflow: hidden;
    position: relative;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dark .progress-bar {
    background-color: rgb(51 65 85);
}

.progress-fill {
    height: 100%;
    background: linear-gradient(to right, rgb(168 85 247), rgb(59 130 246), rgb(147 51 234));
    transition: all 0.7s ease-out;
    position: relative;
    background-size: 200% 100%;
    animation: shimmer 2s ease-in-out infinite;
}

.progress-glow {
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
    animation: glow 2s ease-in-out infinite;
}

.progress-text {
    text-align: center;
    font-size: 0.875rem;
    color: rgb(71 85 105);
    margin-top: 0.75rem;
    font-weight: 500;
}

.dark .progress-text {
    color: rgb(148 163 184);
}

.step-indicators {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.step-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    position: relative;
    z-index: 10;
}

.step-number {
    width: 3rem;
    height: 3rem;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.5s ease-out;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border: 3px solid transparent;
}

.step-indicator.active .step-number {
    background: linear-gradient(to bottom right, rgb(168 85 247), rgb(59 130 246));
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
    box-shadow: 0 0 20px rgba(147, 51, 234, 0.4);
}

.step-indicator.completed .step-number {
    background: linear-gradient(to bottom right, rgb(34 197 94), rgb(16 185 129));
    color: white;
    transform: scale(1.05);
}

.step-indicator.pending .step-number {
    background-color: rgb(226 232 240);
    color: rgb(100 116 139);
    border-color: transparent;
}

.dark .step-indicator.pending .step-number {
    background-color: rgb(51 65 85);
    color: rgb(148 163 184);
}

.step-title {
    font-size: 0.75rem;
    text-align: center;
    font-weight: 500;
    transition: all 0.3s;
    max-width: 5rem;
}

.step-indicator.active .step-title {
    color: rgb(147 51 234);
    font-weight: 600;
}

.dark .step-indicator.active .step-title {
    color: rgb(196 181 253);
}

.step-indicator.completed .step-title {
    color: rgb(34 197 94);
}

.dark .step-indicator.completed .step-title {
    color: rgb(74 222 128);
}

.step-indicator.pending .step-title {
    color: rgb(100 116 139);
}

.dark .step-indicator.pending .step-title {
    color: rgb(148 163 184);
}

.step-line {
    position: absolute;
    top: 1.5rem;
    left: 100%;
    width: 100%;
    height: 2px;
    background-color: rgb(226 232 240);
    transition: all 0.3s;
    transform: translateX(50%);
}

.dark .step-line {
    background-color: rgb(51 65 85);
}

.step-indicator.completed .step-line {
    background: linear-gradient(to right, rgb(34 197 94), rgb(147 51 234));
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

@keyframes glow {
    0%, 100% { opacity: 0; transform: translateX(-100%); }
    50% { opacity: 1; transform: translateX(100%); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .step-indicators {
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .step-indicator {
        flex: 1;
        min-width: 0;
    }
    
    .step-title {
        font-size: 0.75rem;
        max-width: none;
    }
    
    .step-line {
        display: none;
    }
}
</style>
