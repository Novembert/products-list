import type { CreateProductPayload, Product, UpdateProductPayload } from '@/types/models/Product'
import { mockProducts } from './mocks/products'

export const getProducts = async (): Promise<Product[]> => {
  // return await request({
  //   method: 'GET',
  //   url: '/products'
  // });
  await new Promise((resolve) => setTimeout(resolve, 2000))
  return Promise.resolve(mockProducts)
}

export const getProduct = async (id: number): Promise<Product> => {
  // return await request({
  //   method: 'GET',
  //   url: `/products/${id}`
  // });
  await new Promise((resolve) => setTimeout(resolve, 2000))
  return Promise.resolve(mockProducts.find((product) => product.id === id) as Product)
}

export const createProduct = async (product: CreateProductPayload): Promise<Product> => {
  // return await request({
  //   method: 'POST',
  //   url: '/products',
  //   data: product
  // });
  const { tag, ...rest } = product
  await new Promise((resolve) => setTimeout(resolve, 2000))
  return Promise.resolve({
    ...rest,
    id: Math.max(...mockProducts.map((p) => p.id)) + 1,
    price: product.price.toString(),
    vatRate: product.vatRate.toString(),
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString(),
    ...(product.tag
      ? {
          tag: {
            name: product.tag.name,
            color: product.tag.color,
            id: Math.max(...mockProducts.map((p) => p.tag?.id || 0)) + 1,
          },
        }
      : {}),
  })
}

export const updateProduct = async (product: UpdateProductPayload): Promise<Product> => {
  // return await request({
  //   method: 'PUT',
  //   url: `/products/${product.id}`,
  //   data: product
  // });
  await new Promise((resolve) => setTimeout(resolve, 2000))
  const { tag, ...rest } = product
  return Promise.resolve({
    ...rest,
    price: product.price.toString(),
    vatRate: product.vatRate.toString(),
    updatedAt: new Date().toISOString(),
    ...(product.tag
      ? {
          tag: {
            name: product.tag.name,
            color: product.tag.color,
            id: Math.max(...mockProducts.map((p) => p.tag?.id || 0)) + 1,
          },
        }
      : {}),
  })
}

export const deleteProduct = async (id: number): Promise<void> => {
  // return await request({
  //   method: 'DELETE',
  //   url: `/products/${id}`
  // });
  await new Promise((resolve) => setTimeout(resolve, 2000))
  return Promise.resolve()
}
