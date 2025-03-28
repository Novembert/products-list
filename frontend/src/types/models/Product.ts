import type { Tag } from "./Tag";

export interface Product {
  id: number;
  name: string;
  description?: string;
  price: string;
  vatRate: string;
  createdAt: string;
  updatedAt: string;
  tag?: Tag;
}

export interface CreateProductRequest {
  name: string;
  description?: string;
  price: string;
  vatRate: string;
  tag?: {
    name: string;
    color: string;
  }
}

export type UpdateProductRequest = CreateProductRequest & {
  id: number;
  createdAt: string;
  updatedAt: string;
}