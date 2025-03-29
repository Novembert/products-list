<template>
  <Dialog
    :visible="isVisible"
    @update:visible="(value: boolean) => !value && emit('hide')"
  >
    <template #header>
      {{ t('productsForSale.editOrDeleteProductDialog.title') }}
    </template>
    <ProductForm v-model="updatedProduct" />
    <template #footer>
      <Button
        severity="danger"
        :label="t('global.delete')"
        :disabled="deleting"
        :loading="deleting"
        @click="onDelete"
      />
      <div class="grow-1 flex justify-end gap-2">
        <Button
          :label="t('global.cancel')"
          severity="secondary"
          :disabled="loading"
          @click="emit('hide')"
        />
        <Button
          :label="t('global.save')"
          :disabled="isSubmitDisabled"
          :loading="updating"
          @click="onSubmit"
        />
      </div>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import ProductForm from './ProductForm.vue'
import { computed, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { deleteProduct, updateProduct } from '@/api/products'
import { useVuelidate } from '@vuelidate/core'
import type { Product, UpdateProductPayload } from '@/types/models/Product'
import { cloneDeep } from 'lodash';

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
const updating = ref(false)
const deleting = ref(false)

watch(
  () => props.product,
  () => {
    if (!props.product) {
      updatedProduct.value = undefined
      return
    }

    v$.value.$reset()
    updatedProduct.value = {
      ...cloneDeep(props.product),
      price: Number(props.product.price),
      vatRate: Number(props.product.vatRate),
    }
  },
)

const loading = computed(() => updating.value || deleting.value)
const isVisible = computed(() => !!props.product)
const isSubmitDisabled = computed(() => v$.value.$invalid || !v$.value.$anyDirty || loading.value)

const onSubmit = async () => {
  try {
    if (!updatedProduct.value) return
    updating.value = true
    await updateProduct(updatedProduct.value)
    emit('success')
  } finally {
    updating.value = false
  }
}

const onDelete = async () => {
  try {
    if (!props.product) return
    deleting.value = true
    await deleteProduct(props.product.id)
    emit('success')
  } finally {
    deleting.value = false
  }
}
</script>
