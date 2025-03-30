import type { AxiosError } from "axios";

export enum ApiErrorCode {
  Unauthenticated = 'UNAUTHENTICATED',
  ProductNotFound = 'PRODUCT_NOT_FOUND',
  UnknownError = 'UNKNOWN_ERROR',
}

export type ApiError = AxiosError<{
  error: {
    message: string;
    code: ApiErrorCode;
  }
}>