<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository
{
  /**
   * @return Collection<Product>
   */
  public function getAllProducts(): Collection
  {
    return Product::with('tag')->get();
  }

  /**
   * @return Product
   */
  public function getProduct(int $id): ?Product
  {
    return Product::with('tag')->find($id);
  }

  /**
   * @return Product
   */
  public function saveProduct(Product $product): void
  {
    $product->save();
  }

  public function deleteProduct(Product $product): void
  {
    $product->delete();
  }
}