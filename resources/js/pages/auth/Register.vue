<template>
  <Head title="Crear Cuenta" />
  
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
          Crea tu cuenta
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
          Únete a nuestra comunidad y comienza a conectar
        </p>
      </div>

      <!-- Form Card -->
      <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/20 dark:border-gray-700/20">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Name Field -->
          <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Nombre completo
            </label>
            <div class="relative">
              <User class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="name"
                type="text"
                required
                autofocus
                :tabindex="1"
                autocomplete="name"
                v-model="form.name"
                placeholder="Tu nombre completo"
                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
            <InputError :message="form.errors.name" />
          </div>

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
                :tabindex="2"
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
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Contraseña
            </label>
            <div class="relative">
              <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="password"
                type="password"
                required
                :tabindex="3"
                autocomplete="new-password"
                v-model="form.password"
                placeholder="Mínimo 8 caracteres"
                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
            <InputError :message="form.errors.password" />
          </div>

          <!-- Confirm Password Field -->
          <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Confirmar contraseña
            </label>
            <div class="relative">
              <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="password_confirmation"
                type="password"
                required
                :tabindex="4"
                autocomplete="new-password"
                v-model="form.password_confirmation"
                placeholder="Repite tu contraseña"
                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
            <InputError :message="form.errors.password_confirmation" />
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :tabindex="5"
            :disabled="form.processing"
            class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-blue-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center space-x-2"
          >
            <LoaderCircle v-if="form.processing" class="w-5 h-5 animate-spin" />
            <UserPlus v-else class="w-5 h-5" />
            <span>{{ form.processing ? 'Creando cuenta...' : 'Crear cuenta' }}</span>
          </button>

          <!-- Login Link -->
          <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400">
              ¿Ya tienes una cuenta?
              <Link
                :href="route('login')"
                :tabindex="6"
                class="font-medium text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 transition-colors"
              >
                Iniciar sesión
              </Link>
            </p>
          </div>
        </form>
      </div>

      <!-- Features Preview -->
      <div class="mt-8 grid grid-cols-3 gap-4 text-center">
        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-3">
          <MessageCircle class="w-6 h-6 text-purple-600 dark:text-purple-400 mx-auto mb-1" />
          <p class="text-xs text-gray-600 dark:text-gray-400">Chat</p>
        </div>
        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-3">
          <Share2 class="w-6 h-6 text-blue-600 dark:text-blue-400 mx-auto mb-1" />
          <p class="text-xs text-gray-600 dark:text-gray-400">Compartir</p>
        </div>
        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-3">
          <Heart class="w-6 h-6 text-pink-600 dark:text-pink-400 mx-auto mb-1" />
          <p class="text-xs text-gray-600 dark:text-gray-400">Conectar</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { 
  Users, 
  User, 
  Mail, 
  Lock, 
  UserPlus, 
  ArrowLeft, 
  LoaderCircle,
  MessageCircle,
  Share2,
  Heart
} from 'lucide-vue-next'
import InputError from '@/components/InputError.vue'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
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