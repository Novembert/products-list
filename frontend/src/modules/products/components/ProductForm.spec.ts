import ProductForm from "./ProductForm.vue";
import { shallowMount, flushPromises, VueWrapper } from '@vue/test-utils'
import { describe, it, expect, vi } from 'vitest'
import type { CreateProductPayload, UpdateProductPayload } from '@/types/models/Product'
import { mockProduct } from '@/__mocks__/apiData/products'
import { TagColor } from "@/types/models/Tag";

const MOCK_PAYLOAD: UpdateProductPayload = {
    ...mockProduct,
    price: 123.45,
    vatRate: 0.25,
    tag: {
      name: mockProduct.tag!.name,
      color: mockProduct.tag!.color,
    }
}

const wrapperFactory = async ({
  product
}: {
  product?: CreateProductPayload | UpdateProductPayload
} = {
  product: MOCK_PAYLOAD
}): Promise<VueWrapper<any>> => {
  const wrapper = shallowMount(ProductForm, {
    props: {
      modelValue: product,
    }
  })
  await flushPromises()
  return wrapper
}

describe('ProductForm.vue', () => {
  describe('formData ref', () => {
    it("prefills tag name and color with modelValue's data", async () => {
      const wrapper = await wrapperFactory()
      expect(wrapper.vm.formData.tag.name).toBe(MOCK_PAYLOAD.tag!.name)
      expect(wrapper.vm.formData.tag.color).toBe(MOCK_PAYLOAD.tag!.color)
    });

    it('sets default values for tag name and color if modelValue does not have a tag', async () => {
      const wrapper = await wrapperFactory({ product: { ...MOCK_PAYLOAD, tag: undefined } })
      expect(wrapper.vm.formData.tag.name).toBe('')
      expect(wrapper.vm.formData.tag.color).toBe(TagColor.Red)
    });
  });

  describe('vatRate computed property', () => {
    it('returns null if formData.vatRate is not null', async () => {
      const wrapper = await wrapperFactory()
      wrapper.vm.formData.vatRate = null;
      expect(wrapper.vm.vatRate).toBeNull()
    });

    it('returns formData.vatRate multiplied by 100 if formData.vatRate is not null', async () => {
      const wrapper = await wrapperFactory()
      wrapper.vm.formData.vatRate = 0.25;
      expect(wrapper.vm.vatRate).toBe(25)
    });

    it('sets formData.vatRate to passed value divided by 100 when vatRate setter is called', async () => {
      const wrapper = await wrapperFactory()
      wrapper.vm.vatRate = 25
      expect(wrapper.vm.formData.vatRate).toBe(0.25)
    });

    it('sets formData to null when vatRate setter is called with null', async () => {
      const wrapper = await wrapperFactory()
      wrapper.vm.vatRate = null
      expect(wrapper.vm.formData.vatRate).toBeNull()
    });
  })

  describe('formData watcher', () => {
    it('updates modelValue when formData changes', async () => {
      const wrapper = await wrapperFactory()
      const newFormData = { ...MOCK_PAYLOAD, name: 'Updated Product' }
      wrapper.vm.formData = newFormData;
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted('update:modelValue')).toBeTruthy()
      expect(wrapper.emitted('update:modelValue')![0][0]).toEqual(newFormData)
    });

    it('should not emit tag property if formData.tag does not have name or color', async () => {
      const wrapper = await wrapperFactory()
      wrapper.vm.formData.tag.name = ''
      wrapper.vm.formData = { ...MOCK_PAYLOAD, tag: { name: '', color: '' } }
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted('update:modelValue')![0][0]).toEqual({
        ...MOCK_PAYLOAD,
        tag: undefined
      })
    });
  })
});