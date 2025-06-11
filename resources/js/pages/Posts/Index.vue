<template>
  <Head title="Posts" />

  <AppLayout>
    <div class="py-8">
      <!-- Posts Container -->
      <div class="max-w-4xl mx-auto px-6">
        <!-- Header con filtros -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <h1 class="text-3xl font-bold text-white">Posts de la Comunidad</h1>
          
          <div class="flex items-center gap-4">
            <!-- Filtros de feed -->
            <div class="flex bg-gray-800 rounded-lg p-1">
              <button
                @click="changeFilter('all')"
                :class="[
                  'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                  filter === 'all' 
                    ? 'bg-primary text-primary-foreground' 
                    : 'text-gray-400 hover:text-white'
                ]"
              >
                Todos
              </button>
              <button
                @click="changeFilter('following')"
                :class="[
                  'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                  filter === 'following' 
                    ? 'bg-primary text-primary-foreground' 
                    : 'text-gray-400 hover:text-white'
                ]"
              >
                Siguiendo
              </button>
            </div>
            
            <!-- Botón crear post -->
            <button
              @click="router.visit('/post/create')"
              class="px-6 py-3 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl transition-colors font-medium flex items-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              <span>Crear Post</span>
            </button>
          </div>
        </div>

        <!-- Mensaje informativo para feed de seguidos vacío -->
        <div v-if="filter === 'following' && posts.length === 0" class="mb-8 bg-blue-900/20 border border-blue-700/50 rounded-lg p-4">
          <div class="flex items-center space-x-3">
            <InfoIcon class="w-5 h-5 text-blue-400 flex-shrink-0" />
            <div>
              <p class="text-blue-300 text-sm">
                No hay posts de las personas que sigues. 
                <Link href="/users/suggestions" class="underline hover:text-blue-200">
                  Descubre nuevos usuarios para seguir
                </Link>
              </p>
            </div>
          </div>
        </div>

        <div class="space-y-8">
          <div
            v-for="post in posts"
            :key="post.id"
            class="bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-xl hover:shadow-2xl hover:bg-gray-800/70 transition-all duration-300 overflow-hidden border border-gray-700/50"
          >
            <!-- Post Header -->
            <div class="p-6 pb-4">
              <div class="flex items-center space-x-4 mb-4">
                <!-- Avatar del usuario del post (clickeable) -->
                <Link :href="route('users.profile', post.user.id)" class="flex-shrink-0">
                  <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-gray-600 shadow-lg hover:border-primary transition-colors">
                    <img 
                      v-if="post.user.avatar_url" 
                      :src="post.user.avatar_url" 
                      :alt="`Avatar de ${post.user.name}`"
                      class="w-full h-full object-cover"
                      @error="handleAvatarError"
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
                  <!-- Nombre del usuario (clickeable) -->
                  <Link :href="route('users.profile', post.user.id)" class="hover:underline">
                    <h3 class="font-semibold text-white">{{ post.user.name }}</h3>
                  </Link>
                  <p class="text-sm text-gray-400 flex items-center">
                    <ClockIcon class="w-4 h-4 mr-1" />
                    {{ formatDate(post.created_at) }}
                  </p>
                </div>
                
                <!-- Menú de opciones del post -->
                <div class="relative">
                  <button 
                    @click="togglePostMenu(post.id)"
                    class="p-2 hover:bg-gray-700 rounded-full transition-colors"
                  >
                    <MoreHorizontalIcon class="w-5 h-5 text-gray-400 hover:text-gray-300" />
                  </button>
                  
                  <!-- Dropdown menu -->
                  <div 
                    v-if="showPostMenu[post.id]" 
                    class="absolute right-0 top-full mt-2 w-48 bg-gray-800 border border-gray-600 rounded-lg shadow-xl z-10"
                    @click.stop
                  >
                    <div class="py-2">
                      <!-- Editar (solo si es el autor) -->
                      <button
                        v-if="post.user.id === $page.props.auth.user.id"
                        @click="editPost(post.id)"
                        class="w-full px-4 py-2 text-left text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center space-x-2"
                      >
                        <EditIcon class="w-4 h-4" />
                        <span>Editar post</span>
                      </button>
                      
                      <!-- Eliminar (solo si es el autor) -->
                      <button
                        v-if="post.user.id === $page.props.auth.user.id"
                        @click="deletePost(post.id)"
                        class="w-full px-4 py-2 text-left text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors flex items-center space-x-2"
                      >
                        <TrashIcon class="w-4 h-4" />
                        <span>Eliminar post</span>
                      </button>
                      
                      <!-- Reportar (si no es el autor) -->
                      <button
                        v-if="post.user.id !== $page.props.auth.user.id"
                        @click="reportPost(post.id)"
                        class="w-full px-4 py-2 text-left text-yellow-400 hover:bg-yellow-500/10 hover:text-yellow-300 transition-colors flex items-center space-x-2"
                      >
                        <FlagIcon class="w-4 h-4" />
                        <span>Reportar post</span>
                      </button>
                    </div>
                  </div>
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
                :src="getImageUrl(post.media)"
                :alt="post.title"
                class="w-full rounded-xl object-cover max-h-96 border border-gray-600 shadow-lg"
                @error="handleImageError"
                loading="lazy"
              />
            </div>

            <!-- Post Actions -->
            <div class="px-6 py-4 border-t border-gray-700">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                  <button 
                    @click="toggleLike(post)"
                    :class="[
                      'flex items-center space-x-2 transition-colors group',
                      post.liked_by_user ? 'text-destructive' : 'text-gray-400 hover:text-destructive'
                    ]"
                    :disabled="likeInProgress[post.id]"
                  >
                    <HeartIcon 
                      :class="[
                        'w-5 h-5 group-hover:scale-110 transition-transform',
                        post.liked_by_user ? 'fill-current' : '',
                        likeInProgress[post.id] ? 'animate-pulse' : ''
                      ]" 
                    />
                    <span class="text-sm font-medium flex items-center">
                      <span>{{ post.liked_by_user ? 'Te gusta' : 'Me gusta' }}</span>
                      <span v-if="post.likes_count > 0" class="ml-1 bg-gray-700 text-gray-300 px-1.5 py-0.5 rounded-full text-xs">
                        {{ post.likes_count }}
                      </span>
                    </span>
                  </button>
                  
                  <button 
                    @click="toggleComments(post.id)"
                    :class="[
                      'flex items-center space-x-2 transition-colors',
                      showComments[post.id] ? 'text-primary' : 'text-gray-400 hover:text-primary'
                    ]"
                  >
                    <MessageCircleIcon class="w-5 h-5" />
                    <span class="text-sm font-medium">
                      {{ post.comments.length }} comentario{{ post.comments.length !== 1 ? 's' : '' }}
                    </span>
                  </button>
                  
                  <button class="flex items-center space-x-2 text-gray-400 hover:text-accent transition-colors">
                    <ShareIcon class="w-5 h-5" />
                    <span class="text-sm font-medium">Compartir</span>
                  </button>
                </div>
                <button class="p-2 hover:bg-gray-700 rounded-full transition-colors">
                  <BookmarkIcon class="w-5 h-5 text-gray-400 hover:text-yellow-400" />
                </button>
              </div>
            </div>

            <!-- Add Comment Form -->
            <div v-if="showComments[post.id]" class="px-6 pb-4 border-t border-gray-700">
              <div class="pt-4 flex space-x-3">
                <!-- Avatar del usuario actual (clickeable) -->
                <Link :href="route('profile.edit')" class="flex-shrink-0">
                  <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-600 shadow-md hover:border-primary transition-colors">
                    <img 
                      v-if="getCurrentUserAvatar()" 
                      :src="getCurrentUserAvatar()" 
                      :alt="`Avatar de ${$page.props.auth.user.name}`"
                      class="w-full h-full object-cover"
                      @error="handleAvatarError"
                    />
                    <div 
                      v-else 
                      class="w-full h-full bg-gradient-to-br from-secondary to-accent flex items-center justify-center text-white font-semibold text-sm"
                    >
                      {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                  </div>
                </Link>
                
                <div class="flex-1">
                  <form @submit.prevent="submitComment(post.id)">
                    <textarea
                      v-model="newComments[post.id]"
                      placeholder="Escribe un comentario..."
                      class="w-full p-3 bg-gray-800 border border-gray-600 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-primary text-gray-200 placeholder-gray-400 text-sm"
                      rows="2"
                      :disabled="submittingComment[post.id]"
                    ></textarea>
                    <div class="flex justify-end mt-2">
                      <button 
                        type="submit"
                        :disabled="!newComments[post.id]?.trim() || submittingComment[post.id]"
                        class="px-4 py-2 bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed text-primary-foreground rounded-lg transition-colors text-sm font-medium shadow-lg"
                      >
                        {{ submittingComment[post.id] ? 'Enviando...' : 'Comentar' }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Comments Section -->
            <div v-if="showComments[post.id] && post.comments.length" class="border-t border-gray-700 bg-gray-900/30">
              <div class="p-6">
                <h4 class="font-semibold text-white mb-4 flex items-center">
                  <MessageSquareIcon class="w-5 h-5 mr-2 text-primary" />
                  Comentarios ({{ post.comments.length }})
                </h4>
                
                <div class="space-y-4">
                  <div
                    v-for="comment in getVisibleComments(post)"
                    :key="comment.id"
                    class="flex space-x-3 p-4 bg-gray-800/60 rounded-xl border border-gray-600/50 hover:bg-gray-800/80 hover:shadow-lg transition-all duration-200"
                  >
                    <!-- Avatar del usuario del comentario (clickeable) -->
                    <Link :href="route('users.profile', comment.user.id)" class="flex-shrink-0">
                      <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-600 shadow-md hover:border-primary transition-colors">
                        <img 
                          v-if="comment.user.avatar_url" 
                          :src="comment.user.avatar_url" 
                          :alt="`Avatar de ${comment.user.name}`"
                          class="w-full h-full object-cover"
                          @error="handleAvatarError"
                        />
                        <div 
                          v-else 
                          class="w-full h-full bg-gradient-to-br from-accent to-primary flex items-center justify-center text-white font-semibold text-sm"
                        >
                          {{ comment.user.name.charAt(0).toUpperCase() }}
                        </div>
                      </div>
                    </Link>
                    
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center space-x-2 mb-1">
                        <!-- Nombre del usuario del comentario (clickeable) -->
                        <Link :href="route('users.profile', comment.user.id)" class="hover:underline">
                          <span class="font-semibold text-white text-sm">{{ comment.user.name }}</span>
                        </Link>
                        <span class="text-xs text-gray-500">•</span>
                        <span class="text-xs text-gray-500">{{ formatDate(comment.created_at) }}</span>
                      </div>
                      <p class="text-gray-300 text-sm leading-relaxed">{{ comment.content }}</p>
                      <div class="flex items-center space-x-4 mt-2">
                        <button class="text-xs text-gray-400 hover:text-primary transition-colors">Responder</button>
                        <button 
                          v-if="comment.user.id === $page.props.auth.user.id"
                          @click="deleteComment(comment.id, post.id)"
                          class="text-xs text-gray-400 hover:text-destructive transition-colors"
                        >
                          Eliminar
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Botón "Ver más" -->
                  <div v-if="post.comments.length > commentsToShow[post.id]" class="text-center">
                    <button 
                      @click="loadMoreComments(post.id)"
                      class="px-4 py-2 text-primary hover:text-primary/80 hover:bg-gray-800/50 rounded-lg transition-colors text-sm font-medium"
                    >
                      Ver más comentarios ({{ post.comments.length - commentsToShow[post.id] }} restantes)
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="!posts.length" class="text-center py-16">
          <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
            <FileTextIcon class="w-12 h-12 text-gray-400" />
          </div>
          <h3 class="text-xl font-semibold text-white mb-2">No hay posts disponibles</h3>
          <p class="text-gray-400 mb-6">
            {{ filter === 'following' ? 'Las personas que sigues no han publicado nada aún' : 'Sé el primero en compartir algo con la comunidad' }}
          </p>
          <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <button
              @click="router.visit('/post/create')"
              class="px-6 py-3 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl transition-colors font-medium"
            >
              Crear mi primer post
            </button>
            <Link
              v-if="filter === 'following'"
              href="/users/suggestions"
              class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors font-medium flex items-center space-x-2"
            >
              <UsersIcon class="w-5 h-5" />
              <span>Descubrir usuarios</span>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
  HeartIcon, 
  MessageCircleIcon, 
  ShareIcon, 
  BookmarkIcon, 
  ClockIcon, 
  MoreHorizontalIcon,
  MessageSquareIcon,
  FileTextIcon,
  EditIcon,
  TrashIcon,
  FlagIcon,
  InfoIcon,
  UsersIcon
} from 'lucide-vue-next'
import axios from 'axios';

const props = defineProps<{
  posts: {
    id: number;
    title: string;
    body: string | null;
    media: string;
    created_at: string;
    likes_count: number;
    liked_by_user: boolean;
    user: {
      id: number;
      name: string;
      avatar_url?: string;
    };
    comments: {
      id: number;
      content: string;
      created_at: string;
      user: {
        id: number;
        name: string;
        avatar_url?: string;
      };
    }[];
  }[];
  filter: string;
  followingCount: number;
}>();

const page = usePage()

// Estados reactivos
const showComments = ref<Record<number, boolean>>({})
const commentsToShow = ref<Record<number, number>>({})
const newComments = ref<Record<number, string>>({})
const submittingComment = ref<Record<number, boolean>>({})
const showPostMenu = ref<Record<number, boolean>>({})
const likeInProgress = ref<Record<number, boolean>>({})

// Inicializar estados
const initializeStates = () => {
  props.posts.forEach(post => {
    showComments.value[post.id] = false
    commentsToShow.value[post.id] = 10
    newComments.value[post.id] = ''
    submittingComment.value[post.id] = false
    showPostMenu.value[post.id] = false
    likeInProgress.value[post.id] = false
  })
}

initializeStates()

// Cerrar menús al hacer click fuera
const handleClickOutside = (event: MouseEvent) => {
  const target = event.target as HTMLElement
  if (!target.closest('.relative')) {
    Object.keys(showPostMenu.value).forEach(key => {
      showPostMenu.value[parseInt(key)] = false
    })
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

// Funciones
const changeFilter = (newFilter: string) => {
  router.visit(`/post?filter=${newFilter}`, {
    preserveState: false,
    preserveScroll: false
  })
}

const getImageUrl = (mediaPath: string) => {
  if (!mediaPath) return ''
  
  if (mediaPath.startsWith('http')) {
    return mediaPath
  }
  
  if (mediaPath.startsWith('/storage/')) {
    return window.location.origin + mediaPath
  }
  
  return window.location.origin + '/storage/' + mediaPath
}

const getCurrentUserAvatar = () => {
  const user = page.props.auth?.user as any
  if (user?.avatar) {
    return asset('storage/' + user.avatar)
  }
  return null
}

const asset = (path: string) => {
  return window.location.origin + '/' + path.replace(/^\//, '')
}

const handleImageError = (event: Event) => {
  const img = event.target as HTMLImageElement
  console.error('Error cargando imagen:', img.src)
}

const handleAvatarError = (event: Event) => {
  const img = event.target as HTMLImageElement
  console.error('Error cargando avatar:', img.src)
  // Opcional: ocultar la imagen y mostrar el fallback
  img.style.display = 'none'
}

const togglePostMenu = (postId: number) => {
  Object.keys(showPostMenu.value).forEach(key => {
    const id = parseInt(key)
    if (id !== postId) {
      showPostMenu.value[id] = false
    }
  })
  showPostMenu.value[postId] = !showPostMenu.value[postId]
}

const editPost = (postId: number) => {
  showPostMenu.value[postId] = false
  router.visit(`/post/${postId}/edit`)
}

const deletePost = (postId: number) => {
  showPostMenu.value[postId] = false
  
  if (!confirm('¿Estás seguro de que quieres eliminar este post? Esta acción no se puede deshacer.')) {
    return
  }

  router.delete(`/post/${postId}`, {
    preserveState: false,
    onSuccess: () => {
      console.log('Post eliminado exitosamente')
    },
    onError: (errors) => {
      console.error('Error al eliminar post:', errors)
      alert('Error al eliminar el post. Por favor, inténtalo de nuevo.')
    }
  })
}

const reportPost = (postId: number) => {
  showPostMenu.value[postId] = false
  alert('Funcionalidad de reporte en desarrollo')
}

const toggleComments = (postId: number) => {
  showComments.value[postId] = !showComments.value[postId]
}

const toggleLike = async (post: any) => {
  // Evitar múltiples clics
  if (likeInProgress.value[post.id]) return;
  
  likeInProgress.value[post.id] = true;
  
  try {
    // Optimistic UI update
    post.liked_by_user = !post.liked_by_user;
    post.likes_count += post.liked_by_user ? 1 : -1;
    
    // Enviar la petición al servidor
    const response = await axios.post(`/posts/${post.id}/like`);
    
    // Actualizar con la respuesta real del servidor
    post.liked_by_user = response.data.liked;
    post.likes_count = response.data.count;
  } catch (error) {
    console.error('Error al dar like:', error);
    
    // Revertir cambios en caso de error
    post.liked_by_user = !post.liked_by_user;
    post.likes_count += post.liked_by_user ? 1 : -1;
    
    alert('Error al procesar tu like. Por favor, inténtalo de nuevo.');
  } finally {
    likeInProgress.value[post.id] = false;
  }
}

const loadMoreComments = (postId: number) => {
  commentsToShow.value[postId] += 10
}

const getVisibleComments = (post: any) => {
  return post.comments.slice(0, commentsToShow.value[post.id])
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60))
  
  if (diffInHours < 1) return 'Hace unos minutos'
  if (diffInHours < 24) return `Hace ${diffInHours}h`
  if (diffInHours < 168) return `Hace ${Math.floor(diffInHours / 24)}d`
  return date.toLocaleDateString()
}

const submitComment = (postId: number) => {
  const commentText = newComments.value[postId]?.trim()
  if (!commentText) return

  submittingComment.value[postId] = true

  router.post('/comments', {
    post_id: postId,
    content: commentText,
  }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
      const response = page.props.flash?.comment
      if (response) {
        const post = props.posts.find(p => p.id === postId)
        if (post) {
          post.comments.unshift(response)
        }
      }
      newComments.value[postId] = ''
    },
    onError: (errors) => {
      console.error('Error al enviar comentario:', errors)
      alert('Error al enviar el comentario')
    },
    onFinish: () => {
      submittingComment.value[postId] = false
    }
  })
}

const deleteComment = (commentId: number, postId: number) => {
  if (!confirm('¿Estás seguro de que quieres eliminar este comentario?')) return

  router.delete(`/comments/${commentId}`, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      const post = props.posts.find(p => p.id === postId)
      if (post) {
        const commentIndex = post.comments.findIndex(c => c.id === commentId)
        if (commentIndex > -1) {
          post.comments.splice(commentIndex, 1)
        }
      }
    },
    onError: (errors) => {
      console.error('Error al eliminar comentario:', errors)
      alert('Error al eliminar el comentario')
    }
  })
}
</script>
