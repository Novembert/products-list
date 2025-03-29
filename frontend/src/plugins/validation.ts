import type {
  BaseValidation,
  ValidationRuleWithoutParams,
  ValidationRuleWithParams,
} from '@vuelidate/core'
import * as validators from '@vuelidate/validators'
import { useI18n } from 'vue-i18n'

export function useValidation() {
  const i18n = useI18n()

  const getFirstErrorMessage = (field: BaseValidation) => {
    return (field.$errors?.[0]?.$message || field.$message?.[0]?.[0] || '') as string
  }

  const { createI18nMessage } = validators
  const withI18nMessage = createI18nMessage({ t: i18n.t.bind(i18n) })

  const required = withI18nMessage(validators.required)

  return {
    getFirstErrorMessage,
    required,
  }
}
