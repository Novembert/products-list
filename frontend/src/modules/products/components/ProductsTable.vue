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
      class="!px-2 w-0 hidden md:table-cell"
      :reorderableColumn="false"
    />
    <Column
      class="!px-1 w-0 md:hidden"
    >
      <template #body="slotProps">
        <div class="flex gap-2">
          <Button
            icon="pi pi-chevron-up"
            :class="{
              '!opacity-25': isMobileReorderDisabled(slotProps.data.id, ReorderDirection.Up)
            }"
            size="small"
            severity="secondary"
            :disabled="isMobileReorderDisabled(slotProps.data.id, ReorderDirection.Up)"
            @click="onMobileReorder({ direction: ReorderDirection.Up, productId: slotProps.data.id })"
          />
          <Button
            icon="pi pi-chevron-down"
            :class="{
              '!opacity-25': isMobileReorderDisabled(slotProps.data.id, ReorderDirection.Down)
            }"
            size="small"
            severity="secondary"
            :disabled="isMobileReorderDisabled(slotProps.data.id, ReorderDirection.Down)"
            @click="onMobileReorder({ direction: ReorderDirection.Down, productId: slotProps.data.id })"
          />
        </div>
      </template>
    </Column>
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
    <Column
      field="tag"
      class="w-0 hidden md:table-cell"
      :header="t('productsForSale.products.tag')"
    >
      <template #body="slotProps">
        <Tag
          v-if="slotProps.data.tag"
          :tag="slotProps.data.tag"
        />
      </template>
    </Column>
    <Column class="w-0">
      <template #body>
        <i class="pi pi-chevron-right"></i>
      </template>
    </Column>
  </DataTable>
</template>

<script setup lang="ts">
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import Tag from '@/app/components/Tag.vue'
import { useI18n } from 'vue-i18n'
import { computed, ref, watch } from 'vue'
import type { DataTableRowSelectEvent, DataTableRowReorderEvent } from 'primevue/datatable'
import type { Product, updateProductPositionPayload } from '@/types/models/Product'
import { debounce } from 'lodash'

interface TableItem extends Product {
  product: Product
  formattedVatRate: string
}

const props = defineProps<{
  products: Product[]
  loading: boolean
}>()

const emit = defineEmits<{
  (e: 'rowReorder', event: updateProductPositionPayload): void
  (e: 'productSelected', event: Product): void
}>()

const { t } = useI18n()

enum ReorderDirection {
  Up = 'up',
  Down = 'down',
}

interface RowReorderEvent {
  id: number
  oldPosition: number
  newPosition: number
}

interface MobileRowReorderEvent {
  direction: ReorderDirection;
  productId: number;
}

const mobileReorderInitialPosition = ref<number>(0);
const mobileReorderRelativeFinalPosition = ref<number>(0);
const mobileReorderActiveProductId = ref<number>();
const tableItems = ref<TableItem[]>([]);

const productsCount = computed(() => props.products.length)

const isMobileReorderDisabled = (productId: number, targetDirection: ReorderDirection) => {
  if (mobileReorderActiveProductId.value && mobileReorderActiveProductId.value !== productId) {
    return true;
  }

  const itemIndex = tableItems.value.findIndex(item => item.id === productId);
  if (targetDirection === ReorderDirection.Up) {
    return itemIndex === 0;
  } else if (targetDirection === ReorderDirection.Down) {
    return itemIndex === tableItems.value.length - 1;
  }
};

const submitMobileReorder = () => {
  if (mobileReorderRelativeFinalPosition.value === undefined || mobileReorderActiveProductId.value === undefined) {
    return;
  }

  emitRowReorder({
    id: mobileReorderActiveProductId.value,
    oldPosition: mobileReorderInitialPosition.value,
    newPosition: mobileReorderRelativeFinalPosition.value + mobileReorderInitialPosition.value
  })

  mobileReorderRelativeFinalPosition.value = 0;
  mobileReorderInitialPosition.value = 0;
  mobileReorderActiveProductId.value = undefined;
}

const debouncedSubmitMobileReorder = debounce(() => {
  submitMobileReorder();
}, 2000);

const onMobileReorder = (event: MobileRowReorderEvent) => {
  const currentIndex = tableItems.value.findIndex(item => item.id === event.productId);

  if (mobileReorderActiveProductId.value !== event.productId) {
    mobileReorderActiveProductId.value = event.productId;
    mobileReorderInitialPosition.value = currentIndex + 1;
  }

  const swapItems = (index1: number, index2: number) => {
    [tableItems.value[index1], tableItems.value[index2]] = [tableItems.value[index2], tableItems.value[index1]];
  };

  if (event.direction === ReorderDirection.Up && currentIndex > 0) {
    mobileReorderRelativeFinalPosition.value -= 1;
    swapItems(currentIndex, currentIndex - 1);
  } else if (event.direction === ReorderDirection.Down && currentIndex < tableItems.value.length - 1) {
    mobileReorderRelativeFinalPosition.value += 1;
    swapItems(currentIndex, currentIndex + 1);
  }

  debouncedSubmitMobileReorder();
};

const onRowReorder = (event: DataTableRowReorderEvent) => {
  const reorderedProduct = event.value[event.dropIndex];
  emitRowReorder({
    id: reorderedProduct.id,
    oldPosition: event.dragIndex + 1,
    newPosition: event.dropIndex + 1
  })
}

const emitRowReorder = (event: RowReorderEvent) => {
  const reorderedProduct = event.id;
  if (event.oldPosition === event.newPosition) {
    return;
  }
  emit('rowReorder', {
    id: reorderedProduct,
    oldPosition: event.oldPosition,
    newPosition: event.newPosition
  })
}

const onRowSelect = (event: DataTableRowSelectEvent<TableItem>) => {
  emit('productSelected', event.data.product)
}
watch(
  () => props.products,
  (newProducts) => {
    tableItems.value = newProducts.map((product, index) => ({
      ...product,
      formattedVatRate: `${(Number(product.vatRate) * 100).toFixed()}%`,
      product: product,
    }))
  },
  { immediate: true }
)
</script>
