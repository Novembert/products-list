import { getI18nInstance } from "@/plugins/i18n/createI18n";
import type { ApiErrorCode } from "@/types/models/ApiError";
import { ToastEventBus } from "primevue";

export const useDisplayErrorMessage = () => {
  const i18n = getI18nInstance();
  console.log(i18n)
  const displayErrorMessage = (errorCode: ApiErrorCode) => {
    ToastEventBus.emit('add', {
      severity: 'error',
      summary: 'Error',
      detail: i18n.global.t(`errors.${errorCode}`),
      life: 5000
    })
  }

  return displayErrorMessage;
}