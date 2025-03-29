<template>
  <Dialog
    :visible="isVisible"
    @update:visible="(value: boolean) => !value && emit('hide')"
  >
    <template #header>
      {{ t('productsForSale.editOrDeleteProductDialog.title') }}
    </template>
    <ProductForm
      v-model="updatedProduct"
    />
    <template #footer>
      <Button
        :label="t('global.cancel')"
        severity="secondary"
        :disabled="loading"
        @click="emit('hide')"
      />
      <Button
        :label="t('global.save')"
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
import { updateProduct } from '@/api/products'
import { useVuelidate } from '@vuelidate/core'
import type { Product, UpdateProductPayload } from '@/types/models/Product'

const props = defineProps<{
  product?: Product
}>()

const emit = defineEmits<{
  (e: 'success'): void
  (e: 'hide'): void
}>()

const v$ = useVuelidate()
const { t } = useI18n()

const updatedProduct = ref<UpdateProductPayload>()
const loading = ref(false)

watch(
  () => props.product,
  () => {
    if (!props.product) {
      updatedProduct.value = undefined
      return
    }

    v$.value.$reset()
    updatedProduct.value = {
      ...props.product,
      price: Number(props.product.price),
      vatRate: Number(props.product.vatRate),
    }
  },
)

const isVisible = computed(() => !!props.product)
const isSubmitDisabled = computed(() => v$.value.$invalid || !v$.value.$anyDirty || loading.value)

const onSubmit = async () => {
  try {
    if (!updatedProduct.value) return
    loading.value = true
    await updateProduct(updatedProduct.value)
    emit('success')
  } finally {
    loading.value = false
  }
}
</script>
