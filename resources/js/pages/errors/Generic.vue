<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-900 via-navy-800 to-blue-900 flex items-center justify-center p-4 relative">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay:1s"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-navy-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay:0.5s"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 text-center max-w-2xl mx-auto">
      <!-- Error Code -->
      <div class="mb-8">
        <h1 class="text-9xl font-bold bg-gradient-to-r bg-clip-text text-transparent animate-bounce" :class="getStatusColor(props.status)">
          {{ props.status }}
        </h1>
      </div>

      <!-- Error Icon -->
      <div class="mb-8 flex justify-center">
        <div class="relative">
          <div class="w-24 h-24 bg-gradient-to-r rounded-full flex items-center justify-center shadow-2xl animate-pulse" :class="getStatusColor(props.status)">
            <component :is="getStatusIcon(props.status)" class="w-12 h-12 text-white" />
          </div>
          <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center animate-bounce">
            <Info class="w-4 h-4 text-yellow-900" />
          </div>
        </div>
      </div>

      <!-- Error Message -->
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-4 animate-fade-in">
          {{ getStatusText(props.status) }}
        </h2>
        <p class="text-gray-300 text-lg leading-relaxed animate-fade-in-delay">
          {{ props.message || 'An unexpected error occurred. Please try again later.' }}
        </p>
      </div>

      <!-- Error Details -->
      <div class="mb-8 p-6 bg-gray-500/10 border border-gray-500/20 rounded-2xl backdrop-blur-sm animate-fade-in-delay-2">
        <div class="flex items-center justify-center mb-3">
          <Info class="w-5 h-5 text-gray-400 mr-2" />
          <span class="text-gray-400 font-semibold">Error Details</span>
        </div>
        <p class="text-gray-300 text-sm">
          Error ID: {{ props.errorId }}<br>
          Timestamp: {{ props.timestamp }}<br>
          Status: {{ props.status }} {{ getStatusText(props.status) }}
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-delay-3">
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

        <button
          @click="retry"
          class="group relative px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden"
        >
          <span class="relative z-10">Try Again</span>
          <div class="absolute inset-0 bg-gradient-to-r from-green-700 to-emerald-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </button>
      </div>

      <!-- Help Text -->
      <div class="mt-8 text-gray-400 text-sm animate-fade-in-delay-4">
        <p>If you believe this is an error, please contact our support team.</p>
        <p class="mt-2">We apologize for the inconvenience.</p>
      </div>
    </div>

    <!-- Floating Icons -->
    <div class="absolute top-10 left-10 animate-float pointer-events-none">
      <HelpCircle class="w-8 h-8 text-purple-400/50" />
    </div>
    <div class="absolute top-20 right-20 animate-float-delay pointer-events-none">
      <MessageCircle class="w-6 h-6 text-blue-400/50" />
    </div>
    <div class="absolute bottom-20 left-20 animate-float-delay-2 pointer-events-none">
      <Phone class="w-7 h-7 text-gray-400/50" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { AlertTriangle, Server, RefreshCw, Info, HelpCircle, MessageCircle, Phone } from 'lucide-vue-next'

// Props from the controller
interface Props {
  status: number
  message?: string
  errorId: string
  timestamp: string
  ip?: string
  userAgent?: string
}

const props = defineProps<Props>()

//const showDetails = ref(false)
const retryCount = ref(0)

onMounted(() => {
  // Use the errorId from props instead of generating a new one
  console.log('Error ID:', props.errorId)
  console.log('Timestamp:', props.timestamp)
  console.log('Status:', props.status)
  console.log('Message:', props.message)
})

const goBack = () => {
  if (window.history.length > 1) {
    window.history.back()
  } else {
    router.visit('/')
  }
}

const retry = () => {
  retryCount.value++
  window.location.reload()
}

// const toggleDetails = () => {
//   showDetails.value = !showDetails.value
// }

// Get status text based on status code
const getStatusText = (status: number): string => {
  const statusTexts: Record<number, string> = {
    400: 'Bad Request',
    401: 'Unauthorized',
    403: 'Forbidden',
    404: 'Not Found',
    405: 'Method Not Allowed',
    408: 'Request Timeout',
    409: 'Conflict',
    410: 'Gone',
    411: 'Length Required',
    412: 'Precondition Failed',
    413: 'Payload Too Large',
    414: 'URI Too Long',
    415: 'Unsupported Media Type',
    416: 'Range Not Satisfiable',
    417: 'Expectation Failed',
    418: "I'm a teapot",
    422: 'Unprocessable Entity',
    423: 'Locked',
    424: 'Failed Dependency',
    425: 'Too Early',
    426: 'Upgrade Required',
    428: 'Precondition Required',
    429: 'Too Many Requests',
    431: 'Request Header Fields Too Large',
    451: 'Unavailable For Legal Reasons',
    500: 'Internal Server Error',
    501: 'Not Implemented',
    502: 'Bad Gateway',
    503: 'Service Unavailable',
    504: 'Gateway Timeout',
    505: 'HTTP Version Not Supported',
    506: 'Variant Also Negotiates',
    507: 'Insufficient Storage',
    508: 'Loop Detected',
    510: 'Not Extended',
    511: 'Network Authentication Required',
  }
  
  return statusTexts[status] || 'Unknown Error'
}

// Get status color based on status code
const getStatusColor = (status: number): string => {
  if (status >= 500) return 'from-red-400 via-red-500 to-red-600'
  if (status >= 400) return 'from-orange-400 via-orange-500 to-orange-600'
  if (status >= 300) return 'from-blue-400 via-blue-500 to-blue-600'
  return 'from-gray-400 via-gray-500 to-gray-600'
}

// Get status icon based on status code
const getStatusIcon = (status: number) => {
  if (status >= 500) return Server
  if (status >= 400) return AlertTriangle
  if (status >= 300) return RefreshCw
  return Info
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
</style>
