<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\DTOs\CreateProduct\CreateProductDTO;
use App\Modules\Product\Repositories\ProductRepository;
use Illuminate\Support\Collection;
use App\Modules\Product\Exceptions\ProductNotFoundException;
use App\Modules\Product\Models\Product;
use App\Modules\Product\DTOs\FindOrCreateTag\FindOrCreateTagDTO;
use App\Modules\Product\DTOs\UpdateProduct\UpdateProductDTO;
use Illuminate\Support\Facades\DB;

class ProductService
{
  public function __construct(protected ProductRepository $productRepository, protected TagService $tagService) {}

  /**
   * @return Collection<Product>
   */
  public function getAllProducts()
  {
    return $this->productRepository->getAllProducts();
  }

  public function getProduct(int $id): Product
  {
    $product = $this->productRepository->getProduct($id);

    if (!$product) {
      throw new ProductNotFoundException();
    }

    return $product;
  }

  public function createProduct(CreateProductDTO $data): Product
  {
    return DB::transaction(function () use ($data) {
      $product = new Product();
      $product->name = $data->name;
      $product->description = $data->description;
      $product->price = $data->price;
      $product->vat_rate = $data->vatRate;

      if ($data->tag) {
        $product->tag_id = $this->tagService->findOrCreateTag(new FindOrCreateTagDTO(
          name: $data->tag->name,
          color: $data->tag->color,
        ))->id;
      }

      $this->productRepository->saveProduct($product);
      return $product;
    });
  }

  public function updateProduct(int $id, UpdateProductDTO $data): Product
  {
    return DB::transaction(function () use ($id, $data) {
      $product = $this->productRepository->getProduct($id);

      if (!$product) {
        throw new ProductNotFoundException();
      }
  
      $product->name = $data->name;
      $product->description = $data->description;
      $product->price = $data->price;
      $product->vat_rate = $data->vatRate;
      if ($data->tag) {
        $product->tag_id = $this->tagService->findOrCreateTag(new FindOrCreateTagDTO(
          name: $data->tag->name,
          color: $data->tag->color,
        ))->id;
      }
      
      $this->productRepository->saveProduct($product);
      // refetch product
      $product = $this->productRepository->getProduct($product->id);
      return $product;
    });
  }

  public function deleteProduct(int $id): void
  {
    DB::transaction(function () use ($id) {
      $product = $this->productRepository->getProduct($id);

      if (!$product) {
        throw new ProductNotFoundException();
      }

      $this->productRepository->deleteProduct($product);
    });
  }
}