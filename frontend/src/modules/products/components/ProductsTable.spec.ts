import type { Product } from '@/types/models/Product'
import ProductsTable from './ProductsTable.vue'
import { shallowMount, flushPromises, VueWrapper } from '@vue/test-utils'
import { describe, it, expect, vi } from 'vitest'
import { mockProducts } from '@/__mocks__/apiData/products'

const wrapperFactory = async ({
  products = mockProducts,
  loading = false,
}: {
  products?: Product[]
  loading?: boolean
} = {}): Promise<VueWrapper<any>> => {
  const wrapper = shallowMount(ProductsTable, {
    props: {
      products,
      loading,
    },
  })
  await flushPromises()
  return wrapper
}

describe('ProductsTable.vue', () => {
  describe('tableItems computed property', () => {
    it('returns products array with formattedVatRate and a property holding the original product', async () => {
      const wrapper = await wrapperFactory()
      expect(wrapper.vm.tableItems[0]).toEqual({
        ...mockProducts[0],
        formattedVatRate: '25%',
        product: mockProducts[0],
      })
    })
  })

  describe('productsCount computed property', () => {
    it('returns the length of the products array', async () => {
      const wrapper = await wrapperFactory()
      expect(wrapper.vm.productsCount).toBe(mockProducts.length)
    })
  })

  describe('onRowSelect method', () => {
    it('emits productSelected event with the selected product original data', async () => {
      const wrapper = await wrapperFactory()
      const tableDataItem = wrapper.vm.tableItems[0]
      wrapper.vm.onRowSelect({
        data: tableDataItem,
      })
      expect(wrapper.emitted('productSelected')).toBeTruthy()
      expect(wrapper.emitted('productSelected')?.[0]).toEqual([tableDataItem.product])
    })
  })
})
