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
        <h1 class="text-9xl font-bold bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 bg-clip-text text-transparent animate-bounce">
          {{ errorCode }}
        </h1>
      </div>

      <!-- Error Icon -->
      <div class="mb-8 flex justify-center">
        <div class="relative">
          <div class="w-24 h-24 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center shadow-2xl animate-pulse">
            <AlertTriangle class="w-12 h-12 text-white" />
          </div>
          <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center animate-bounce">
            <Info class="w-4 h-4 text-yellow-900" />
          </div>
        </div>
      </div>

      <!-- Error Message -->
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-4 animate-fade-in">
          {{ errorTitle }}
        </h2>
        <p class="text-gray-300 text-lg leading-relaxed animate-fade-in-delay">
          {{ errorMessage }}
        </p>
      </div>

      <!-- Error Details -->
      <div class="mb-8 p-6 bg-gray-500/10 border border-gray-500/20 rounded-2xl backdrop-blur-sm animate-fade-in-delay-2">
        <div class="flex items-center justify-center mb-3">
          <Info class="w-5 h-5 text-gray-400 mr-2" />
          <span class="text-gray-400 font-semibold">Error Details</span>
        </div>
        <p class="text-gray-300 text-sm">
          Error ID: {{ errorId }}<br>
          Timestamp: {{ timestamp }}<br>
          Status: {{ errorCode }} {{ errorTitle }}
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
    <div class="absolute top-10 left-10 animate-float">
      <HelpCircle class="w-8 h-8 text-purple-400/50" />
    </div>
    <div class="absolute top-20 right-20 animate-float-delay">
      <MessageCircle class="w-6 h-6 text-blue-400/50" />
    </div>
    <div class="absolute bottom-20 left-20 animate-float-delay-2">
      <Phone class="w-7 h-7 text-gray-400/50" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { AlertTriangle, Info, HelpCircle, MessageCircle, Phone } from 'lucide-vue-next'

// Props
interface Props {
  status?: number
  message?: string
}

const props = withDefaults(defineProps<Props>(), {
  status: 500,
  message: 'An unexpected error occurred.'
})

// Generate a unique error ID for tracking
const errorId = ref('')
const timestamp = ref('')

// Computed properties for error details
const errorCode = computed(() => props.status.toString())
const errorTitle = computed(() => {
  const titles: Record<number, string> = {
    400: 'Bad Request',
    401: 'Unauthorized',
    402: 'Payment Required',
    403: 'Forbidden',
    404: 'Not Found',
    405: 'Method Not Allowed',
    406: 'Not Acceptable',
    407: 'Proxy Authentication Required',
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
    418: 'I\'m a teapot',
    421: 'Misdirected Request',
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
    511: 'Network Authentication Required'
  }
  return titles[props.status] || 'Unknown Error'
})

const errorMessage = computed(() => {
  const messages: Record<number, string> = {
    400: 'The request could not be understood by the server due to malformed syntax.',
    401: 'Authentication is required to access this resource.',
    402: 'Payment is required to access this resource.',
    403: 'You don\'t have permission to access this resource.',
    404: 'The requested resource could not be found on this server.',
    405: 'The method specified in the request is not allowed for the resource.',
    406: 'The resource identified by the request is only capable of generating response entities which have content characteristics not acceptable according to the accept headers sent in the request.',
    407: 'Proxy authentication is required to access this resource.',
    408: 'The server timed out waiting for the request.',
    409: 'The request could not be completed due to a conflict with the current state of the resource.',
    410: 'The requested resource is no longer available at the server and no forwarding address is known.',
    411: 'The server refuses to accept the request without a defined Content-Length.',
    412: 'The precondition given in one or more of the request-header fields evaluated to false when it was tested on the server.',
    413: 'The server is refusing to process a request because the request entity is larger than the server is willing or able to process.',
    414: 'The server is refusing to service the request because the Request-URI is longer than the server is willing to interpret.',
    415: 'The server is refusing to service the request because the entity of the request is in a format not supported by the requested resource for the requested method.',
    416: 'A server should return a response with this status code if a request included a Range request-header field, and none of the range-specifier values in this field overlap the current extent of the selected resource, and the request did not include an If-Range request-header field.',
    417: 'The expectation given in an Expect request-header field could not be met by this server.',
    418: 'I\'m a teapot. This is a joke response.',
    421: 'The request was directed at a server that is not able to produce a response.',
    422: 'The server understands the content type of the request entity, and the syntax of the request entity is correct, but it was unable to process the contained instructions.',
    423: 'The source or destination resource of a method is locked.',
    424: 'The method could not be performed on the resource because the requested action depended on another action and that action failed.',
    425: 'The server is unwilling to risk processing a request that might be replayed.',
    426: 'The server refuses to perform the request using the current protocol but might be willing to do so after the client upgrades to a different protocol.',
    428: 'The origin server requires the request to be conditional.',
    429: 'The user has sent too many requests in a given amount of time.',
    431: 'The server is unwilling to process the request because either an individual header field, or all the header fields collectively, are too large.',
    451: 'The server is denying access to the resource as a consequence of a legal demand.',
    500: 'The server encountered an unexpected condition which prevented it from fulfilling the request.',
    501: 'The server does not support the functionality required to fulfill the request.',
    502: 'The server, while acting as a gateway or proxy, received an invalid response from the upstream server it accessed in attempting to fulfill the request.',
    503: 'The server is currently unable to handle the request due to a temporary overloading or maintenance of the server.',
    504: 'The server, while acting as a gateway or proxy, did not receive a timely response from the upstream server specified by the URI.',
    505: 'The server does not support, or refuses to support, the HTTP protocol version that was used in the request message.',
    506: 'The server has an internal configuration error: the chosen variant resource is configured to engage in transparent content negotiation itself, and is therefore not a proper end point in the negotiation process.',
    507: 'The server is unable to store the representation needed to complete the request.',
    508: 'The server detected an infinite loop while processing the request.',
    510: 'Further extensions to the request are required for the server to fulfill it.',
    511: 'The client needs to authenticate to gain network access.'
  }
  return messages[props.status] || props.message
})

onMounted(() => {
  errorId.value = `${props.status}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`
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
</style>
