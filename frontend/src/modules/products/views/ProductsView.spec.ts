import ProductsView from './ProductsView.vue'
import { shallowMount, flushPromises, VueWrapper } from '@vue/test-utils'
import { describe, it, expect, vi } from 'vitest'
import * as productsApi from '@/api/products'
import { mockProducts } from '@/__mocks__/apiData/products'

const wrapperFactory = async (): Promise<VueWrapper<any>> => {
  const wrapper = shallowMount(ProductsView)
  await flushPromises()
  return wrapper
}

describe('ProductsView.vue', () => {
  describe('template', () => {
    it('sets selectedProduct to undefined when EditOrDeleteProductDialog emits hide event', async () => {
      const wrapper = await wrapperFactory()
      wrapper.vm.selectedProduct = { id: 1, name: 'Product 1' }
      const EditOrDeleteProductDialog = wrapper.findComponent({ name: 'EditOrDeleteProductDialog' })
      EditOrDeleteProductDialog.vm.$emit('hide')
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.selectedProduct).toBeUndefined()
    })

    it('sets selectedProduct to passed product when ProductsTable emits product-selected event', async () => {
      const wrapper = await wrapperFactory()
      const product = { id: 1, name: 'Product 1' }
      const ProductsTable = wrapper.findComponent({ name: 'ProductsTable' })
      ProductsTable.vm.$emit('product-selected', product)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.selectedProduct).toEqual(product)
    })
  })

  describe('onBeforeMount', () => {
    it('should fetch products', async () => {
      const fetchProductsSpy = vi
        .spyOn(productsApi, 'getProducts')
        .mockResolvedValueOnce(mockProducts)
      const wrapper = await wrapperFactory()
      expect(fetchProductsSpy).toHaveBeenCalledTimes(1)
      expect(wrapper.vm.products).toEqual(mockProducts)
    })
  })

  describe('onEditOrDeleteProductSuccess method', () => {
    it('should fetch products again', async () => {
      const fetchProductsSpy = vi
        .spyOn(productsApi, 'getProducts')
        .mockResolvedValueOnce(mockProducts)
      const wrapper = await wrapperFactory()
      wrapper.vm.onEditOrDeleteProductSuccess()
      expect(fetchProductsSpy).toHaveBeenCalledTimes(2)
    })
  })
})
