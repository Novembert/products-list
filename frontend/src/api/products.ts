import type { Product } from "@/types/models/Product";
import { mockProducts } from "./mocks/products";

export const getProducts = async (): Promise<Product[]> => {
  // return await request({
  //   method: 'GET',
  //   url: '/products'
  // });
  setTimeout(() => {}, 1000);
  return Promise.resolve(mockProducts);
}

export const getProduct = async (id: number): Promise<Product> => {
  // return await request({
  //   method: 'GET',
  //   url: `/products/${id}`
  // });
  setTimeout(() => {}, 1000);
  return Promise.resolve(mockProducts.find(product => product.id === id) as Product);
}

export const createProduct = async (product: Product): Promise<Product> => {
  // return await request({
  //   method: 'POST',
  //   url: '/products',
  //   data: product
  // });
  setTimeout(() => {}, 1000);
  return Promise.resolve({
    ...product,
    id: Math.max(...mockProducts.map(p => p.id)) + 1,
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  });
}

export const updateProduct = async (product: Product): Promise<Product> => {
  // return await request({
  //   method: 'PUT',
  //   url: `/products/${product.id}`,
  //   data: product
  // });
  setTimeout(() => {}, 1000);
  return Promise.resolve(product);
}

export const deleteProduct = async (id: number): Promise<void> => {
  // return await request({
  //   method: 'DELETE',
  //   url: `/products/${id}`
  // });
  setTimeout(() => {}, 1000);
  return Promise.resolve();
}