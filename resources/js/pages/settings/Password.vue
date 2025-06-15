<template>
  <Head title="Configuración de Contraseña" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-8">
      <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Configuración de Seguridad</h1>
          <p class="text-gray-600 dark:text-gray-400">Mantén tu cuenta segura actualizando tu contraseña</p>
        </div>

        <!-- Mensajes de éxito/error -->
        <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700/50 rounded-xl p-4">
          <div class="flex items-center">
            <div class="w-5 h-5 text-green-600 dark:text-green-400 mr-3">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
            <p class="text-green-800 dark:text-green-300">{{ $page.props.flash.success }}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Sidebar de Configuración -->
          <div class="lg:col-span-1">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/20 p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Configuración
              </h3>
              
              <nav class="space-y-2">
                <a 
                  href="/profile" 
                  class="flex items-center space-x-3 px-4 py-3 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <span>Perfil</span>
                </a>
                
                <div class="flex items-center space-x-3 px-4 py-3 text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                  <span class="font-medium">Contraseña</span>
                </div>
                
                <a 
                  href="/settings/notifications" 
                  class="flex items-center space-x-3 px-4 py-3 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                  </svg>
                  <span>Notificaciones</span>
                </a>
                
                <a 
                  href="/settings/privacy" 
                  class="flex items-center space-x-3 px-4 py-3 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                  </svg>
                  <span>Privacidad</span>
                </a>
              </nav>
            </div>
          </div>

          <!-- Formulario de Contraseña -->
          <div class="lg:col-span-3">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/20 p-8">
              <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 flex items-center">
                  <svg class="w-6 h-6 mr-3 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                  Actualizar Contraseña
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                  Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura
                </p>
              </div>

              <!-- Indicadores de Seguridad -->
              <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700/50 rounded-xl p-4">
                  <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3">
                      <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-green-800 dark:text-green-300">Cuenta Verificada</p>
                      <p class="text-xs text-green-600 dark:text-green-400">Email confirmado</p>
                    </div>
                  </div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/50 rounded-xl p-4">
                  <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                      <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Sesión Segura</p>
                      <p class="text-xs text-blue-600 dark:text-blue-400">Conexión encriptada</p>
                    </div>
                  </div>
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700/50 rounded-xl p-4">
                  <div class="flex items-center">
                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mr-3">
                      <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Actualizar Contraseña</p>
                      <p class="text-xs text-yellow-600 dark:text-yellow-400">Recomendado cada 90 días</p>
                    </div>
                  </div>
                </div>
              </div>

              <form @submit.prevent="updatePassword" class="space-y-6">
                <div>
                  <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Contraseña Actual *
                  </label>
                  <div class="relative">
                    <input
                      id="current_password"
                      ref="currentPasswordInput"
                      v-model="form.current_password"
                      type="password"
                      autocomplete="current-password"
                      placeholder="Ingresa tu contraseña actual"
                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all pl-12"
                      :class="{ 'border-red-500 focus:ring-red-500': form.errors.current_password }"
                    />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                      </svg>
                    </div>
                  </div>
                  <p v-if="form.errors.current_password" class="text-red-500 text-sm mt-1">{{ form.errors.current_password }}</p>
                </div>

                <div>
                  <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nueva Contraseña *
                  </label>
                  <div class="relative">
                    <input
                      id="password"
                      ref="passwordInput"
                      v-model="form.password"
                      type="password"
                      autocomplete="new-password"
                      placeholder="Ingresa tu nueva contraseña"
                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all pl-12"
                      :class="{ 'border-red-500 focus:ring-red-500': form.errors.password }"
                    />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                      </svg>
                    </div>
                  </div>
                  <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                  
                  <!-- Indicador de fortaleza de contraseña -->
                  <div v-if="form.password" class="mt-2">
                    <div class="flex items-center space-x-2">
                      <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                        <div 
                          :class="[
                            'h-2 rounded-full transition-all duration-300',
                            getPasswordStrength(form.password).color
                          ]"
                          :style="{ width: getPasswordStrength(form.password).width }"
                        ></div>
                      </div>
                      <span 
                        :class="[
                          'text-xs font-medium',
                          getPasswordStrength(form.password).textColor
                        ]"
                      >
                        {{ getPasswordStrength(form.password).text }}
                      </span>
                    </div>
                  </div>
                </div>

                <div>
                  <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Confirmar Nueva Contraseña *
                  </label>
                  <div class="relative">
                    <input
                      id="password_confirmation"
                      v-model="form.password_confirmation"
                      type="password"
                      autocomplete="new-password"
                      placeholder="Confirma tu nueva contraseña"
                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all pl-12"
                      :class="{ 'border-red-500 focus:ring-red-500': form.errors.password_confirmation }"
                    />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                  </div>
                  <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
                </div>

                <!-- Consejos de Seguridad -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/50 rounded-xl p-4">
                  <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Consejos para una contraseña segura
                  </h4>
                  <ul class="text-sm text-blue-700 dark:text-blue-400 space-y-1">
                    <li>• Usa al menos 8 caracteres</li>
                    <li>• Incluye mayúsculas, minúsculas, números y símbolos</li>
                    <li>• Evita información personal como nombres o fechas</li>
                    <li>• No reutilices contraseñas de otras cuentas</li>
                  </ul>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                  <button
                    type="button"
                    @click="goBack"
                    class="px-6 py-3 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
                  >
                    Cancelar
                  </button>
                  
                  <div class="flex items-center space-x-4">
                    <Transition
                      enter-active-class="transition ease-in-out duration-300"
                      enter-from-class="opacity-0 transform translate-y-2"
                      enter-to-class="opacity-100 transform translate-y-0"
                      leave-active-class="transition ease-in-out duration-300"
                      leave-from-class="opacity-100 transform translate-y-0"
                      leave-to-class="opacity-0 transform translate-y-2"
                    >
                      <p v-show="form.recentlySuccessful" class="text-sm text-green-600 dark:text-green-400 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        ¡Contraseña actualizada!
                      </p>
                    </Transition>
                    
                    <button
                      type="submit"
                      :disabled="form.processing || !form.current_password || !form.password || !form.password_confirmation"
                      class="px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl transition-all font-medium shadow-lg hover:shadow-xl transform hover:scale-105 disabled:transform-none flex items-center space-x-2"
                    >
                      <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                      </svg>
                      <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      <span>{{ form.processing ? 'Actualizando...' : 'Actualizar Contraseña' }}</span>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const breadcrumbs = [
  { title: 'Inicio', href: '/post' },
  { title: 'Configuración', href: '/settings' },
  { title: 'Contraseña', href: '/settings/password' }
]

