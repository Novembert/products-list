<template>
  <div class="bg-gray-50 h-full">
    <main>
      <h2 v-if="breadcrumb" class="mb-8">
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
