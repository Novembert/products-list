import AppLayout from "./AppLayout.vue";
import { describe, it, expect, vi } from "vitest";
import { shallowMount, flushPromises } from "@vue/test-utils";

const mockRoute: {
  meta: {
    breadcrumb?: string;
  };
} = {
  meta: {
    breadcrumb: 'Example breadcrumb',
  }
};

vi.mock('vue-router', async (importActual) => {
  const actual = await importActual<typeof import('vue-router')>();
  return {
    ...actual,
    useRoute: () => mockRoute,
  };
});

const wrapperFactory = async () => {
  const wrapper = shallowMount(AppLayout);
  await flushPromises();
  return wrapper;
}

describe("AppLayout.vue", () => {
  describe('template', () => {
    it('renders breadcrumbs when route meta breadcrumb is set', async () => {
      const wrapper = await wrapperFactory();
      expect(wrapper.html()).toContain('Example breadcrumb');
    });

    it('does not render breadcrumbs when route meta breadcrumb is not set', async () => {
      mockRoute.meta.breadcrumb = undefined;
      const wrapper = await wrapperFactory();
      expect(wrapper.html()).not.toContain('Example breadcrumb');
    });
  });
});