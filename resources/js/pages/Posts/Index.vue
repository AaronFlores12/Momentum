<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router, usePage, Link, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { 
    Heart, 
    MessageCircle, 
    Share, 
    Bookmark, 
    Clock, 
    MoreHorizontal,
    MessageSquare,
    FileText,
    Edit,
    Trash,
    Flag,
    Info,
    Users,
    Plus,
    UserCheck,
    Home,
    UserPlus,
    Settings,
    Bell,
    User,
    LogOut,
    Menu,
    ChevronDown
} from 'lucide-vue-next'
import axios from 'axios'

const props = defineProps({
    posts: Array,
    filter: String,
    followingCount: Number,
})

const page = usePage()

// Breadcrumbs
const breadcrumbs = [
    { title: 'Inicio', href: '/post' },
    { title: 'Posts', href: '/post' }
]

// Estados reactivos
const showComments = ref({})
const commentsToShow = ref({})
const newComments = ref({})
const submittingComment = ref({})
const showPostMenu = ref({})
const likeInProgress = ref({})

// Inicializar estados
const initializeStates = () => {
    if (props.posts) {
        props.posts.forEach(post => {
            showComments.value[post.id] = false
            commentsToShow.value[post.id] = 10
            newComments.value[post.id] = ''
            submittingComment.value[post.id] = false
            showPostMenu.value[post.id] = false
            likeInProgress.value[post.id] = false
        })
    }
}

initializeStates()

