<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { BicepsFlexed } from 'lucide-vue-next';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <BicepsFlexed :class="className" v-bind="$attrs" />
</template>