import { vi } from "vitest";

vi.mock('axios', async importOriginal => {
  const original = await importOriginal<typeof import('axios')>();
  return {
    ...original,
    default: {
      get: vi.fn(),
      post: vi.fn(),
      put: vi.fn(),
      delete: vi.fn(),
      request: vi.fn(() => ({ data: 'response' })),
      create: vi.fn().mockReturnThis(),
      interceptors: {
        request: { use: vi.fn(), eject: vi.fn() },
        response: { use: vi.fn(), eject: vi.fn() },
      },
    }
  };
})