// Cerrar menús al hacer click fuera
const handleClickOutside = (event) => {
    const target = event.target
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
const changeFilter = (newFilter) => {
    router.visit(`/post?filter=${newFilter}`, {
        preserveState: false,
        preserveScroll: false
    })
}

const getImageUrl = (mediaPath) => {
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
    const user = page.props.auth?.user
    if (user?.avatar) {
        return asset('storage/' + user.avatar)
    }
    return null
}

const asset = (path) => {
    return window.location.origin + '/' + path.replace(/^\//, '')
}

const handleImageError = (event) => {
    const img = event.target
    console.error('Error cargando imagen:', img.src)
}

const handleAvatarError = (event) => {
    const img = event.target
    console.error('Error cargando avatar:', img.src)
    img.style.display = 'none'
}

const togglePostMenu = (postId) => {
    Object.keys(showPostMenu.value).forEach(key => {
        const id = parseInt(key)
        if (id !== postId) {
            showPostMenu.value[id] = false
        }
    })
    showPostMenu.value[postId] = !showPostMenu.value[postId]
}

const editPost = (postId) => {
    showPostMenu.value[postId] = false
    router.visit(`/post/${postId}/edit`)
}

const deletePost = (postId) => {
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

const reportPost = (postId) => {
    showPostMenu.value[postId] = false
    alert('Funcionalidad de reporte en desarrollo')
}

const toggleComments = (postId) => {
    showComments.value[postId] = !showComments.value[postId]
}

const toggleLike = async (post) => {
    if (likeInProgress.value[post.id]) return

    likeInProgress.value[post.id] = true

    try {
        post.liked_by_user = !post.liked_by_user
        post.likes_count += post.liked_by_user ? 1 : -1
        
        const response = await axios.post(`/post/${post.id}/like`)
        
        post.liked_by_user = response.data.liked
        post.likes_count = response.data.count
    } catch (error) {
        console.error('Error al dar like:', error)
        
        post.liked_by_user = !post.liked_by_user
        post.likes_count += post.liked_by_user ? 1 : -1
        
        alert('Error al procesar tu like. Por favor, inténtalo de nuevo.')
    } finally {
        likeInProgress.value[post.id] = false
    }
}

const loadMoreComments = (postId) => {
    commentsToShow.value[postId] += 10
}

const getVisibleComments = (post) => {
    return post.comments.slice(0, commentsToShow.value[post.id])
}

const formatDate = (dateString) => {
    const date = new Date(dateString)
    const now = new Date()
    const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60))

    if (diffInHours < 1) return 'Hace unos minutos'
    if (diffInHours < 24) return `Hace ${diffInHours}h`
    if (diffInHours < 168) return `Hace ${Math.floor(diffInHours / 24)}d`
    return date.toLocaleDateString()
}

const submitComment = (postId) => {
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

const deleteComment = (commentId, postId) => {
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

// Función route simulada (ajusta según tu configuración)
const route = (name, params) => {
    if (name === 'users.profile') {
        return `/users/${params}`
    }
    if (name === 'profile.edit') {
        return '/profile'
    }
    return '/'
}
</script>

<template>
    <Head title="Posts" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-8">
            <!-- Posts Container -->
            <div class="max-w-4xl mx-auto px-6">
                <!-- Header con filtros -->
                <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Posts de la Comunidad</h1>
                        <p class="text-gray-600 dark:text-gray-400">Descubre y comparte momentos increíbles</p>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Filtros de feed -->
                        <div class="flex bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl p-1 border border-gray-200 dark:border-gray-700 shadow-lg">
                            <button
                                @click="changeFilter('all')"
                                :class="[
                                    'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                    filter === 'all' 
                                        ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg transform scale-105' 
                                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700'
                                ]"
                            >
                                <Users class="w-4 h-4 mr-2 inline" />
                                Todos
                            </button>
                            <button
                                @click="changeFilter('following')"
                                :class="[
                                    'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                    filter === 'following' 
                                        ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg transform scale-105' 
                                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700'
                                ]"
                            >
                                <UserCheck class="w-4 h-4 mr-2 inline" />
                                Siguiendo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mensaje informativo para feed de seguidos vacío -->
                <div v-if="filter === 'following' && posts.length === 0" class="mb-8 bg-blue-50/80 dark:bg-blue-900/20 backdrop-blur-sm border border-blue-200 dark:border-blue-700/50 rounded-xl p-6 shadow-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <Info class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-1">Tu feed está vacío</h3>
                            <p class="text-blue-700 dark:text-blue-400 text-sm">
                                No hay posts de las personas que sigues. 
                                <Link href="/users/suggestions" class="underline hover:text-blue-600 dark:hover:text-blue-300 font-medium">
                                    Descubre nuevos usuarios para seguir
                                </Link>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        v-for="post in posts"
                        :key="post.id"
                        class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden border border-white/20 dark:border-gray-700/20 hover:transform hover:scale-[1.02]"
                    >
                        <!-- Post Header -->
                        <div class="p-6 pb-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <!-- Avatar del usuario del post -->
                                <Link :href="route('users.profile', post.user.id)" class="flex-shrink-0 group">
                                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-gray-200 dark:border-gray-600 shadow-lg group-hover:border-purple-400 dark:group-hover:border-purple-500 transition-colors">
                                        <img 
                                            v-if="post.user.avatar_url" 
                                            :src="post.user.avatar_url" 
                                            :alt="`Avatar de ${post.user.name}`"
                                            class="w-full h-full object-cover"
                                            @error="handleAvatarError"
                                        />
                                        <div 
                                            v-else 
                                            class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold text-lg"
                                        >
                                            {{ post.user.name.charAt(0).toUpperCase() }}
                                        </div>
                                    </div>
                                </Link>
                                
                                <div class="flex-1">
                                    <Link :href="route('users.profile', post.user.id)" class="hover:underline group">
                                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ post.user.name }}</h3>
                                    </Link>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                        <Clock class="w-4 h-4 mr-1" />
                                        {{ formatDate(post.created_at) }}
                                    </p>
                                </div>
                                
                                <!-- Menú de opciones del post -->
                                <div class="relative">
                                    <button 
                                        @click="togglePostMenu(post.id)"
                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors"
                                    >
                                        <MoreHorizontal class="w-5 h-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" />
                                    </button>
                                    
                                    <!-- Dropdown menu -->
                                    <div 
                                        v-if="showPostMenu[post.id]" 
                                        class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl shadow-xl z-10 backdrop-blur-sm"
                                        @click.stop
                                    >
                                        <div class="py-2">
                                            <!-- Editar (solo si es el autor) -->
                                            <button
                                                v-if="post.user.id === $page.props.auth.user.id"
                                                @click="editPost(post.id)"
                                                class="w-full px-4 py-2 text-left text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors flex items-center space-x-2"
                                            >
                                                <Edit class="w-4 h-4" />
                                                <span>Editar post</span>
                                            </button>
                                            
                                            <!-- Eliminar (solo si es el autor) -->
                                            <button
                                                v-if="post.user.id === $page.props.auth.user.id"
                                                @click="deletePost(post.id)"
                                                class="w-full px-4 py-2 text-left text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-700 dark:hover:text-red-300 transition-colors flex items-center space-x-2"
                                            >
                                                <Trash class="w-4 h-4" />
                                                <span>Eliminar post</span>
                                            </button>
                                            
                                            <!-- Reportar (si no es el autor) -->
                                            <button
                                                v-if="post.user.id !== $page.props.auth.user.id"
                                                @click="reportPost(post.id)"
                                                class="w-full px-4 py-2 text-left text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-500/10 hover:text-yellow-700 dark:hover:text-yellow-300 transition-colors flex items-center space-x-2"
                                            >
                                                <Flag class="w-4 h-4" />
                                                <span>Reportar post</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Title -->
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 leading-tight">
                                {{ post.title }}
                            </h2>

                            <!-- Post Body -->
                            <p v-if="post.body" class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                                {{ post.body }}
                            </p>
                        </div>

                        <!-- Post Media -->
                        <div v-if="post.media" class="px-6 mb-4">
                            <img
                                :src="getImageUrl(post.media)"
                                :alt="post.title"
                                class="w-full rounded-xl object-cover max-h-96 border border-gray-200 dark:border-gray-600 shadow-lg hover:shadow-xl transition-shadow"
                                @error="handleImageError"
                                loading="lazy"
                            />
                        </div>

                        <!-- Post Actions -->
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-6">
                                    <button 
                                        @click="toggleLike(post)"
                                        :class="[
                                            'flex items-center space-x-2 transition-all duration-200 group',
                                            post.liked_by_user ? 'text-red-500' : 'text-gray-500 dark:text-gray-400 hover:text-red-500'
                                        ]"
                                        :disabled="likeInProgress[post.id]"
                                    >
                                        <Heart 
                                            :class="[
                                                'w-5 h-5 group-hover:scale-110 transition-transform',
                                                post.liked_by_user ? 'fill-current' : '',
                                                likeInProgress[post.id] ? 'animate-pulse' : ''
                                            ]" 
                                        />
                                        <span class="text-sm font-medium flex items-center">
                                            <span>{{ post.liked_by_user ? 'Te gusta' : 'Me gusta' }}</span>
                                            <span v-if="post.likes_count > 0" class="ml-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full text-xs">
                                                {{ post.likes_count }}
                                            </span>
                                        </span>
                                    </button>
                                    
                                    <button 
                                        @click="toggleComments(post.id)"
                                        :class="[
                                            'flex items-center space-x-2 transition-colors group',
                                            showComments[post.id] ? 'text-purple-600 dark:text-purple-400' : 'text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400'
                                        ]"
                                    >
                                        <MessageCircle class="w-5 h-5 group-hover:scale-110 transition-transform" />
                                        <span class="text-sm font-medium">
                                            {{ post.comments.length }} comentario{{ post.comments.length !== 1 ? 's' : '' }}
                                        </span>
                                    </button>
                                    
                                    <button class="flex items-center space-x-2 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                                        <Share class="w-5 h-5 group-hover:scale-110 transition-transform" />
                                        <span class="text-sm font-medium">Compartir</span>
                                    </button>
                                </div>
                                <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors group">
                                    <Bookmark class="w-5 h-5 text-gray-400 hover:text-yellow-500 group-hover:scale-110 transition-all" />
                                </button>
                            </div>
                        </div>

                        <!-- Add Comment Form -->
                        <div v-if="showComments[post.id]" class="px-6 pb-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/20">
                            <div class="pt-4 flex space-x-3">
                                <Link :href="route('profile.edit')" class="flex-shrink-0 group">
                                    <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-200 dark:border-gray-600 shadow-md group-hover:border-purple-400 dark:group-hover:border-purple-500 transition-colors">
                                        <img 
                                            v-if="getCurrentUserAvatar()" 
                                            :src="getCurrentUserAvatar()" 
                                            :alt="`Avatar de ${$page.props.auth.user.name}`"
                                            class="w-full h-full object-cover"
                                            @error="handleAvatarError"
                                        />
                                        <div 
                                            v-else 
                                            class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold text-sm"
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
                                            class="w-full p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 text-sm transition-all"
                                            rows="2"
                                            :disabled="submittingComment[post.id]"
                                        ></textarea>
                                        <div class="flex justify-end mt-2">
                                            <button 
                                                type="submit"
                                                :disabled="!newComments[post.id]?.trim() || submittingComment[post.id]"
                                                class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg transition-all text-sm font-medium shadow-lg hover:shadow-xl transform hover:scale-105 disabled:transform-none"
                                            >
                                                {{ submittingComment[post.id] ? 'Enviando...' : 'Comentar' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div v-if="showComments[post.id] && post.comments.length" class="border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <MessageSquare class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" />
                                    Comentarios ({{ post.comments.length }})
                                </h4>
                                
                                <div class="space-y-4">
                                    <div
                                        v-for="comment in getVisibleComments(post)"
                                        :key="comment.id"
                                        class="flex space-x-3 p-4 bg-white/60 dark:bg-gray-800/60 rounded-xl border border-gray-200/50 dark:border-gray-600/50 hover:bg-white/80 dark:hover:bg-gray-800/80 hover:shadow-lg transition-all duration-200 backdrop-blur-sm"
                                    >
                                        <Link :href="route('users.profile', comment.user.id)" class="flex-shrink-0 group">
                                            <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-200 dark:border-gray-600 shadow-md group-hover:border-purple-400 dark:group-hover:border-purple-500 transition-colors">
                                                <img 
                                                    v-if="comment.user.avatar_url" 
                                                    :src="comment.user.avatar_url" 
                                                    :alt="`Avatar de ${comment.user.name}`"
                                                    class="w-full h-full object-cover"
                                                    @error="handleAvatarError"
                                                />
                                                <div 
                                                    v-else 
                                                    class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white font-semibold text-sm"
                                                >
                                                    {{ comment.user.name.charAt(0).toUpperCase() }}
                                                </div>
                                            </div>
                                        </Link>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <Link :href="route('users.profile', comment.user.id)" class="hover:underline group">
                                                    <span class="font-semibold text-gray-900 dark:text-white text-sm group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ comment.user.name }}</span>
                                                </Link>
                                                <span class="text-xs text-gray-400">•</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(comment.created_at) }}</span>
                                            </div>
                                            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">{{ comment.content }}</p>
                                            <div class="flex items-center space-x-4 mt-2">
                                                <button class="text-xs text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Responder</button>
                                                <button 
                                                    v-if="comment.user.id === $page.props.auth.user.id"
                                                    @click="deleteComment(comment.id, post.id)"
                                                    class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
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
                                            class="px-4 py-2 text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors text-sm font-medium"
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
                    <div class="w-24 h-24 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl border border-gray-200 dark:border-gray-700">
                        <FileText class="w-12 h-12 text-gray-400" />
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No hay posts disponibles</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        {{ filter === 'following' ? 'Las personas que sigues no han publicado nada aún' : 'Sé el primero en compartir algo con la comunidad' }}
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <button
                            @click="router.visit('/post/create')"
                            class="px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-xl transition-all font-medium shadow-lg hover:shadow-xl transform hover:scale-105"
                        >
                            Crear mi primer post
                        </button>
                        <Link
                            v-if="filter === 'following'"
                            href="/users/suggestions"
                            class="px-8 py-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm hover:bg-white dark:hover:bg-gray-800 text-gray-900 dark:text-white rounded-xl transition-all font-medium flex items-center space-x-2 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl transform hover:scale-105"
                        >
                            <Users class="w-5 h-5" />
                            <span>Descubrir usuarios</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
