<?php 

namespace Tests\Unit\ProductRepositoryTests;

use App\Modules\Product\Repositories\ProductRepository;
use Tests\Unit\UnitTestCase;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Tag;

class GetNthProductTest extends UnitTestCase
{
  public function testGetNthProductReturnsNthProductFromProductsListOrderedByPosition(): void
  {
    Product::factory()->create([
      'id' => 1,
      'name' => 'Test Product',
      'description' => 'Test Description',
      'price' => 100.00,
    ]);
    $dummyProduct2 = Product::factory()->create([
      'id' => 2,
      'name' => 'Test Product 2',
      'description' => 'Test Description 2',
      'price' => 200.00,
    ]);

    $repository = app()->make(ProductRepository::class);
    $result = $repository->getNthProduct(1);

    $this->assertNotNull($result, 'The product should not be null.');
    $this->assertEquals($dummyProduct2->id, $result->id, 'The product ID does not match the expected value.');
  }
}