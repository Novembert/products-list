import type { Product } from "@/types/models/Product";
import { TagColor } from "@/types/models/Tag";

export const mockProduct: Product = {
  id: 1,
  name: "Test Product",
  description: "This is a test product",
  price: "100.00",
  vatRate: "0.25",
  createdAt: new Date().toISOString(),
  updatedAt: new Date().toISOString(),
  tag: {
    id: 1,
    name: "Test Tag",
    color: TagColor.Red,
  }
}

export const mockProducts: Product[] = [
  mockProduct,
  {
    id: 2,
    name: "Another Product",
    description: "This is another product",
    price: "200.00",
    vatRate: "0.25",
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString(),
    tag: {
      id: 2,
      name: "Another Tag",
      color: TagColor.Blue,
    }
  },
  {
    id: 3,
    name: "Third Product",
    description: "This is the third product",
    price: "300.00",
    vatRate: "0.25",
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString(),
  },
  {
    id: 4,
    name: "Fourth Product",
    description: "This is the fourth product",
    price: "400.00",
    vatRate: "0.25",
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString(),
  },
  {
    id: 5,
    name: "Fifth Product",
    description: "This is the fifth product",
    price: "500.00",
    vatRate: "0.25",
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString(),
  }
]