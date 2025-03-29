import { describe, expect, it, vi } from "vitest";
import { requestInterceptor, responseInterceptor, axiosInstance, request } from "./apiClient";
import { type AxiosResponse, type InternalAxiosRequestConfig } from "axios";

describe("apiClient", () => {
  describe('requestInterceptor', () => {
    it('should add the Content-Type and Accept headers to the request', () => {
      const config = {
        headers: {}
      };
      const result = requestInterceptor(config as InternalAxiosRequestConfig);
      expect(result.headers['Content-Type']).toBe('application/json');
      expect(result.headers['Accept']).toBe('application/json');
    });
  })

  describe('responseInterceptor', () => {
    it('should return the response as is', () => {
      const response = { data: 'test' };
      const result = responseInterceptor(response as AxiosResponse);
      expect(result).toBe(response);
    });
  });

  describe('request', () => {
    it('should call axiosInstance.request with the correct config', () => {
      const config = { method: 'GET', url: '/test' };
      const axiosRequestSpy = vi.spyOn(axiosInstance, 'request').mockResolvedValue({ data: 'test' } as AxiosResponse);
      request(config);
      expect(axiosRequestSpy).toHaveBeenCalledWith(config);
      axiosRequestSpy.mockRestore();
    });
  })
});