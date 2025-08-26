<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-900 via-navy-800 to-blue-900 flex items-center justify-center p-4">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-navy-500/10 rounded-full blur-3xl animate-pulse delay-500"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 text-center max-w-2xl mx-auto">
      <!-- Error Code -->
      <div class="mb-8">
        <h1 class="text-9xl font-bold bg-gradient-to-r from-red-400 via-red-500 to-red-600 bg-clip-text text-transparent animate-bounce">
          500
        </h1>
      </div>

      <!-- Error Icon -->
      <div class="mb-8 flex justify-center">
        <div class="relative">
          <div class="w-24 h-24 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-2xl animate-pulse">
            <ServerCrash class="w-12 h-12 text-white" />
          </div>
          <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center animate-bounce">
            <AlertCircle class="w-4 h-4 text-yellow-900" />
          </div>
        </div>
      </div>

      <!-- Error Message -->
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-4 animate-fade-in">
          Internal Server Error
        </h2>
        <p class="text-gray-300 text-lg leading-relaxed animate-fade-in-delay">
          Something went wrong on our end. Our team has been notified and is working to fix the issue.
        </p>
      </div>

      <!-- Technical Details -->
      <div class="mb-8 p-6 bg-red-500/10 border border-red-500/20 rounded-2xl backdrop-blur-sm animate-fade-in-delay-2">
        <div class="flex items-center justify-center mb-3">
          <Bug class="w-5 h-5 text-red-400 mr-2" />
          <span class="text-red-400 font-semibold">Technical Details</span>
        </div>
        <p class="text-gray-300 text-sm">
          Error ID: {{ errorId }}<br>
          Timestamp: {{ timestamp }}<br>
          Status: {{ status }}
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-delay-3">
        <button
          @click="retry"
          class="group relative px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden"
        >
          <span class="relative z-10">Try Again</span>
          <div class="absolute inset-0 bg-gradient-to-r from-green-700 to-emerald-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </button>

        <Link
          href="/"
          class="group relative px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden"
        >
          <span class="relative z-10">Go Home</span>
          <div class="absolute inset-0 bg-gradient-to-r from-purple-700 to-blue-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </Link>

        <button
          @click="goBack"
          class="group relative px-8 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden"
        >
          <span class="relative z-10">Go Back</span>
          <div class="absolute inset-0 bg-gradient-to-r from-gray-700 to-gray-800 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </button>
      </div>

      <!-- Status Updates -->
      <div class="mt-8 p-6 bg-blue-500/10 border border-blue-500/20 rounded-2xl backdrop-blur-sm animate-fade-in-delay-4">
        <div class="flex items-center justify-center mb-3">
          <Activity class="w-5 h-5 text-blue-400 mr-2" />
          <span class="text-blue-400 font-semibold">Status Updates</span>
        </div>
        <div class="space-y-2 text-sm">
          <div class="flex items-center justify-between">
            <span class="text-gray-300">System Status:</span>
            <span class="text-green-400 font-semibold">Monitoring</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-gray-300">Response Time:</span>
            <span class="text-yellow-400 font-semibold">Investigating</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-gray-300">ETA:</span>
            <span class="text-blue-400 font-semibold">Soon</span>
          </div>
        </div>
      </div>

      <!-- Help Text -->
      <div class="mt-8 text-gray-400 text-sm animate-fade-in-delay-5">
        <p>If this problem persists, please contact our support team.</p>
        <p class="mt-2">We apologize for the inconvenience.</p>
      </div>
    </div>

    <!-- Floating Technical Icons -->
    <div class="absolute top-10 left-10 animate-float">
      <Server class="w-8 h-8 text-purple-400/50" />
    </div>
    <div class="absolute top-20 right-20 animate-float-delay">
      <Database class="w-6 h-6 text-blue-400/50" />
    </div>
    <div class="absolute bottom-20 left-20 animate-float-delay-2">
      <Wrench class="w-7 h-7 text-red-400/50" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { ServerCrash, AlertCircle, Bug, Activity, Server, Database, Wrench } from 'lucide-vue-next'

// Generate a unique error ID for tracking
const errorId = ref('')
const timestamp = ref('')
const status = ref('500 Internal Server Error')

onMounted(() => {
  errorId.value = '500-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9)
  timestamp.value = new Date().toISOString()
})

const retry = () => {
  window.location.reload()
}

const goBack = () => {
  if (window.history.length > 1) {
    router.visit(window.history.back())
  } else {
    router.visit('/')
  }
}
</script>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

@keyframes fade-in {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

.animate-float-delay {
  animation: float 3s ease-in-out infinite 1s;
}

.animate-float-delay-2 {
  animation: float 3s ease-in-out infinite 2s;
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out;
}

.animate-fade-in-delay {
  animation: fade-in 0.8s ease-out 0.2s both;
}

.animate-fade-in-delay-2 {
  animation: fade-in 0.8s ease-out 0.4s both;
}

.animate-fade-in-delay-3 {
  animation: fade-in 0.8s ease-out 0.6s both;
}

.animate-fade-in-delay-4 {
  animation: fade-in 0.8s ease-out 0.8s both;
}

.animate-fade-in-delay-5 {
  animation: fade-in 0.8s ease-out 1s both;
}
</style>
