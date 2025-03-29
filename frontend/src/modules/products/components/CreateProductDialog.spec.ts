import CreateProductDialog from "./CreateProductDialog.vue";
import { shallowMount, flushPromises, VueWrapper } from "@vue/test-utils";
import { describe, it, expect, vi } from "vitest";
import * as productsApi from "@/api/products";

const wrapperFactory = async (): Promise<VueWrapper<any>> => {
  const wrapper = shallowMount(CreateProductDialog);
  await flushPromises();
  return wrapper;
};

describe("CreateProductDialog.vue", () => {
  describe('template', () => {
    it('binds isVisible ref as v-model:visible for Dialog', async () => {
      const wrapper = await wrapperFactory();
      const dialog = wrapper.findComponent({ name: "Dialog" });

      dialog.vm.$emit("update:visible", true);
      await wrapper.vm.$nextTick();
      expect(wrapper.vm.isVisible).toBe(true);

      dialog.vm.$emit("update:visible", false);
      await wrapper.vm.$nextTick();
      expect(wrapper.vm.isVisible).toBe(false);
    });
  });

  describe('showDialog method', () => {
    it('sets the isVisible ref to true', async () => {
      const wrapper = await wrapperFactory();
      wrapper.vm.showDialog();
      expect(wrapper.vm.isVisible).toBe(true);
    });
  })

  describe('isVisible watcher', () => {
    it('calls $reset on vuelidate instance when isVisible is set to true', async () => {
      const wrapper = await wrapperFactory();
      const resetSpy = vi.spyOn(wrapper.vm.v$, "$reset");
      wrapper.vm.isVisible = true;
      await flushPromises();
      expect(resetSpy).toHaveBeenCalledTimes(1);
    });
  })

  describe('onSubmit method', () => {
    it('calls createProduct API passing the form data', async () => {
      const wrapper = await wrapperFactory();
      const createProductSpy = vi.spyOn(productsApi, "createProduct")
      wrapper.vm.product = { name: "Test Product", vatRate: 0.25, price: 100 };
      await wrapper.vm.onSubmit();
      expect(createProductSpy).toHaveBeenCalledWith(wrapper.vm.product);
    });

    it('emits success event and sets isVisible to false on successful product creation', async () => {
      const wrapper = await wrapperFactory();
      vi.spyOn(productsApi, "createProduct");
      wrapper.vm.product = { name: "Test Product", vatRate: 0.25, price: 100 };
      await wrapper.vm.onSubmit();
      expect(wrapper.emitted("success")).toBeTruthy();
      expect(wrapper.emitted("success")?.[0]).toEqual([]);
      expect(wrapper.vm.isVisible).toBe(false);
    });

    it('does nothing if product is not set', async () => {
      const wrapper = await wrapperFactory();
      vi.spyOn(productsApi, "createProduct");
      wrapper.vm.product = undefined;
      await wrapper.vm.onSubmit();
      expect(productsApi.createProduct).not.toHaveBeenCalled();
    });
  });
});