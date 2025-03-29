import type { App } from 'vue'
import PrimeVue from 'primevue/config'
import Aura from '@primeuix/themes/aura'

export const usePrimeVue = (app: App) => {
  app.use(PrimeVue, {
    pt: {
      dialog: {
        root: 'max-md:w-full md:min-w-128 max-md:h-[92.5vh] max-md:!max-h-[92.5vh] overflow-hidden',
        mask: 'max-md:!items-end md:bg-black/50',
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
        header: '!border-none',
      },
      column: {
        headercell: 'whitespace-nowrap',
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
