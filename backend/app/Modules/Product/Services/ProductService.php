<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\DTOs\CreateProduct\CreateProductDTO;
use App\Modules\Product\Repositories\ProductRepository;
use Illuminate\Support\Collection;
use App\Modules\Product\Exceptions\ProductNotFoundException;
use App\Modules\Product\Models\Product;
use App\Modules\Product\DTOs\FindOrCreateTag\FindOrCreateTagDTO;
use App\Modules\Product\DTOs\UpdateProduct\UpdateProductDTO;
use App\Modules\Product\DTOs\UpdateProduct\UpdateProductPositionDTO;
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
      $product = $this->productRepository->getProduct($product->id);
      return $product;
    });
  }

  public function updateProductPosition(int $id, UpdateProductPositionDTO $data): void {
    DB::transaction(function () use ($id, $data) {
      $product = $this->productRepository->getProduct($id);

      if (!$product) {
        throw new ProductNotFoundException();
      }

      $allProducts = $this->productRepository->getAllProducts();
      $allProductsCount = $allProducts->count();
      $movingUp = $data->newPosition < $data->oldPosition;

      if ($data->newPosition === 1) {
        $productAfter = $this->productRepository->getNthProduct(0) ?? null;
        $product->position = ($productAfter ? $productAfter->position : 0) / 2;
      } else if ($data->newPosition === $allProductsCount) {
        $productBefore = $this->productRepository->getNthProduct($allProductsCount - 1) ?? null;
        $product->position = ($productBefore ? $productBefore->position : 0) + 1;
      } else if ($movingUp) {
        $productBefore = $this->productRepository->getNthProduct($data->newPosition - 2) ?? null;
        $productAfter = $this->productRepository->getNthProduct($data->newPosition - 1) ?? null;
        $productBeforePosition = $productBefore ? $productBefore->position : 0;
        $productAfterPosition = $productAfter ? $productAfter->position : 0;
        $product->position = ($productBeforePosition + $productAfterPosition) / 2;
      } else {
        $productBefore = $this->productRepository->getNthProduct($data->newPosition - 1) ?? null;
        $productAfter = $this->productRepository->getNthProduct($data->newPosition) ?? null;
        $productBeforePosition = $productBefore ? $productBefore->position : 0;
        $productAfterPosition = $productAfter ? $productAfter->position : 0;
        $product->position = ($productBeforePosition + $productAfterPosition) / 2;
      }

      $this->productRepository->saveProduct($product);
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