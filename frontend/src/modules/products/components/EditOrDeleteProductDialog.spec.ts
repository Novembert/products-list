import EditOrDeleteProductDialog from "./EditOrDeleteProductDialog.vue";
import { shallowMount, flushPromises, VueWrapper } from "@vue/test-utils";
import { describe, it, expect, vi } from "vitest";
import * as productsApi from "@/api/products";

const wrapperFactory = async (props = {}): Promise<VueWrapper<any>> => {
  const wrapper = shallowMount(EditOrDeleteProductDialog, {
    props,
  });
  await flushPromises();
  return wrapper;
};

describe("EditOrDeleteProductDialog.vue", () => {
  describe("template", () => {
    it("binds isVisible computed property to Dialog visibility", async () => {
      const wrapper = await wrapperFactory({ product: { id: 1, name: "Test Product", price: 100, vatRate: 0.25 } });
      const dialog = wrapper.findComponent({ name: "Dialog" });

      dialog.vm.$emit("update:visible", false);
      await wrapper.vm.$nextTick();
      expect(wrapper.emitted("hide")).toBeTruthy();
    });

    it("does not render ProductForm when product is not provided", async () => {
      const wrapper = await wrapperFactory({ product: undefined });
      const productForm = wrapper.findComponent({ name: "ProductForm" });
      expect(productForm.exists()).toBe(false);
    });
  });

  describe("watchers", () => {
    it("resets vuelidate instance and updates updatedProduct when product prop changes", async () => {
      const wrapper = await wrapperFactory({ product: { id: 1, name: "Test Product", price: 100, vatRate: 0.25 } });
      const resetSpy = vi.spyOn(wrapper.vm.v$, "$reset");

      await wrapper.setProps({ product: { id: 2, name: "Updated Product", price: 200, vatRate: 0.15 } });
      expect(resetSpy).toHaveBeenCalledTimes(1);
      expect(wrapper.vm.updatedProduct).toEqual({
        id: 2,
        name: "Updated Product",
        price: 200,
        vatRate: 0.15,
      });
    });

    it("clears updatedProduct when product prop is undefined", async () => {
      const wrapper = await wrapperFactory({ product: { id: 1, name: "Test Product", price: 100, vatRate: 0.25 } });

      await wrapper.setProps({ product: undefined });
      expect(wrapper.vm.updatedProduct).toBeUndefined();
    });
  });

  describe("onSubmit method", () => {
    it("calls updateProduct API with updatedProduct and emits success event", async () => {
      const wrapper = await wrapperFactory({ product: { id: 1, name: "Test Product", price: 100, vatRate: 0.25 } });
      const updateProductSpy = vi.spyOn(productsApi, "updateProduct")

      wrapper.vm.updatedProduct = { id: 1, name: "Updated Product", price: 200, vatRate: 0.15 };
      await wrapper.vm.onSubmit();

      expect(updateProductSpy).toHaveBeenCalledWith(wrapper.vm.updatedProduct);
      expect(wrapper.emitted("success")).toBeTruthy();
    });

    it("does nothing if updatedProduct is not set", async () => {
      const wrapper = await wrapperFactory({ product: { id: 1, name: "Test Product", price: 100, vatRate: 0.25 } });
      const updateProductSpy = vi.spyOn(productsApi, "updateProduct");

      wrapper.vm.updatedProduct = undefined;
      await wrapper.vm.onSubmit();

      expect(updateProductSpy).not.toHaveBeenCalled();
    });

    it("sets loading to true while submitting and resets it after", async () => {
      const wrapper = await wrapperFactory({ product: { id: 1, name: "Test Product", price: 100, vatRate: 0.25 } });
      vi.spyOn(productsApi, "updateProduct");

      wrapper.vm.updatedProduct = { id: 1, name: "Updated Product", price: 200, vatRate: 0.15 };
      const submitPromise = wrapper.vm.onSubmit();

      expect(wrapper.vm.loading).toBe(true);
      await submitPromise;
      expect(wrapper.vm.loading).toBe(false);
    });
  });
});