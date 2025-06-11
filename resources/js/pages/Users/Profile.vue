<template>
  <Head :title="user.name" />

  <AppLayout>
    <div class="py-8">
      <div class="max-w-4xl mx-auto px-6">
        <!-- Cabecera del perfil -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-xl p-8 mb-8 border border-gray-700/50">
          <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <!-- Avatar -->
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-2 border-gray-600 shadow-lg flex-shrink-0">
              <img 
                v-if="user.avatar_url" 
                :src="user.avatar_url" 
                :alt="`Avatar de ${user.name}`"
                class="w-full h-full object-cover"
              />
              <div 
                v-else 
                class="w-full h-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-3xl"
              >
                {{ user.name.charAt(0).toUpperCase() }}
              </div>
            </div>
            
            <!-- Información del usuario -->
            <div class="flex-1 text-center md:text-left">
              <h1 class="text-3xl font-bold text-white mb-2">{{ user.name }}</h1>
              
              <p v-if="user.bio" class="text-gray-300 mb-4 max-w-2xl">{{ user.bio }}</p>
              <p v-else class="text-gray-400 italic mb-4">Sin biografía</p>
              
              <!-- Estadísticas -->
              <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm text-gray-400 mb-4">
                <div class="flex items-center">
                  <FileTextIcon class="w-4 h-4 mr-1" />
                  <span>{{ user.posts_count }} posts</span>
                </div>
                <Link 
                  :href="route('users.followers', user.id)"
                  class="flex items-center hover:text-white transition-colors"
                >
                  <UsersIcon class="w-4 h-4 mr-1" />
                  <span>{{ user.followers_count }} seguidores</span>
                </Link>
                <Link 
                  :href="route('users.following', user.id)"
                  class="flex items-center hover:text-white transition-colors"
                >
                  <UserPlusIcon class="w-4 h-4 mr-1" />
                  <span>{{ user.following_count }} siguiendo</span>
                </Link>
                <div class="flex items-center">
                  <CalendarIcon class="w-4 h-4 mr-1" />
                  <span>Miembro desde {{ formatDate(user.created_at) }}</span>
                </div>
              </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="flex flex-col gap-2 mt-4 md:mt-0">
              <!-- Botón de seguir/dejar de seguir - Solo mostrar si:
                   1. No es el usuario actual
                   2. El usuario está autenticado
              -->
              <button
                v-if="!isCurrentUser && $page.props.auth.user && $page.props.auth.user.id !== user.id"
                @click="toggleFollow"
                :disabled="followInProgress"
                :class="[
                  'px-6 py-2 rounded-lg transition-colors text-sm font-medium flex items-center space-x-2',
                  user.is_following 
                    ? 'bg-gray-600 hover:bg-red-600 text-white' 
                    : 'bg-primary hover:bg-primary/90 text-primary-foreground',
                  followInProgress ? 'opacity-50 cursor-not-allowed' : ''
                ]"
              >
                <UserPlusIcon v-if="!user.is_following" class="w-4 h-4" />
                <UserMinusIcon v-else class="w-4 h-4" />
                <span>
                  {{ followInProgress ? 'Procesando...' : (user.is_following ? 'Dejar de seguir' : 'Seguir') }}
                </span>
              </button>
              
              <!-- Botón de editar perfil - Solo mostrar si es el usuario actual -->
              <Link 
                v-if="isCurrentUser"
                :href="route('profile.edit')" 
                class="px-6 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-lg transition-colors text-sm font-medium text-center"
              >
                Editar perfil
              </Link>
              
              <!-- Botón de mensaje (placeholder) - Solo mostrar si no es el usuario actual y está autenticado -->
              <button
                v-if="!isCurrentUser && $page.props.auth.user && $page.props.auth.user.id !== user.id"
                class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors text-sm font-medium flex items-center justify-center space-x-2"
                disabled
              >
                <MessageCircleIcon class="w-4 h-4" />
                <span>Mensaje</span>
              </button>
              
              <!-- Mensaje para usuarios no autenticados -->
              <div v-if="!$page.props.auth.user && !isCurrentUser" class="text-center">
                <p class="text-gray-400 text-sm mb-2">Inicia sesión para seguir a este usuario</p>
                <Link 
                  :href="route('login')"
                  class="px-6 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-lg transition-colors text-sm font-medium"
                >
                  Iniciar sesión
                </Link>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Posts del usuario -->
        <div class="space-y-6">
          <h2 class="text-2xl font-bold text-white mb-4">Posts de {{ user.name }}</h2>
          
          <div v-if="posts.length" class="space-y-8">
            <div
              v-for="post in posts"
              :key="post.id"
              class="bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-xl hover:shadow-2xl hover:bg-gray-800/70 transition-all duration-300 overflow-hidden border border-gray-700/50"
            >
              <!-- Post Header -->
              <div class="p-6 pb-4">
                <div class="flex items-center space-x-4 mb-4">
                  <Link :href="route('users.profile', post.user.id)" class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-gray-600 shadow-lg">
                      <img 
                        v-if="post.user.avatar_url" 
                        :src="post.user.avatar_url" 
                        :alt="`Avatar de ${post.user.name}`"
                        class="w-full h-full object-cover"
                      />
                      <div 
                        v-else 
                        class="w-full h-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg"
                      >
                        {{ post.user.name.charAt(0).toUpperCase() }}
                      </div>
                    </div>
                  </Link>
                  
                  <div class="flex-1">
                    <Link :href="route('users.profile', post.user.id)" class="hover:underline">
                      <h3 class="font-semibold text-white">{{ post.user.name }}</h3>
                    </Link>
                    <p class="text-sm text-gray-400 flex items-center">
                      <ClockIcon class="w-4 h-4 mr-1" />
                      {{ formatDate(post.created_at) }}
                    </p>
                  </div>
                </div>

                <!-- Post Title -->
                <h2 class="text-xl font-bold text-white mb-3 leading-tight">
                  {{ post.title }}
                </h2>

                <!-- Post Body -->
                <p v-if="post.body" class="text-gray-300 leading-relaxed mb-4">
                  {{ post.body }}
                </p>
              </div>

              <!-- Post Media -->
              <div v-if="post.media" class="px-6 mb-4">
                <img
                  :src="post.media_url"
                  :alt="post.title"
                  class="w-full rounded-xl object-cover max-h-96 border border-gray-600 shadow-lg"
                  loading="lazy"
                />
              </div>

              <!-- Post Actions -->
              <div class="px-6 py-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-6">
                    <Link 
                      :href="route('post.show', post.id)"
                      class="text-gray-400 hover:text-primary transition-colors"
                    >
                      Ver post completo
                    </Link>
                  </div>
                  <div class="flex items-center space-x-2 text-gray-400">
                    <HeartIcon class="w-5 h-5" :class="{ 'fill-current text-red-500': post.liked_by_user }" />
                    <span>{{ post.likes_count }}</span>
                    <span class="mx-2">•</span>
                    <MessageCircleIcon class="w-5 h-5" />
                    <span>{{ post.comments.length }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Estado vacío -->
          <div v-else class="text-center py-16 bg-gray-800/30 rounded-2xl border border-gray-700/50">
            <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
              <FileTextIcon class="w-10 h-10 text-gray-400" />
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">No hay posts disponibles</h3>
            <p class="text-gray-400 mb-6">{{ user.name }} aún no ha publicado nada</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
  FileTextIcon, 
  CalendarIcon,
  ClockIcon,
  HeartIcon,
  MessageCircleIcon,
  UsersIcon,
  UserPlusIcon,
  UserMinusIcon
} from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps<{
  user: {
    id: number;
    name: string;
    bio: string | null;
    avatar_url: string | null;
    posts_count: number;
    comments_count: number;
    followers_count: number;
    following_count: number;
    created_at: string;
    is_following: boolean;
  };
  posts: any[];
  isCurrentUser: boolean;
}>();

const followInProgress = ref(false);

const toggleFollow = async () => {
  if (followInProgress.value) return;
  
  followInProgress.value = true;
  
  try {
    const response = await axios.post(`/users/${props.user.id}/follow`);
    
    if (response.data.success) {
      // Actualizar el estado local
      props.user.is_following = response.data.following;
      props.user.followers_count = response.data.followers_count;
      
      // Mostrar mensaje de éxito (opcional)
      console.log(response.data.message);
    }
  } catch (error) {
    console.error('Error al seguir/dejar de seguir:', error);
    alert('Error al procesar la acción. Por favor, inténtalo de nuevo.');
  } finally {
    followInProgress.value = false;
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
