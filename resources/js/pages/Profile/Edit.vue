<template>
  <Head title="Mi Perfil" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-8">
      <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Mi Perfil</h1>
          <p class="text-gray-600 dark:text-gray-400">Administra tu información personal y configuración de cuenta</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Profile Card -->
          <div class="lg:col-span-1">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/20 p-6">
              <div class="text-center">
                <!-- Avatar -->
                <div class="relative inline-block mb-4">
                  <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-200 dark:border-gray-600 shadow-lg mx-auto">
                    <img 
                      v-if="user.avatar" 
                      :src="getAvatarUrl(user.avatar)" 
                      :alt="user.name"
                      class="w-full h-full object-cover"
                    />
                    <div 
                      v-else 
                      class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold text-2xl"
                    >
                      {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                  </div>
                  <button 
                    @click="$refs.avatarInput.click()"
                    class="absolute bottom-0 right-0 w-8 h-8 bg-purple-600 hover:bg-purple-700 text-white rounded-full flex items-center justify-center shadow-lg transition-all transform hover:scale-110"
                  >
                    <Camera class="w-4 h-4" />
                  </button>
                  <input 
                    ref="avatarInput"
                    type="file" 
                    accept="image/*" 
                    @change="uploadAvatar"
                    class="hidden"
                  />
                </div>

                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ user.name }}</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-4">{{ user.email }}</p>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 text-center">
                  <div>
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ userStats.posts }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Posts</div>
                  </div>
                  <div>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ userStats.followers }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Seguidores</div>
                  </div>
                  <div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ userStats.following }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Siguiendo</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Edit Form -->
          <div class="lg:col-span-2">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/20 p-6">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                <Edit class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" />
                Editar Información
              </h3>

              <form @submit.prevent="updateProfile" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Nombre Completo
                    </label>
                    <input
                      v-model="form.name"
                      type="text"
                      required
                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all"
                      placeholder="Tu nombre completo"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Email
                    </label>
                    <input
                      v-model="form.email"
                      type="email"
                      required
                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all"
                      placeholder="tu@email.com"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Biografía
                  </label>
                  <textarea
                    v-model="form.bio"
                    rows="4"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all resize-none"
                    placeholder="Cuéntanos algo sobre ti..."
                  ></textarea>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                  <button
                    type="button"
                    @click="resetForm"
                    class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all font-medium"
                  >
                    Cancelar
                  </button>
                  <button
                    type="submit"
                    :disabled="loading"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-xl transition-all font-medium shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:transform-none"
                  >
                    {{ loading ? 'Guardando...' : 'Guardar Cambios' }}
                  </button>
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
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Edit, Camera } from 'lucide-vue-next'

const props = defineProps({
  user: Object,
  userStats: {
    type: Object,
    default: () => ({ posts: 0, followers: 0, following: 0 })
  }
})

const breadcrumbs = [
  { title: 'Inicio', href: '/post' },
  { title: 'Mi Perfil', href: '/profile' }
]

const loading = ref(false)

const form = ref({
  name: props.user.name,
  email: props.user.email,
  bio: props.user.bio || ''
})

const getAvatarUrl = (avatar) => {
  if (!avatar) return null
  if (avatar.startsWith('http')) return avatar
  return window.location.origin + '/storage/' + avatar
}

const resetForm = () => {
  form.value = {
    name: props.user.name,
    email: props.user.email,
    bio: props.user.bio || ''
  }
}

const updateProfile = () => {
  loading.value = true
  
  // Usar POST method para actualizar
  router.post('/profile/update', form.value, {
    preserveState: true,
    onSuccess: () => {
      alert('Perfil actualizado exitosamente')
    },
    onError: (errors) => {
      console.error('Error al actualizar perfil:', errors)
      alert('Error al actualizar el perfil')
    },
    onFinish: () => {
      loading.value = false
    }
  })
}

const uploadAvatar = (event) => {
  const file = event.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('avatar', file)

  // Usar POST para subir avatar
  router.post('/profile/avatar', formData, {
    preserveState: true,
    onSuccess: () => {
      alert('Avatar actualizado exitosamente')
      // Recargar la página para mostrar el nuevo avatar
      window.location.reload()
    },
    onError: (errors) => {
      console.error('Error al subir avatar:', errors)
      alert('Error al subir el avatar')
    }
  })
}
</script>
