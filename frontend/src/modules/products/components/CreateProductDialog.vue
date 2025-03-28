<template>
  <Button
    :label="t('productsForSale.createProductDialog.cta')"
    icon="pi pi-plus"
    @click="showDialog"
  />
  <Dialog v-model:visible="isVisible">
    <template #header>
      {{ t('productsForSale.createProductDialog.title') }}
    </template>
    <ProductForm v-model="product" />
    <template #footer>
      <Button
        :label="t('global.cancel')"
        severity="secondary"
        :disabled="loading"
        @click="isVisible = false"
      />
      <Button
        :label="t('global.create')"
        :disabled="isSubmitDisabled"
        :loading="loading"
        @click="onSubmit"
      />
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import ProductForm from './ProductForm.vue'
import { computed, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { createProduct } from '@/api/products'
import { useVuelidate } from '@vuelidate/core'
import type { CreateProductPayload } from '@/types/models/Product'

const emit = defineEmits<{
  (e: 'success'): void
}>()

const v$ = useVuelidate()
const { t } = useI18n()

const product = ref<CreateProductPayload>()
const isVisible = ref(false)
const loading = ref(false)

const showDialog = () => {
  isVisible.value = true
}

watch(isVisible, () => {
  if (isVisible.value) {
    v$.value.$reset()
  }
})

const isSubmitDisabled = computed(() => v$.value.$invalid || !v$.value.$dirty || loading.value)

const onSubmit = async () => {
  try {
    if (!product.value) return
    loading.value = true
    await createProduct(product.value)
    emit('success')
    isVisible.value = false
  } finally {
    loading.value = false
  }
}
</script>
