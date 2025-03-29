import axios, {
  type AxiosError,
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
  return config
}

export const responseInterceptor = (response: AxiosResponse) => response

export const errorResponseInterceptor = (error: AxiosError) => {
  // temporary
  console.error('Error response interceptor', error)

  if (error.response) {
    // handle error response
  }
  if (error.request) {
    // handle no response
  }

  // Request not made at all
}

axiosInstance.interceptors.request.use(requestInterceptor, (error) => Promise.reject(error))
axiosInstance.interceptors.response.use(responseInterceptor, errorResponseInterceptor)

export const request = async <T>(config: AxiosRequestConfig<T>): Promise<T> => {
  const response = await axiosInstance.request(config)
  return response.data
}
