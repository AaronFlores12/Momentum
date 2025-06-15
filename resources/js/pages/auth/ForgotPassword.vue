<template>
  <Head title="Recuperar Contraseña" />
  
  <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-purple-900 flex items-center justify-center p-6">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
      <div class="absolute top-40 left-40 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative w-full max-w-md">
      <!-- Header -->
      <div class="text-center mb-8">
        <Link href="/login" class="inline-flex items-center space-x-2 mb-6 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
          <ArrowLeft class="w-4 h-4" />
          <span>Volver al login</span>
        </Link>
        
        <div class="flex items-center justify-center space-x-2 mb-4">
          <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
            <Users class="w-6 h-6 text-white" />
          </div>
          <span class="text-2xl font-bold text-gray-900 dark:text-white">SocialHub</span>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          ¿Olvidaste tu contraseña?
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
          No te preocupes, te enviaremos un enlace para restablecerla
        </p>
      </div>

      <!-- Status Message -->
      <div v-if="status" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-center space-x-2">
          <CheckCircle class="w-5 h-5 text-green-600 dark:text-green-400" />
          <p class="text-sm text-green-600 dark:text-green-400">{{ status }}</p>
        </div>
      </div>

      <!-- Form Card -->
      <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/20 dark:border-gray-700/20">
        <!-- Icon -->
        <div class="flex justify-center mb-6">
          <div class="w-16 h-16 bg-gradient-to-r from-purple-100 to-blue-100 dark:from-purple-900/30 dark:to-blue-900/30 rounded-full flex items-center justify-center">
            <KeyRound class="w-8 h-8 text-purple-600 dark:text-purple-400" />
          </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
          <!-- Email Field -->
          <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Correo electrónico
            </label>
            <div class="relative">
              <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="email"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="email"
                v-model="form.email"
                placeholder="tu@email.com"
                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
            <InputError :message="form.errors.email" />
            <p class="text-xs text-gray-500 dark:text-gray-400">
              Ingresa el correo asociado a tu cuenta
            </p>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-blue-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center space-x-2"
          >
            <LoaderCircle v-if="form.processing" class="w-5 h-5 animate-spin" />
            <Send v-else class="w-5 h-5" />
            <span>{{ form.processing ? 'Enviando enlace...' : 'Enviar enlace de recuperación' }}</span>
          </button>

          <!-- Back to Login -->
          <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400">
              ¿Recordaste tu contraseña?
              <TextLink
                href="/login"
                class="font-medium text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 transition-colors"
              >
                Volver al login
              </TextLink>
            </p>
          </div>
        </form>
      </div>

      <!-- Help Section -->
      <div class="mt-8 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-white/20 dark:border-gray-700/20">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
          <HelpCircle class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" />
          ¿Necesitas ayuda?
        </h3>
        <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
          <div class="flex items-start space-x-2">
            <div class="w-1.5 h-1.5 bg-purple-600 rounded-full mt-2 flex-shrink-0"></div>
            <p>Revisa tu carpeta de spam si no recibes el correo</p>
          </div>
          <div class="flex items-start space-x-2">
            <div class="w-1.5 h-1.5 bg-purple-600 rounded-full mt-2 flex-shrink-0"></div>
            <p>El enlace de recuperación expira en 60 minutos</p>
          </div>
          <div class="flex items-start space-x-2">
            <div class="w-1.5 h-1.5 bg-purple-600 rounded-full mt-2 flex-shrink-0"></div>
            <p>¿Problemas? Contacta a nuestro soporte</p>
          </div>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
          <button class="inline-flex items-center text-sm text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 transition-colors">
            <MessageCircle class="w-4 h-4 mr-2" />
            Contactar soporte
          </button>
        </div>
      </div>

      <!-- Security Note -->
      <div class="mt-6 text-center">
        <div class="inline-flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400 bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-full px-3 py-2">
          <Shield class="w-3 h-3" />
          <span>Tus datos están protegidos y seguros</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { 
  Users, 
  Mail, 
  KeyRound, 
  Send, 
  ArrowLeft, 
  LoaderCircle,
  CheckCircle,
  HelpCircle,
  MessageCircle,
  Shield
} from 'lucide-vue-next'
import InputError from '@/components/InputError.vue'
import TextLink from '@/components/TextLink.vue'

const form = useForm({
  email: '',
})

defineProps({
  status: String,
})



const submit = () => {
  // Opción 1: Usar la ruta directa
  form.post('/forgot-password')
  
  // Opción 2: Si tienes route() disponible globalmente, descomenta la línea siguiente:
  // form.post(route('password.email'))
}
</script>

<style scoped>
@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}
</style>