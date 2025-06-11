<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  modelValue?: string
  class?: string
  placeholder?: string
  disabled?: boolean
  rows?: number
  maxlength?: number
  id?: string
}>()

const emit = defineEmits(['update:modelValue'])

const value = computed({
  get: () => props.modelValue || '',
  set: (value) => emit('update:modelValue', value)
})

const classes = computed(() => {
  return `flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 ${props.class || ''}`
})
</script>

<template>
  <textarea
    :id="id"
    v-model="value"
    :class="classes"
    :placeholder="placeholder"
    :disabled="disabled"
    :rows="rows || 3"
    :maxlength="maxlength"
  ></textarea>
</template>
