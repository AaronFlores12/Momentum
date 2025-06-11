<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    title: string;
    description: string;
}>();
</script>

<template>
    <div class="min-h-screen grid lg:grid-cols-2">
        <!-- Left side - Form -->
        <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <Link href="/" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                            <span class="text-primary-foreground font-bold">A</span>
                        </div>
                        <span class="text-xl font-bold">App</span>
                    </Link>
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        {{ title }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ description }}
                    </p>
                </div>
                <div class="mt-8 space-y-6">
                    <slot />
                </div>
            </div>
        </div>
        
        <!-- Right side - Image/Branding -->
        <div class="hidden lg:block relative">
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary/80"></div>
            <div class="relative h-full flex items-center justify-center p-12">
                <div class="text-center text-white">
                    <h3 class="text-3xl font-bold mb-4">Welcome to our platform</h3>
                    <p class="text-lg opacity-90">Join thousands of users who trust our platform for their daily needs.</p>
                </div>
            </div>
        </div>
    </div>
</template>
