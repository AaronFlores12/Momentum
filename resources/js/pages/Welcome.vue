<template>
  <Head title="Únete a la Comunidad">
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
  </Head>
  
  <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-purple-900">
    <!-- Header -->
    <header class="relative z-10 p-6">
      <nav class="flex items-center justify-between max-w-7xl mx-auto">
        <div class="flex items-center space-x-2">
          <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
            <Users class="w-5 h-5 text-white" />
          </div>
          <span class="text-xl font-bold text-gray-900 dark:text-white">Momentum</span>
        </div>
        
        <div class="flex items-center space-x-4">
          <Link
            v-if="$page.props.auth.user"
            :href="route('dashboard')"
            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          >
            <LayoutDashboard class="w-4 h-4 mr-2" />
            Panel
          </Link>
          <template v-else>
            <Link
              :href="route('login')"
              class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-medium transition-colors"
            >
              Iniciar Sesión
            </Link>
            <Link
              :href="route('register')"
              class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-medium rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-lg"
            >
              Comenzar
            </Link>
          </template>
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="relative">
      <!-- Hero Section -->
      <div class="max-w-7xl mx-auto px-6 py-12 lg:py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <!-- Left Content -->
          <div class="space-y-8">
            <div class="space-y-4">
              <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight">
                Conecta con
                <span class="bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                  Personas Increíbles
                </span>
              </h1>
              <p class="text-xl text-gray-600 dark:text-gray-300 leading-relaxed">
                Únete a miles de usuarios compartiendo momentos, construyendo comunidades y creando conexiones duraderas en nuestra vibrante plataforma social.
              </p>
            </div>

            <!-- Features -->
            <div class="grid sm:grid-cols-2 gap-4">
              <div class="flex items-center space-x-3 p-4 bg-white/50 dark:bg-gray-800/50 rounded-lg backdrop-blur-sm">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                  <MessageCircle class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">Chat en Tiempo Real</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Mensajería instantánea</p>
                </div>
              </div>
              
              <div class="flex items-center space-x-3 p-4 bg-white/50 dark:bg-gray-800/50 rounded-lg backdrop-blur-sm">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                  <Share2 class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">Comparte Momentos</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Fotos e historias</p>
                </div>
              </div>
              
              <div class="flex items-center space-x-3 p-4 bg-white/50 dark:bg-gray-800/50 rounded-lg backdrop-blur-sm">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                  <Heart class="w-5 h-5 text-green-600 dark:text-green-400" />
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">Crea Comunidades</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Únete a grupos</p>
                </div>
              </div>
              
              <div class="flex items-center space-x-3 p-4 bg-white/50 dark:bg-gray-800/50 rounded-lg backdrop-blur-sm">
                <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                  <Zap class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">Mantente Actualizado</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Feeds en vivo</p>
                </div>
              </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
              <Link
                :href="route('register')"
                class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-xl"
              >
                <UserPlus class="w-5 h-5 mr-2" />
                Crear Cuenta
              </Link>
              <Link
                :href="route('login')"
                class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-500 transition-all"
              >
                <LogIn class="w-5 h-5 mr-2" />
                Iniciar Sesión
              </Link>
            </div>
          </div>

          <!-- Right Content - Visual -->
          <div class="relative">
            <!-- Main Card -->
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 transform rotate-3 hover:rotate-0 transition-transform duration-500">
              <!-- Profile Header -->
              <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                  <span class="text-white font-bold text-lg">M</span>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">María González</h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">hace 2 horas</p>
                </div>
              </div>
              
              <!-- Post Content -->
              <p class="text-gray-700 dark:text-gray-300 mb-4">
                ¡Acabo de unirme a esta increíble comunidad! Me encantan las conexiones que estoy haciendo aquí 🚀
              </p>
              
              <!-- Post Image Placeholder -->
              <div class="w-full h-48 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg mb-4 flex items-center justify-center">
                <Camera class="w-12 h-12 text-white/70" />
              </div>
              
              <!-- Engagement -->
              <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-4">
                  <button class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors">
                    <Heart class="w-5 h-5" />
                    <span class="text-sm">24</span>
                  </button>
                  <button class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 transition-colors">
                    <MessageCircle class="w-5 h-5" />
                    <span class="text-sm">8</span>
                  </button>
                  <button class="flex items-center space-x-2 text-gray-500 hover:text-green-500 transition-colors">
                    <Share2 class="w-5 h-5" />
                    <span class="text-sm">3</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Floating Elements -->
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg animate-bounce">
              <Sparkles class="w-8 h-8 text-white" />
            </div>
            
            <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
              <Users class="w-6 h-6 text-white" />
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Section -->
      <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm py-12">
        <div class="max-w-7xl mx-auto px-6">
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
              <div class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">10K+</div>
              <div class="text-gray-600 dark:text-gray-400">Usuarios Activos</div>
            </div>
            <div class="text-center">
              <div class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">50K+</div>
              <div class="text-gray-600 dark:text-gray-400">Publicaciones</div>
            </div>
            <div class="text-center">
              <div class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">100+</div>
              <div class="text-gray-600 dark:text-gray-400">Comunidades</div>
            </div>
            <div class="text-center">
              <div class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">24/7</div>
              <div class="text-gray-600 dark:text-gray-400">Soporte</div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
      <div class="absolute top-40 left-40 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { 
  Users, 
  MessageCircle, 
  Share2, 
  Heart, 
  Zap, 
  UserPlus, 
  LogIn, 
  Camera, 
  Sparkles,
  LayoutDashboard
} from 'lucide-vue-next'
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