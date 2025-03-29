import { createApp } from "vue";
import { useValidation } from "./validation";
import { createI18n } from "vue-i18n";
import { describe, it, expect } from "vitest";
import { flushPromises } from "@vue/test-utils";

async function appWithComposableFactory(composable: () => ReturnType<typeof useValidation>) {
  let composableOutput = {} as ReturnType<typeof useValidation>;
  const app = createApp({
    template: `<div></div>`,
    setup() {
      composableOutput = composable();
      return;
    }
  });
  app.use(createI18n({ legacy: false }));
  app.mount(document.createElement("div"));
  await flushPromises();
  return composableOutput;
}

describe("validation.ts", () => {
  describe('useValidation', () => {
    describe('getFirstErrorMessage', () => {
      it('returns the first error message from field errors array', async () => {
        const { getFirstErrorMessage } = await appWithComposableFactory(useValidation);
        const field = {
          $errors: [{ $message: "Error 1" }, { $message: "Error 2" }],
        }

        // @ts-expect-error -- We're just mocking the field object here, so we don't need a proper type.
        expect(getFirstErrorMessage(field)).toBe("Error 1");
      });

      it('returns first error message from field errors array in case of another field messages structure', async () => {
        const { getFirstErrorMessage } = await appWithComposableFactory(useValidation);
        const field = {
          $message: [["Error 1", "Error 2"], ["Error 3"]],
        }

        // @ts-expect-error -- We're just mocking the field object here, so we don't need a proper type.
        expect(getFirstErrorMessage(field)).toBe("Error 1");
      });

      it('returns empty string if no errors are provided', async () => {
        const { getFirstErrorMessage } = await appWithComposableFactory(useValidation);
        const field = {
          $errors: []
        }
        // @ts-expect-error -- We're just mocking the field object here, so we don't need a proper type.
        expect(getFirstErrorMessage(field)).toBe("");
      });
    });
  });
});