<template>
  <Head title="Descubre usuarios" />

  <AppLayout>
    <div class="py-8">
      <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-white mb-4">Descubre usuarios</h1>
          <p class="text-gray-400">Encuentra nuevas personas para seguir y descubrir contenido interesante.</p>
        </div>

        <!-- Lista de usuarios sugeridos -->
        <div v-if="suggestions.length" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div
            v-for="user in suggestions"
            :key="user.id"
            class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 hover:bg-gray-800/70 transition-all duration-200"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <!-- Avatar -->
                <Link :href="route('users.profile', user.id)" class="flex-shrink-0">
                  <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-600 shadow-lg hover:border-primary transition-colors">
                    <img 
                      v-if="user.avatar_url" 
                      :src="user.avatar_url" 
                      :alt="`Avatar de ${user.name}`"
                      class="w-full h-full object-cover"
                    />
                    <div 
                      v-else 
                      class="w-full h-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-xl"
                    >
                      {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                  </div>
                </Link>
                
                <!-- Información del usuario -->
                <div class="flex-1">
                  <Link :href="route('users.profile', user.id)" class="hover:underline">
                    <h3 class="font-semibold text-white text-lg">{{ user.name }}</h3>
                  </Link>
                  <p v-if="user.bio" class="text-gray-400 text-sm mt-1 line-clamp-2">{{ user.bio }}</p>
                  <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                    <span class="flex items-center">
                      <FileTextIcon class="w-3 h-3 mr-1" />
                      {{ user.posts_count }} posts
                    </span>
                    <span class="flex items-center">
                      <UsersIcon class="w-3 h-3 mr-1" />
                      {{ user.followers_count }} seguidores
                    </span>
                  </div>
                </div>
              </div>
              
              <!-- Botón de seguir -->
              <button
                @click="followUser(user)"
                :disabled="followInProgress[user.id]"
                class="px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-lg transition-colors text-sm font-medium flex items-center space-x-2 disabled:opacity-50"
              >
                <UserPlusIcon class="w-4 h-4" />
                <span>{{ followInProgress[user.id] ? 'Procesando...' : 'Seguir' }}</span>
              </button>
            </div>
          </div>
        </div>
        
        <!-- Estado vacío -->
        <div v-else class="text-center py-16 bg-gray-800/30 rounded-2xl border border-gray-700/50">
          <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
            <UsersIcon class="w-10 h-10 text-gray-400" />
          </div>
          <h3 class="text-xl font-semibold text-white mb-2">No hay sugerencias disponibles</h3>
          <p class="text-gray-400">Parece que ya sigues a todos los usuarios de la plataforma.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
  UsersIcon,
  UserPlusIcon,
  FileTextIcon
} from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps<{
  suggestions: {
    id: number;
    name: string;
    avatar_url: string | null;
    bio: string | null;
    posts_count: number;
    followers_count: number;
  }[];
}>();

const followInProgress = ref<Record<number, boolean>>({});

// Inicializar estados
props.suggestions.forEach(user => {
  followInProgress.value[user.id] = false;
});

const followUser = async (user: any) => {
  if (followInProgress.value[user.id]) return;
  
  followInProgress.value[user.id] = true;
  
  try {
    const response = await axios.post(`/users/${user.id}/follow`);
    
    if (response.data.success) {
      // Redirigir al perfil del usuario
      router.visit(route('users.profile', user.id));
    }
  } catch (error) {
    console.error('Error al seguir al usuario:', error);
    alert('Error al procesar la acción. Por favor, inténtalo de nuevo.');
    followInProgress.value[user.id] = false;
  }
};
</script>
