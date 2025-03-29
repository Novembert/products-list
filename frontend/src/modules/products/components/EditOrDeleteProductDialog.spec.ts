import EditOrDeleteProductDialog from "./EditOrDeleteProductDialog.vue";
import { shallowMount, flushPromises, VueWrapper } from "@vue/test-utils";
import { describe, it, expect, vi } from "vitest";
import * as productsApi from "@/api/products";
import type { Product } from "@/types/models/Product";
import { mockProduct } from "@/api/mocks/products";

const wrapperFactory = async ({
  product
}: {
  product: Product | undefined
} = {
  product: mockProduct
}): Promise<VueWrapper<any>> => {
  const wrapper = shallowMount(EditOrDeleteProductDialog, {
    props: {
      product
    }
  });
  await flushPromises();
  return wrapper;
};

describe("EditOrDeleteProductDialog.vue", () => {
  describe("template", () => {
    it("binds isVisible computed property to Dialog visibility", async () => {
      const wrapper = await wrapperFactory();
      const dialog = wrapper.findComponent({ name: "Dialog" });

      dialog.vm.$emit("update:visible", false);
      await wrapper.vm.$nextTick();
      expect(wrapper.emitted("hide")).toBeTruthy();
    });
  });

  describe("props.product watcher", () => {
    it("resets vuelidate instance and updates updatedProduct when product prop changes", async () => {
      const wrapper = await wrapperFactory();
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
      const wrapper = await wrapperFactory();

      await wrapper.setProps({ product: undefined });
      expect(wrapper.vm.updatedProduct).toBeUndefined();
    });
  });

  describe('loading computed property', () => {
    it('returns true if updating or deleting is true', async () => {
      const wrapper = await wrapperFactory();
      wrapper.vm.updating = true;
      wrapper.vm.deleting = false;
      expect(wrapper.vm.loading).toBe(true);

      wrapper.vm.updating = false;
      wrapper.vm.deleting = true;
      expect(wrapper.vm.loading).toBe(true);
    });

    it('returns false if updating and deleting are false', async () => {
      const wrapper = await wrapperFactory();
      wrapper.vm.updating = false;
      wrapper.vm.deleting = false;
      expect(wrapper.vm.loading).toBe(false);
    });
  });

  describe("onSubmit method", () => {
    it("calls updateProduct API with updatedProduct and emits success event", async () => {
      const wrapper = await wrapperFactory();
      const updateProductSpy = vi.spyOn(productsApi, "updateProduct")

      wrapper.vm.updatedProduct = { id: 1, name: "Updated Product", price: 200, vatRate: 0.15 };
      await wrapper.vm.onSubmit();

      expect(updateProductSpy).toHaveBeenCalledWith(wrapper.vm.updatedProduct);
      expect(wrapper.emitted("success")).toBeTruthy();
    });

    it("does nothing if updatedProduct is not set", async () => {
      const wrapper = await wrapperFactory();
      const updateProductSpy = vi.spyOn(productsApi, "updateProduct");

      wrapper.vm.updatedProduct = undefined;
      await wrapper.vm.onSubmit();

      expect(updateProductSpy).not.toHaveBeenCalled();
    });

    it("sets updating to true while submitting and resets it after", async () => {
      const wrapper = await wrapperFactory();
      vi.spyOn(productsApi, "updateProduct");

      wrapper.vm.updatedProduct = { id: 1, name: "Updated Product", price: 200, vatRate: 0.15 };
      const submitPromise = wrapper.vm.onSubmit();

      expect(wrapper.vm.updating).toBe(true);
      await submitPromise;
      expect(wrapper.vm.updating).toBe(false);
    });
  });

  describe('onDelete method', () => {
    it('calls deleteProduct API with product ID and emits success event', async () => {
      const wrapper = await wrapperFactory();
      const deleteProductSpy = vi.spyOn(productsApi, "deleteProduct");

      await wrapper.vm.onDelete();

      expect(deleteProductSpy).toHaveBeenCalledWith(1);
      expect(wrapper.emitted("success")).toBeTruthy();
    });

    it('does nothing if product is not set', async () => {
      const wrapper = await wrapperFactory({ product: undefined });
      const deleteProductSpy = vi.spyOn(productsApi, "deleteProduct");

      await wrapper.vm.onDelete();

      expect(deleteProductSpy).not.toHaveBeenCalled();
    });

    it('sets deleting to true while deleting and resets it after', async () => {
      const wrapper = await wrapperFactory();
      vi.spyOn(productsApi, "deleteProduct");

      const deletePromise = wrapper.vm.onDelete();

      expect(wrapper.vm.deleting).toBe(true);
      await deletePromise;
      expect(wrapper.vm.deleting).toBe(false);
    });
  })
});