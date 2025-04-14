<?php 

namespace Tests\Unit\ProductRepositoryTests;

use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Models\Product;
use Tests\Unit\UnitTestCase;

class SaveProductTest extends UnitTestCase
{
  public function testSaveProductSavesProductInDatabase(): void
  {
    $dummyProduct = Product::factory()->make([
      'name' => 'Test Product',
      'description' => 'Test Description',
      'price' => 100.00,
    ]);

    $repository = app()->make(ProductRepository::class);
    $repository->saveProduct($dummyProduct);

    $this->assertDatabaseHas('products', [
      'id' => $dummyProduct->id,
      'name' => 'Test Product',
      'description' => 'Test Description',
      'price' => 100.00,
    ]);
  }
}