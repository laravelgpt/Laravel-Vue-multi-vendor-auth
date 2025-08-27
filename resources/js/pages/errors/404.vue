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
        <h1 class="text-9xl font-bold bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 bg-clip-text text-transparent animate-bounce">
          404
        </h1>
      </div>

      <!-- Error Icon -->
      <div class="mb-8 flex justify-center">
        <div class="relative">
          <div class="w-24 h-24 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center shadow-2xl animate-pulse">
            <SearchX class="w-12 h-12 text-white" />
          </div>
          <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center animate-bounce">
            <MapPin class="w-4 h-4 text-yellow-900" />
          </div>
        </div>
      </div>

      <!-- Error Message -->
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-4 animate-fade-in">
          Page Not Found
        </h2>
        <p class="text-gray-300 text-lg leading-relaxed animate-fade-in-delay">
          Oops! The page you're looking for doesn't exist. 
          It might have been moved, deleted, or you entered the wrong URL.
        </p>
      </div>

      <!-- Search Suggestion -->
      <div class="mb-8 p-6 bg-orange-500/10 border border-orange-500/20 rounded-2xl backdrop-blur-sm animate-fade-in-delay-2">
        <div class="flex items-center justify-center mb-3">
          <Search class="w-5 h-5 text-orange-400 mr-2" />
          <span class="text-orange-400 font-semibold">Search Suggestion</span>
        </div>
        <p class="text-gray-300 text-sm">
          Try checking the URL for typos or use our search feature to find what you're looking for.
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
          @click="showSearch = true"
          class="group relative px-8 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden"
        >
          <span class="relative z-10">Search</span>
          <div class="absolute inset-0 bg-gradient-to-r from-orange-700 to-red-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </button>
      </div>

      <!-- Search Modal -->
      <div v-if="showSearch" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 animate-fade-in">
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 max-w-md w-full mx-4 animate-scale-in">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white">Search</h3>
            <button @click="showSearch = false" class="text-gray-400 hover:text-white">
              <X class="w-6 h-6" />
            </button>
          </div>
          <div class="space-y-4">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="What are you looking for?"
              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
              @keyup.enter="performSearch"
            />
            <button
              @click="performSearch"
              class="w-full px-4 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-300"
            >
              Search
            </button>
          </div>
        </div>
      </div>

      <!-- Help Text -->
      <div class="mt-8 text-gray-400 text-sm animate-fade-in-delay-4">
        <p>If you believe this is an error, please contact support.</p>
        <p class="mt-2">Error ID: {{ props.errorId }}</p>
      </div>
    </div>

    <!-- Floating Navigation Icons -->
    <div class="absolute top-10 left-10 animate-float">
      <Compass class="w-8 h-8 text-purple-400/50" />
    </div>
    <div class="absolute top-20 right-20 animate-float-delay">
      <Map class="w-6 h-6 text-blue-400/50" />
    </div>
    <div class="absolute bottom-20 left-20 animate-float-delay-2">
      <Navigation class="w-7 h-7 text-orange-400/50" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { SearchX, MapPin, Search, X, Compass, Map, Navigation } from 'lucide-vue-next'

// Props from the controller
interface Props {
  errorId: string
  timestamp: string
  ip?: string
  userAgent?: string
}

const props = defineProps<Props>()

const showSearch = ref(false)
const searchQuery = ref('')



const goBack = () => {
  if (window.history.length > 1) {
    window.history.back()
  } else {
    router.visit('/')
  }
}

const performSearch = () => {
  if (searchQuery.value.trim()) {
    // You can implement actual search functionality here
    console.log('Searching for:', searchQuery.value)
    showSearch.value = false
    searchQuery.value = ''
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

@keyframes scale-in {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
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

.animate-scale-in {
  animation: scale-in 0.3s ease-out;
}
</style>
