<?php

namespace Tests\Unit\ProductRepositoryTests;

use App\Modules\Product\Repositories\ProductRepository;
use Tests\Unit\UnitTestCase;
use App\Modules\Product\Models\Product;

class DeleteProductTest extends UnitTestCase
{
    public function testDeleteProductDeletesProductFromDatabase(): void
    {
      $dummyProduct = Product::factory()->create([
        'name' => 'Test Product',
        'description' => 'Test Description',
        'price' => 100.00,
      ]);

      $repository = app()->make(ProductRepository::class);
      $repository->deleteProduct($dummyProduct);

      $this->assertDatabaseMissing('products', [
        'id' => $dummyProduct->id,
        'name' => 'Test Product',
        'description' => 'Test Description',
        'price' => 100.00,
      ]);
    }
}
