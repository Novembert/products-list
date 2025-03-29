import FormField from './FormField.vue'
import { describe, it, expect } from 'vitest'
import { shallowMount, flushPromises } from '@vue/test-utils'

const wrapperFactory = async ({
  error,
  required,
}: {
  error?: string
  required?: boolean
} = {}) => {
  const wrapper = shallowMount(FormField, {
    props: {
      label: 'Label',
      error,
      required,
    },
  })
  await flushPromises()
  return wrapper
}

describe('FormField.vue', () => {
  describe('template', () => {
    it('renders error message when error prop receives a text value', async () => {
      const wrapper = await wrapperFactory({ error: 'Error message' })
      expect(wrapper.find('[data-testid="error-message"]').text()).toBe('Error message')
    })

    it('does not render error message when error prop is empty', async () => {
      const wrapper = await wrapperFactory()
      expect(wrapper.find('[data-testid="error-message"]').exists()).toBe(false)
    })

    it('renders required indicator when required prop is true', async () => {
      const wrapper = await wrapperFactory({ required: true })
      expect(wrapper.find('[data-testid="required-indicator"]').exists()).toBe(true)
    })
  })
})
