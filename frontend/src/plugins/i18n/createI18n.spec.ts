import { describe, it, expect, vi } from "vitest";
import { type App } from "vue";
import { registerI18nInstance } from "./createI18n";

describe("createI18n.ts", () => {
  describe("registerI18nInstance", () => {
    it("should register i18n instance with the app", () => {
      const app = {
        use: vi.fn(),
      } as unknown as App;
      registerI18nInstance(app);
      expect(app.use).toHaveBeenCalledTimes(1);
    });
  });
});