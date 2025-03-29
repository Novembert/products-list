<template>
  <div class="flex justify-end mb-4">
    <CreateProductDialog @success="fetchProducts" />
  </div>
  <ProductsTable
    class="!h-auto"
    :products="products"
    :loading="loading"
    @product-selected="(product: Product) => (selectedProduct = product)"
  />
  <EditOrDeleteProductDialog
    :product="selectedProduct"
    @hide="selectedProduct = undefined"
    @success="onEditOrDeleteProductSuccess"
  />
</template>

<script setup lang="ts">
import CreateProductDialog from '../components/CreateProductDialog.vue'
import EditOrDeleteProductDialog from '../components/EditOrDeleteProductDialog.vue'
import ProductsTable from '../components/ProductsTable.vue'
import { onBeforeMount } from 'vue'
import type { Product } from '@/types/models/Product'
import { ref } from 'vue'
import { getProducts } from '@/api/products'

const products = ref<Product[]>([])
const selectedProduct = ref<Product | undefined>()
const loading = ref(false)

const onEditOrDeleteProductSuccess = () => {
  selectedProduct.value = undefined
  fetchProducts()
}

const fetchProducts = async () => {
  try {
    loading.value = true
    products.value = await getProducts()
  } finally {
    loading.value = false
  }
}

onBeforeMount(() => {
  fetchProducts()
})
</script>
