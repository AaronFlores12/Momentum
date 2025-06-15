<template>
  <Head title="Iniciar Sesión" />
  
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
        <Link href="/" class="inline-flex items-center space-x-2 mb-6 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
          <ArrowLeft class="w-4 h-4" />
          <span>Volver al inicio</span>
        </Link>
        
        <div class="flex items-center justify-center space-x-2 mb-4">
          <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
            <Users class="w-6 h-6 text-white" />
          </div>
          <span class="text-2xl font-bold text-gray-900 dark:text-white">SocialHub</span>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          ¡Bienvenido de vuelta!
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
          Inicia sesión para continuar conectando
        </p>
      </div>

      <!-- Status Message -->
      <div v-if="status" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <p class="text-sm text-green-600 dark:text-green-400 text-center">{{ status }}</p>
      </div>

      <!-- Form Card -->
      <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/20 dark:border-gray-700/20">
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
                required
                autofocus
                :tabindex="1"
                autocomplete="email"
                v-model="form.email"
                placeholder="tu@email.com"
                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
            <InputError :message="form.errors.email" />
          </div>

          <!-- Password Field -->
          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Contraseña
              </label>
              <TextLink
                v-if="canResetPassword"
                href="/forgot-password"
                :tabindex="5"
                class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 transition-colors"
              >
                ¿Olvidaste tu contraseña?
              </TextLink>
            </div>
            <div class="relative">
              <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="password"
                type="password"
                required
                :tabindex="2"
                autocomplete="current-password"
                v-model="form.password"
                placeholder="Tu contraseña"
                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
            <InputError :message="form.errors.password" />
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <input
              id="remember"
              type="checkbox"
              v-model="form.remember"
              :tabindex="3"
              class="w-4 h-4 text-purple-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-purple-500 focus:ring-2"
            />
            <label for="remember" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
              Recordarme
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :tabindex="4"
            :disabled="form.processing"
            class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-blue-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center space-x-2"
          >
            <LoaderCircle v-if="form.processing" class="w-5 h-5 animate-spin" />
            <LogIn v-else class="w-5 h-5" />
            <span>{{ form.processing ? 'Iniciando sesión...' : 'Iniciar sesión' }}</span>
          </button>

          <!-- Register Link -->
          <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400">
              ¿No tienes una cuenta?
              <TextLink
                href="/register"
                :tabindex="5"
                class="font-medium text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 transition-colors"
              >
                Regístrate gratis
              </TextLink>
            </p>
          </div>
        </form>
      </div>

      <!-- Quick Stats -->
      <div class="mt-8 grid grid-cols-3 gap-4 text-center">
        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-3">
          <div class="text-lg font-bold text-gray-900 dark:text-white">10K+</div>
          <p class="text-xs text-gray-600 dark:text-gray-400">Usuarios</p>
        </div>
        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-3">
          <div class="text-lg font-bold text-gray-900 dark:text-white">50K+</div>
          <p class="text-xs text-gray-600 dark:text-gray-400">Posts</p>
        </div>
        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-3">
          <div class="text-lg font-bold text-gray-900 dark:text-white">100+</div>
          <p class="text-xs text-gray-600 dark:text-gray-400">Grupos</p>
        </div>
      </div>

      <!-- Social Login Options (Optional) -->
      <div class="mt-6 space-y-3">
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">O continúa con</span>
          </div>
        </div>
        
        <div class="grid grid-cols-2 gap-3">
          <button
            type="button"
            class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
          >
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
              <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Google
          </button>
          
          <button
            type="button"
            class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
          >
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Facebook
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import InputError from '@/components/InputError.vue'
import TextLink from '@/components/TextLink.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { 
  Users, 
  Mail, 
  Lock, 
  LogIn, 
  ArrowLeft, 
  LoaderCircle
} from 'lucide-vue-next'

defineProps({
  status: String,
  canResetPassword: Boolean,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  })
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