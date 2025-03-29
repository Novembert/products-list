<template>
  <div class="bg-gray-50 h-full max-h-full overflow-hidden">
    <AppHeader class="max-md:h-[7.5vh]" />
    <main class="p-4 md:p-6 lg:p-8 h-[92.5vh] max-h-[92.5vh] flex flex-col">
      <h2
        v-if="breadcrumb"
        data-testid="breadcrumbs"
        class="mb-4 font-medium text-gray-500 md:text-lg lg:text-xl"
      >
        {{ t(breadcrumb) }}
      </h2>
      <RouterView />
    </main>
  </div>
</template>

<script lang="ts" setup>
import AppHeader from './components/AppHeader.vue'
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'

const { t } = useI18n()
const route = useRoute()
const { breadcrumb } = route.meta
</script>
