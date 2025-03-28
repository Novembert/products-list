import { LanguageCode } from '@/types/language'
import danish from './da-DK.json'
import english from './en-US.json'
import { createI18n } from 'vue-i18n'
import type { App } from 'vue'

const createI18nInstance = () => {
  const messages = {
    [LanguageCode.Danish]: danish,
    [LanguageCode.English]: english,
  }

  return createI18n({
    legacy: false,
    locale: LanguageCode.Danish,
    fallbackLocale: LanguageCode.Danish,
    messages,
  })
}

export const registerI18nInstance = (app: App) => {
  const i18n = createI18nInstance()
  app.use(i18n)
}
