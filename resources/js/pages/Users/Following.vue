<template>
  <Head :title="`${user.name} está siguiendo`" />

  <AppLayout>
    <div class="py-8">
      <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="mb-8">
          <Link :href="route('users.profile', user.id)" class="inline-flex items-center text-gray-400 hover:text-white transition-colors mb-4">
            <ArrowLeftIcon class="w-5 h-5 mr-2" />
            Volver al perfil
          </Link>
          
          <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-600 shadow-lg flex-shrink-0">
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
            
            <div>
              <h1 class="text-3xl font-bold text-white">{{ user.name }} está siguiendo</h1>
              <p class="text-gray-400">{{ following.length }} usuarios</p>
            </div>
          </div>
        </div>

        <!-- Lista de usuarios seguidos -->
        <div v-if="following.length" class="space-y-4">
          <div
            v-for="followedUser in following"
            :key="followedUser.id"
            class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 hover:bg-gray-800/70 transition-all duration-200"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <!-- Avatar -->
                <Link :href="route('users.profile', followedUser.id)" class="flex-shrink-0">
                  <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-gray-600 shadow-lg hover:border-primary transition-colors">
                    <img 
                      v-if="followedUser.avatar_url" 
                      :src="followedUser.avatar_url" 
                      :alt="`Avatar de ${followedUser.name}`"
                      class="w-full h-full object-cover"
                    />
                    <div 
                      v-else 
                      class="w-full h-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg"
                    >
                      {{ followedUser.name.charAt(0).toUpperCase() }}
                    </div>
                  </div>
                </Link>
                
                <!-- Información del usuario -->
                <div class="flex-1">
                  <Link :href="route('users.profile', followedUser.id)" class="hover:underline">
                    <h3 class="font-semibold text-white">{{ followedUser.name }}</h3>
                  </Link>
                  <p v-if="followedUser.bio" class="text-gray-400 text-sm mt-1">{{ followedUser.bio }}</p>
                  <p class="text-gray-500 text-xs mt-1">
                    Siguiendo desde {{ formatDate(followedUser.followed_at) }}
                  </p>
                </div>
              </div>
              
              <!-- Botón de seguir/dejar de seguir -->
              <button
                v-if="$page.props.auth.user && followedUser.id !== $page.props.auth.user.id"
                @click="toggleFollow(followedUser)"
                :disabled="followInProgress[followedUser.id]"
                :class="[
                  'px-4 py-2 rounded-lg transition-colors text-sm font-medium flex items-center space-x-2',
                  followedUser.is_following 
                    ? 'bg-gray-600 hover:bg-red-600 text-white' 
                    : 'bg-primary hover:bg-primary/90 text-primary-foreground',
                  followInProgress[followedUser.id] ? 'opacity-50 cursor-not-allowed' : ''
                ]"
              >
                <UserPlusIcon v-if="!followedUser.is_following" class="w-4 h-4" />
                <UserMinusIcon v-else class="w-4 h-4" />
                <span>
                  {{ followInProgress[followedUser.id] ? 'Procesando...' : (followedUser.is_following ? 'Dejar de seguir' : 'Seguir') }}
                </span>
              </button>
            </div>
          </div>
        </div>
        
        <!-- Estado vacío -->
        <div v-else class="text-center py-16 bg-gray-800/30 rounded-2xl border border-gray-700/50">
          <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
            <UserPlusIcon class="w-10 h-10 text-gray-400" />
          </div>
          <h3 class="text-xl font-semibold text-white mb-2">No sigue a nadie</h3>
          <p class="text-gray-400">{{ user.name }} aún no sigue a ningún usuario</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
  ArrowLeftIcon,
  UserPlusIcon,
  UserMinusIcon
} from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps<{
  user: {
    id: number;
    name: string;
    avatar_url: string | null;
  };
  following: {
    id: number;
    name: string;
    avatar_url: string | null;
    bio: string | null;
    followed_at: string;
    is_following: boolean;
  }[];
}>();

const followInProgress = ref<Record<number, boolean>>({});

// Inicializar estados
props.following.forEach(followedUser => {
  followInProgress.value[followedUser.id] = false;
});

const toggleFollow = async (followedUser: any) => {
  if (followInProgress.value[followedUser.id]) return;
  
  followInProgress.value[followedUser.id] = true;
  
  try {
    const response = await axios.post(`/users/${followedUser.id}/follow`);
    
    if (response.data.success) {
      followedUser.is_following = response.data.following;
    }
  } catch (error) {
    console.error('Error al seguir/dejar de seguir:', error);
    alert('Error al procesar la acción. Por favor, inténtalo de nuevo.');
  } finally {
    followInProgress.value[followedUser.id] = false;
  }
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>
