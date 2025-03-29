import { describe, it, expect, vi } from 'vitest'
import { type App } from 'vue'
import { usePrimeVue } from './primeVue'

describe('primeVue.ts', () => {
  describe('usePrimeVue', () => {
    it('should register primeVue instance with the app', () => {
      const app = {
        use: vi.fn(),
      } as unknown as App
      usePrimeVue(app)
      expect(app.use).toHaveBeenCalledTimes(1)
    })
  })
})
