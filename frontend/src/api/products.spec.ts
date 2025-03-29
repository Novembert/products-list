import * as apiClient from '@/plugins/apiClient';
import { getProducts, getProduct, createProduct, updateProduct, deleteProduct } from './products';
import { afterAll, beforeEach, vi, describe, expect, it } from 'vitest';
import type { CreateProductPayload, Product, UpdateProductPayload } from '@/types/models/Product';
import { mockProducts, mockProduct } from '@/__mocks__/apiData/products';
import { TagColor } from '@/types/models/Tag';

describe('products API', () => {
  const mockRequest = vi.spyOn(apiClient, 'request');
  beforeEach(() => {
    mockRequest.mockClear();
  });

  afterAll(() => {
    mockRequest.mockRestore();
  });

  describe('getProducts', () => {
    it('calls request with correct parameters', async () => {
      await getProducts();
      expect(mockRequest).toHaveBeenCalledWith({
        method: 'GET',
        url: '/products',
      });
    });

    it('returns data from request', async () => {
      mockRequest.mockResolvedValueOnce(mockProducts);
      const result = await getProducts();
      expect(result).toEqual(mockProducts);
    });
  })

  describe('getProduct', () => {
    it('calls request with correct parameters', async () => {
      const productId = 1;
      await getProduct(productId);
      expect(mockRequest).toHaveBeenCalledWith({
        method: 'GET',
        url: `/products/${productId}`,
      });
    });

    it('returns data from request', async () => {
      mockRequest.mockResolvedValueOnce(mockProduct);
      const result = await getProduct(1);
      expect(result).toEqual(mockProduct);
    });
  });

  describe('createProduct', () => {
    const MOCK_CREATE_PRODUCT_PAYLOAD: CreateProductPayload = {
      name: 'Test Product',
      description: 'This is a test product',
      price: 123.45,
      vatRate: 0.25,
      tag: {
        name: 'Test Tag',
        color: TagColor.Red,
      }
    };

    it('calls request with correct parameters', async () => {
      await createProduct(MOCK_CREATE_PRODUCT_PAYLOAD);
      expect(mockRequest).toHaveBeenCalledWith({
        method: 'POST',
        url: '/products',
        data: MOCK_CREATE_PRODUCT_PAYLOAD,
      });
    });

    it('returns data from request', async () => {
      mockRequest.mockResolvedValueOnce(mockProduct);
      const result = await createProduct(MOCK_CREATE_PRODUCT_PAYLOAD);
      expect(result).toEqual(mockProduct);
    });
  });

  describe('updateProduct', () => {
    const MOCK_UPDATE_PRODUCT_PAYLOAD: UpdateProductPayload = {
      id: 1,
      name: 'Updated Product',
      description: 'This is an updated product',
      price: 543.21,
      vatRate: 0.15,
      tag: {
        name: 'Updated Tag',
        color: TagColor.Blue,
      },
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
    };

    it('calls request with correct parameters', async () => {
      await updateProduct(MOCK_UPDATE_PRODUCT_PAYLOAD);
      expect(mockRequest).toHaveBeenCalledWith({
        method: 'PUT',
        url: `/products/${MOCK_UPDATE_PRODUCT_PAYLOAD.id}`,
        data: MOCK_UPDATE_PRODUCT_PAYLOAD,
      });
    });

    it('returns data from request', async () => {
      mockRequest.mockResolvedValueOnce(mockProduct);
      const result = await updateProduct(MOCK_UPDATE_PRODUCT_PAYLOAD);
      expect(result).toEqual(mockProduct);
    });
  });

  describe('deleteProduct', () => {
    it('calls request with correct parameters', async () => {
      const productId = 1;
      await deleteProduct(productId);
      expect(mockRequest).toHaveBeenCalledWith({
        method: 'DELETE',
        url: `/products/${productId}`,
      });
    });

    it('returns data from request', async () => {
      mockRequest.mockResolvedValueOnce(undefined);
      const result = await deleteProduct(1);
      expect(result).toBeUndefined();
    });
  });
});
  