<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { User, LogOut } from 'lucide-vue-next'

interface Props {
  user: {
    id: number
    name: string
    email: string
    avatar?: string
  }
}

const props = defineProps<Props>()

// Función route helper
const route = (name, params = {}) => {
  const routes = {
    'profile.edit': '/profile'
  }
  return routes[name] || '/'
}

const logout = () => {
  router.post('/logout')
}
</script>

<template>
  <div class="py-2">
    <!-- Profile Link -->
    <Link 
      :href="route('profile.edit')" 
      class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-all rounded-lg mx-2"
    >
      <User class="w-4 h-4" />
      <span class="font-medium">Mi Perfil</span>
    </Link>
    
    <!-- Divider -->
    <hr class="my-2 border-gray-200 dark:border-gray-700" />
    
    <!-- Logout Button -->
    <button 
      @click="logout"
      class="w-full flex items-center space-x-3 px-4 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-700 dark:hover:text-red-300 transition-all rounded-lg mx-2"
    >
      <LogOut class="w-4 h-4" />
      <span class="font-medium">Cerrar Sesión</span>
    </button>
  </div>
</template>
