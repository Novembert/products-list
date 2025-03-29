<template>
  <div class="bg-gray-50 h-full max-h-full overflow-hidden">
    <header class="h-[7.5vh] py-2 px-4 bg-white shadow-md flex items-center justify-between">
      Some menu
    </header>
    <main class="p-4 h-[92.5vh] max-h-[92.5vh] flex flex-col">
      <h2
        v-if="breadcrumb"
        data-testid="breadcrumbs"
        class="mb-4 font-medium text-gray-500"
      >
        {{ t(breadcrumb) }}
      </h2>
      <RouterView />
    </main>
  </div>
</template>

<script lang="ts" setup>
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'

const { t } = useI18n()
const route = useRoute()
const { breadcrumb } = route.meta
</script>
