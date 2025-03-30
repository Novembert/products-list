import type { ApiError } from '@/types/models/ApiError'
import { useDisplayErrorMessage } from '@/utils/errorMessage'
import axios, {
  type AxiosRequestConfig,
  type AxiosResponse,
  type InternalAxiosRequestConfig,
} from 'axios'

const BASE_URL = import.meta.env.VITE_API_URL
const DEFAULT_TIMEOUT = 10000 // 10 seconds

export const axiosInstance = axios.create({
  baseURL: BASE_URL,
  timeout: DEFAULT_TIMEOUT,
})

export const requestInterceptor = (config: InternalAxiosRequestConfig) => {
  config.headers['Content-Type'] = 'application/json'
  config.headers['Accept'] = 'application/json'
  // We're setting a mock authorization header here.
  // In a real application, you would get the token from a store or cookie.
  config.headers['X-User-Id'] = 'DUMMY_USER_ID'
  return config
}

export const responseInterceptor = (response: AxiosResponse) => response

export const errorResponseInterceptor = (error: ApiError) => {
  const displayErrorMessage = useDisplayErrorMessage();
  if (error.response) {
    displayErrorMessage(error.response.data.error.code);
    return Promise.reject();
  }
  Promise.reject(error);
}

axiosInstance.interceptors.request.use(requestInterceptor, (error) => Promise.reject(error))
axiosInstance.interceptors.response.use(responseInterceptor, errorResponseInterceptor)

export const request = async <T>(config: AxiosRequestConfig): Promise<T> => {
  const response = await axiosInstance.request(config)
  return response.data.data
}