const passwordInput = ref(null)
const currentPasswordInput = ref(null)

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const getPasswordStrength = (password) => {
  if (!password) return { width: '0%', color: 'bg-gray-300', text: '', textColor: 'text-gray-500' }
  
  let score = 0
  if (password.length >= 8) score++
  if (/[a-z]/.test(password)) score++
  if (/[A-Z]/.test(password)) score++
  if (/[0-9]/.test(password)) score++
  if (/[^A-Za-z0-9]/.test(password)) score++
  
  if (score <= 2) {
    return { width: '25%', color: 'bg-red-500', text: 'Débil', textColor: 'text-red-500' }
  } else if (score <= 3) {
    return { width: '50%', color: 'bg-yellow-500', text: 'Regular', textColor: 'text-yellow-500' }
  } else if (score <= 4) {
    return { width: '75%', color: 'bg-blue-500', text: 'Buena', textColor: 'text-blue-500' }
  } else {
    return { width: '100%', color: 'bg-green-500', text: 'Excelente', textColor: 'text-green-500' }
  }
}

const updatePassword = () => {
  form.put('/profile/password', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    },
    onError: (errors) => {
      if (errors.password) {
        form.reset('password', 'password_confirmation')
        if (passwordInput.value) {
          passwordInput.value.focus()
        }
      }

      if (errors.current_password) {
        form.reset('current_password')
        if (currentPasswordInput.value) {
          currentPasswordInput.value.focus()
        }
      }
    },
  })
}

const goBack = () => {
  router.visit('/profile')
}
</script>
