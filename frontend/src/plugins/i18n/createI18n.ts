import { LanguageCode } from '@/types/language'
import danish from './da-DK.json'
import english from './en-US.json'
import { createI18n } from 'vue-i18n'
import type { App } from 'vue'

let i18nInstance: ReturnType<typeof createI18nInstance> | null = null

export const createI18nInstance = () => {
  const messages = {
    [LanguageCode.Danish]: danish,
    [LanguageCode.English]: english,
  }
  return  createI18n({
    legacy: false,
    locale: LanguageCode.Danish,
    fallbackLocale: LanguageCode.Danish,
    messages,
  })
}

export const getI18nInstance = () => {
  if (!i18nInstance) {
    i18nInstance = createI18nInstance()
  }
  return i18nInstance
}


export const registerI18nInstance = (app: App) => {
  const i18n = createI18nInstance()
  i18nInstance = i18n
  app.use(i18n)
}
