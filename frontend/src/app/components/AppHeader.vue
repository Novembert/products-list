<template>
  <header class="py-2 md:py-4 px-4 md:px-6 lg:px-8 bg-white shadow-md flex items-center justify-between">
    <h1>OnlinePOS</h1>
    <Select 
      :options="languageDropdownItems" 
      v-model="locale"
      option-label="label"
      option-value="value"
    />
  </header>
</template>

<script setup lang="ts">
import { LanguageCode } from '@/types/language';
import Select from 'primevue/select';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n()

const languageDropdownItems = [
  {
    label: t(`locale.${LanguageCode.Danish}`),
    value: LanguageCode.Danish,
  },
  { 
    label: t(`locale.${LanguageCode.English}`),
    value: LanguageCode.English,
  },
]
</script>