<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, Search, ChevronDown } from 'lucide-vue-next';
import { computed } from 'vue';
import { House, Heart, MessageCircle, Plus } from 'lucide-vue-next';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const page = usePage();
const auth = computed(() => page.props.auth);

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const isCurrentRoute = computed(() => (url: string) => {
    if (url === '/post' || url === '/posts') {
        return page.url === '/post' || page.url === '/posts' || page.url.startsWith('/post') || page.url.startsWith('/posts');
    }
    return page.url === url || page.url.startsWith(url);
});

const activeItemStyles = computed(
    () => (url: string) => (isCurrentRoute.value(url) ? 'text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20' : ''),
);

const mainNavItems: NavItem[] = [
    {
        title: 'Posts',
        href: '/post',
        icon: House,
    },
    {
        title: 'Crear Post',
        href: '/post/create',
        icon: Plus,
    },
];

const rightNavItems: NavItem[] = [
    {
        title: 'Notificaciones',
        href: '#',
        icon: Heart,
    },
    {
        title: 'Mensajes',
        href: '#',
        icon: MessageCircle,
    },
];
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-purple-900">
        <!-- Background Decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute top-40 left-40 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <!-- Header -->
        <div class="relative z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-200/50 dark:border-gray-700/50 shadow-lg">
            <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                <!-- Mobile Menu -->
                <div class="lg:hidden">
                    <Sheet>
                        <SheetTrigger :as-child="true">
                            <Button variant="ghost" size="icon" class="mr-2 h-9 w-9 hover:bg-purple-100 dark:hover:bg-purple-900/20">
                                <Menu class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-[300px] p-6 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border-r border-gray-200 dark:border-gray-700">
                            <SheetTitle class="sr-only">Navigation Menu</SheetTitle>
                            <SheetHeader class="flex justify-start text-left">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                                        <AppLogoIcon class="size-5 fill-current text-white" />
                                    </div>
                                    <span class="text-xl font-bold text-gray-900 dark:text-white">Momentum</span>
                                </div>
                            </SheetHeader>
                            <div class="flex h-full flex-1 flex-col justify-between space-y-4 py-6">
                                <nav class="-mx-3 space-y-2">
                                    <Link
                                        v-for="item in mainNavItems"
                                        :key="item.title"
                                        :href="item.href"
                                        class="flex items-center gap-x-3 rounded-xl px-4 py-3 text-sm font-medium transition-all hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400"
                                        :class="[
                                            activeItemStyles(item.href),
                                            isCurrentRoute(item.href) ? 'bg-gradient-to-r from-purple-100 to-blue-100 dark:from-purple-900/30 dark:to-blue-900/30 text-purple-600 dark:text-purple-400 shadow-sm' : ''
                                        ]"
                                    >
                                        <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                        {{ item.title }}
                                    </Link>
                                </nav>
                                <div class="flex flex-col space-y-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <a
                                        v-for="item in rightNavItems"
                                        :key="item.title"
                                        :href="item.href"
                                        class="flex items-center space-x-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors px-2 py-1 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20"
                                    >
                                        <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                        <span>{{ item.title }}</span>
                                    </a>
                                </div>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>

                <!-- Logo -->
                <Link :href="route('dashboard')" class="flex items-center gap-x-3 group">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all transform group-hover:scale-105">
                        <AppLogoIcon class="size-6 fill-current text-white" />
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors hidden sm:block">
                        Momentum
                    </span>
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden h-full lg:flex lg:flex-1">
                    <NavigationMenu class="ml-10 flex h-full items-stretch">
                        <NavigationMenuList class="flex h-full items-stretch space-x-2">
                            <NavigationMenuItem v-for="(item, index) in mainNavItems" :key="index" class="relative flex h-full items-center">
                                <Link :href="item.href">
                                    <NavigationMenuLink
                                        :class="[
                                            'h-10 cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center space-x-2',
                                            isCurrentRoute(item.href) 
                                                ? 'bg-gradient-to-r from-purple-100 to-blue-100 dark:from-purple-900/30 dark:to-blue-900/30 text-purple-600 dark:text-purple-400 shadow-sm' 
                                                : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800'
                                        ]"
                                    >
                                        <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                                        <span>{{ item.title }}</span>
                                    </NavigationMenuLink>
                                </Link>
                                <div
                                    v-if="isCurrentRoute(item.href)"
                                    class="absolute bottom-0 left-1/2 transform -translate-x-1/2 h-1 w-8 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full"
                                ></div>
                            </NavigationMenuItem>
                        </NavigationMenuList>
                    </NavigationMenu>
                </div>

                <div class="ml-auto flex items-center space-x-2">
                    <div class="relative flex items-center space-x-1">
                        <Button variant="ghost" size="icon" class="group h-10 w-10 cursor-pointer hover:bg-purple-100 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-all rounded-lg">
                            <Search class="size-5 opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all" />
                        </Button>

                        <div class="hidden space-x-1 lg:flex">
                            <template v-for="item in rightNavItems" :key="item.title">
                                <TooltipProvider :delay-duration="0">
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <Button variant="ghost" size="icon" as-child class="group h-10 w-10 cursor-pointer hover:bg-purple-100 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-all rounded-lg relative">
                                                <a :href="item.href">
                                                    <span class="sr-only">{{ item.title }}</span>
                                                    <component :is="item.icon" class="size-5 opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all" />
                                                    <span v-if="item.title === 'Notificaciones'" class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white dark:border-gray-900"></span>
                                                </a>
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent class="bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 border border-gray-700 dark:border-gray-300">
                                            <p>{{ item.title }}</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </template>
                        </div>
                    </div>

                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative size-12 w-auto rounded-full p-1 hover:bg-purple-100 dark:hover:bg-purple-900/20 transition-all group"
                            >
                                <Avatar class="size-10 overflow-hidden rounded-full border-2 border-gray-200 dark:border-gray-600 group-hover:border-purple-400 dark:group-hover:border-purple-500 transition-colors shadow-lg">
                                    <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
                                    <AvatarFallback class="rounded-full bg-gradient-to-br from-purple-500 to-blue-500 font-semibold text-white">
                                        {{ getInitials(auth.user?.name) }}
                                    </AvatarFallback>
                                </Avatar>
                                <ChevronDown class="absolute -bottom-1 -right-1 w-4 h-4 text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors bg-white dark:bg-gray-900 rounded-full p-0.5" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-64 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-3">
                                    <Avatar class="size-10 overflow-hidden rounded-full border-2 border-gray-200 dark:border-gray-600">
                                        <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
                                        <AvatarFallback class="rounded-full bg-gradient-to-br from-purple-500 to-blue-500 font-semibold text-white">
                                            {{ getInitials(auth.user?.name) }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 dark:text-white truncate">{{ auth.user.name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ auth.user.email }}</p>
                                    </div>
                                </div>
                            </div>
                            <UserMenuContent :user="auth.user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </div>

        <div v-if="props.breadcrumbs.length > 1" class="relative z-40 bg-white/60 dark:bg-gray-900/60 backdrop-blur-sm border-b border-gray-200/50 dark:border-gray-700/50">
            <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-gray-600 dark:text-gray-400 md:max-w-7xl">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>

        <!-- Main Content -->
        <main class="relative z-10">
            <slot />
        </main>
    </div>
</template>

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
