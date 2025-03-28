import '@/assets/main.css';

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { usePrimeVue } from './plugins/primeVue/primeVue'

import App from './app/App.vue'
import router from './router'
import { registerI18nInstance } from './plugins/i18n/createI18n'

const app = createApp(App)

app.use(createPinia())
app.use(router)
usePrimeVue(app)
registerI18nInstance(app)

app.mount('#app')
