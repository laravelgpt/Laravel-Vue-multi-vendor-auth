<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Props {
  provider: 'google' | 'facebook' | 'github';
  href: string;
}

defineProps<Props>();

const providerConfig = {
  google: {
    name: 'Google',
    icon: 'M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z',
    bgColor: 'bg-white',
    textColor: 'text-slate-700',
    borderColor: 'border-slate-200',
    hoverBg: 'hover:bg-slate-50',
    darkBg: 'dark:bg-slate-700',
    darkText: 'dark:text-slate-200',
    darkBorder: 'dark:border-slate-600',
    darkHover: 'dark:hover:bg-slate-600'
  },
  facebook: {
    name: 'Facebook',
    icon: 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
    bgColor: 'bg-[#1877F2]',
    textColor: 'text-white',
    borderColor: 'border-[#1877F2]',
    hoverBg: 'hover:bg-[#166FE5]',
    darkBg: 'dark:bg-[#1877F2]',
    darkText: 'dark:text-white',
    darkBorder: 'dark:border-[#1877F2]',
    darkHover: 'dark:hover:bg-[#166FE5]'
  },
  github: {
    name: 'GitHub',
    icon: 'M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z',
    bgColor: 'bg-slate-900',
    textColor: 'text-white',
    borderColor: 'border-slate-900',
    hoverBg: 'hover:bg-slate-800',
    darkBg: 'dark:bg-slate-900',
    darkText: 'dark:text-white',
    darkBorder: 'dark:border-slate-900',
    darkHover: 'dark:hover:bg-slate-800'
  }
};
</script>

<template>
  <Link
    :href="href"
    :class="[
      'flex w-full items-center justify-center gap-3 rounded-lg border px-4 py-3 text-sm font-medium shadow-sm transition-colors',
      providerConfig[provider].bgColor,
      providerConfig[provider].textColor,
      providerConfig[provider].borderColor,
      providerConfig[provider].hoverBg,
      providerConfig[provider].darkBg,
      providerConfig[provider].darkText,
      providerConfig[provider].darkBorder,
      providerConfig[provider].darkHover
    ]"
  >
    <svg
      v-if="provider === 'google'"
      class="h-5 w-5"
      viewBox="0 0 24 24"
      fill="currentColor"
    >
      <path :d="providerConfig[provider].icon" />
    </svg>
    <svg
      v-else-if="provider === 'facebook'"
      class="h-5 w-5"
      viewBox="0 0 24 24"
      fill="currentColor"
    >
      <path :d="providerConfig[provider].icon" />
    </svg>
    <svg
      v-else-if="provider === 'github'"
      class="h-5 w-5"
      viewBox="0 0 24 24"
      fill="currentColor"
    >
      <path :d="providerConfig[provider].icon" />
    </svg>
    Continue with {{ providerConfig[provider].name }}
  </Link>
</template>
