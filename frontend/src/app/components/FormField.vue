<template>
  <div class="flex flex-col gap-2">
    <p>
      {{ props.label }}
      <span
        v-if="props.required"
        data-testid="required-indicator"
        class="text-red-500"
        >*</span
      >
    </p>
    <slot />
    <p
      v-if="props.error"
      data-testid="error-message"
      class="text-red-500 text-sm first-letter:capitalize"
    >
      {{ props.error }}
    </p>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  label: string
  required?: boolean
  error?: string
}>()
</script>
