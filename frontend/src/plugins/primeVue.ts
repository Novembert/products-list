import type { App } from 'vue'
import PrimeVue from 'primevue/config'
import Aura from '@primeuix/themes/aura'

export const usePrimeVue = (app: App) => {
  app.use(PrimeVue, {
    pt: {
      dialog: {
        root: 'w-full h-[92.5vh] !max-h-[92.5vh] overflow-hidden',
        mask: '!items-end',
        header: 'text-xl font-bold',
        content: 'grow',
      },
      card: {
        header: 'px-4 pt-2',
      },
      datatable: {
        root: 'rounded-lg border border-gray-200 bg-white overflow-hidden',
        tablecontainer: 'px-3',
        tbody: '[&_tr.body-row:last-child_>_td]:!border-b-0',
        bodyRow: 'body-row',
        header: '!border-none'
      },
    },
    theme: {
      preset: Aura,
      options: {
        darkModeSelector: false,
      },
    },
  })
}
