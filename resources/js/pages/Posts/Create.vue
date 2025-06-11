<template>
  <Head title="Crear Post" />

  <AppLayout>
    <div class="py-8">
      <div class="max-w-2xl mx-auto px-6">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-white mb-2">Crear Nuevo Post</h1>
          <p class="text-gray-400">Comparte algo interesante con la comunidad</p>
        </div>

        <!-- Formulario -->
        <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden">
          <form @submit.prevent="submitPost" class="p-6 space-y-6">
            <!-- Título -->
            <div>
              <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                Título *
              </label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                placeholder="¿Qué quieres compartir?"
                class="w-full p-4 bg-gray-900 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary text-white placeholder-gray-400 text-lg"
                :class="{ 'border-destructive': errors.title }"
                maxlength="255"
              />
              <div class="flex justify-between mt-1">
                <span v-if="errors.title" class="text-destructive text-sm">{{ errors.title }}</span>
                <span class="text-gray-500 text-sm ml-auto">{{ form.title.length }}/255</span>
              </div>
            </div>

            <!-- Contenido -->
            <div>
              <label for="body" class="block text-sm font-medium text-gray-300 mb-2">
                Contenido
              </label>
              <textarea
                id="body"
                v-model="form.body"
                placeholder="Cuéntanos más detalles... (opcional)"
                rows="6"
                class="w-full p-4 bg-gray-900 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary text-white placeholder-gray-400 resize-none"
                :class="{ 'border-destructive': errors.body }"
                maxlength="5000"
              ></textarea>
              <div class="flex justify-between mt-1">
                <span v-if="errors.body" class="text-destructive text-sm">{{ errors.body }}</span>
                <span class="text-gray-500 text-sm ml-auto">{{ (form.body || '').length }}/5000</span>
              </div>
            </div>

            <!-- Subir imagen -->
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Imagen (opcional)
              </label>
              
              <!-- Área de drop -->
              <div
                @drop="handleDrop"
                @dragover.prevent
                @dragenter.prevent
                @dragleave="isDragging = false"
                @dragenter="isDragging = true"
                :class="[
                  'border-2 border-dashed rounded-xl p-8 text-center transition-colors',
                  isDragging ? 'border-primary bg-primary/10' : 'border-gray-600 hover:border-gray-500'
                ]"
              >
                <input
                  ref="fileInput"
                  type="file"
                  accept="image/*"
                  @change="handleFileSelect"
                  class="hidden"
                />
                
                <div v-if="!imagePreview">
                  <!-- Icono SVG en lugar de Lucide -->
                  <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                  <p class="text-gray-300 mb-2">Arrastra una imagen aquí o</p>
                  <button
                    type="button"
                    @click="$refs.fileInput.click()"
                    class="px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-lg transition-colors text-sm font-medium"
                  >
                    Seleccionar archivo
                  </button>
                  <p class="text-gray-500 text-xs mt-2">PNG, JPG, GIF hasta 10MB</p>
                </div>

                <!-- Preview de imagen -->
                <div v-else class="relative">
                  <img
                    :src="imagePreview"
                    alt="Preview"
                    class="max-w-full max-h-64 rounded-lg mx-auto"
                  />
                  <button
                    type="button"
                    @click="removeImage"
                    class="absolute top-2 right-2 p-2 bg-destructive hover:bg-destructive/90 text-destructive-foreground rounded-full transition-colors"
                  >
                    <!-- Icono X SVG -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                  <p class="text-gray-400 text-sm mt-2">{{ selectedFile?.name }}</p>
                </div>
              </div>
              
              <span v-if="errors.media" class="text-destructive text-sm">{{ errors.media }}</span>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-700">
              <button
                type="button"
                @click="goBack"
                class="px-6 py-3 text-gray-400 hover:text-white transition-colors"
              >
                Cancelar
              </button>
              
              <button
                type="submit"
                :disabled="!form.title.trim() || form.processing"
                class="px-8 py-3 bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed text-primary-foreground rounded-xl transition-colors font-medium flex items-center space-x-2"
              >
                <span v-if="form.processing">Publicando...</span>
                <span v-else>Publicar Post</span>
                <!-- Icono Send SVG -->
                <svg v-if="!form.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

// Props
defineProps<{
  errors?: Record<string, string>
}>()

// Estados reactivos
const isDragging = ref(false)
const imagePreview = ref<string | null>(null)
const selectedFile = ref<File | null>(null)
const fileInput = ref<HTMLInputElement>()

// Formulario de Inertia
const form = useForm({
  title: '',
  body: '',
  media: null as File | null,
})

// Funciones
const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    processFile(file)
  }
}

const handleDrop = (event: DragEvent) => {
  event.preventDefault()
  isDragging.value = false
  
  const files = event.dataTransfer?.files
  if (files && files.length > 0) {
    processFile(files[0])
  }
}

const processFile = (file: File) => {
  // Validar tipo de archivo
  if (!file.type.startsWith('image/')) {
    alert('Por favor selecciona solo archivos de imagen')
    return
  }

  // Validar tamaño (10MB)
  if (file.size > 10 * 1024 * 1024) {
    alert('La imagen debe ser menor a 10MB')
    return
  }

  selectedFile.value = file
  form.media = file

  // Crear preview
  const reader = new FileReader()
  reader.onload = (e) => {
    imagePreview.value = e.target?.result as string
  }
  reader.readAsDataURL(file)
}

const removeImage = () => {
  imagePreview.value = null
  selectedFile.value = null
  form.media = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const goBack = () => {
  router.visit('/post')
}

const submitPost = () => {
  form.post('/post', {
    forceFormData: true,
    onSuccess: () => {
      // El redirect se maneja en el controlador
    }
  })
}
</script>