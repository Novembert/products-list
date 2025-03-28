import type { Tag, TagColor } from './Tag'

export interface Product {
  id: number
  name: string
  description?: string
  price: string
  vatRate: string
  createdAt: string
  updatedAt: string
  tag?: Tag
}

export interface CreateProductPayload {
  name: string
  description?: string
  price: number
  vatRate: number
  tag?: {
    name: string
    color: TagColor
  }
}

export type UpdateProductPayload = CreateProductPayload & {
  id: number
  createdAt: string
  updatedAt: string
}
