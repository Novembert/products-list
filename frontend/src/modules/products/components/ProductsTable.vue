<template>
  <DataTable
    selectionMode="single"
    :value="tableItems"
    :loading="loading"
    scrollable
    scrollHeight="flex"
    @rowReorder="onRowReorder"
    @rowSelect="onRowSelect"
  >
    <template #header>
      <h2 class="font-semibold">
        {{ t('productsForSale.allProducts', { count: productsCount }) }}
      </h2>
    </template>
    <Column
      rowReorder
      class="!px-2 w-0"
      :reorderableColumn="false"
    />
    <Column
      class="hidden md:table-cell"
      field="id"
      :header="t('productsForSale.products.productId')"
    />
    <Column
      field="name"
      class="whitespace-nowrap"
      :header="t('productsForSale.products.name')"
    />
    <Column
      class="hidden md:table-cell"
      field="description"
      :header="t('productsForSale.products.description')"
    />
    <Column
      field="price"
      class="w-0 text-right"
      :header="t('productsForSale.products.price')"
    />
    <Column
      field="formattedVatRate"
      class="w-0 hidden md:table-cell"
      :header="t('productsForSale.products.vatRate')"
    />
  </DataTable>
</template>

<script setup lang="ts">
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useI18n } from 'vue-i18n'
import { computed } from 'vue'
import type { DataTableRowSelectEvent, DataTableRowReorderEvent } from 'primevue/datatable'
import type { Product } from '@/types/models/Product'

interface TableItem extends Product {
  product: Product
  formattedVatRate: string
}

const props = defineProps<{
  products: Product[]
  loading: boolean
}>()

const emit = defineEmits<{
  (e: 'rowReorder', event: DataTableRowReorderEvent): void
  (e: 'productSelected', event: Product): void
}>()

const { t } = useI18n()

const onRowReorder = (event: DataTableRowReorderEvent) => {}

const onRowSelect = (event: DataTableRowSelectEvent<TableItem>) => {
  emit('productSelected', event.data.product)
}

const productsCount = computed(() => props.products.length)

const tableItems = computed<TableItem[]>(() => {
  return props.products.map((product) => ({
    ...product,
    formattedVatRate: `${Number(product.vatRate) * 100}%`,
    product: product,
  }))
})
</script>
