import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { usePrimeVue } from './plugins/primeVue'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)
usePrimeVue(app)

app.mount('#app')
