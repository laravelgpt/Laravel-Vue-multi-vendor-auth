<script setup lang="ts">
import { Chrome, Facebook, Github, Apple } from 'lucide-vue-next';

interface Props {
    provider: 'google' | 'facebook' | 'github' | 'apple';
}

const props = defineProps<Props>();

const providerConfig = {
    google: {
        name: 'Google',
        icon: Chrome,
        color: 'text-red-600',
        bgColor: 'bg-white hover:bg-gray-50',
        borderColor: 'border-gray-300 hover:border-gray-400',
        iconColor: 'text-red-600'
    },
    facebook: {
        name: 'Facebook',
        icon: Facebook,
        color: 'text-blue-600',
        bgColor: 'bg-white hover:bg-gray-50',
        borderColor: 'border-gray-300 hover:border-gray-400',
        iconColor: 'text-blue-600'
    },
    github: {
        name: 'GitHub',
        icon: Github,
        color: 'text-gray-900',
        bgColor: 'bg-white hover:bg-gray-50',
        borderColor: 'border-gray-300 hover:border-gray-400',
        iconColor: 'text-gray-900'
    },
    apple: {
        name: 'Apple',
        icon: Apple,
        color: 'text-gray-900',
        bgColor: 'bg-white hover:bg-gray-50',
        borderColor: 'border-gray-300 hover:border-gray-400',
        iconColor: 'text-gray-900'
    }
};
</script>

<template>
    <Link
        :href="`/auth/${provider}`"
        :class="[
            'flex items-center justify-center w-full px-4 py-3 text-sm font-medium rounded-lg border transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5',
            providerConfig[provider].bgColor,
            providerConfig[provider].borderColor,
            providerConfig[provider].color
        ]"
    >
        <component 
            :is="providerConfig[provider].icon" 
            class="w-5 h-5 mr-3" 
            :class="providerConfig[provider].iconColor"
        />
        <span class="font-medium">Continue with {{ providerConfig[provider].name }}</span>
    </Link>
</template>
