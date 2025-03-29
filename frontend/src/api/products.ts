import type { CreateProductPayload, Product, UpdateProductPayload } from '@/types/models/Product'
import { request } from '@/plugins/apiClient'

export const getProducts = async (): Promise<Product[]> => {
  return await request({
    method: 'GET',
    url: '/products',
  })
}

export const getProduct = async (id: number): Promise<Product> => {
  return await request({
    method: 'GET',
    url: `/products/${id}`,
  })
}

export const createProduct = async (product: CreateProductPayload): Promise<Product> => {
  return await request({
    method: 'POST',
    url: '/products',
    data: product,
  })
}

export const updateProduct = async (product: UpdateProductPayload): Promise<Product> => {
  return await request({
    method: 'PUT',
    url: `/products/${product.id}`,
    data: product,
  })
}

export const deleteProduct = async (id: number): Promise<void> => {
  return await request({
    method: 'DELETE',
    url: `/products/${id}`,
  })
}
