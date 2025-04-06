<?php

namespace Tests\Unit\ProductRepositoryTests;

use Tests\Unit\UnitTestCase;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Tag;
use App\Modules\Product\Repositories\ProductRepository;

class GetProductTest extends UnitTestCase {
  public function testGetProductReturnsSpecifiedProductWithTag(): void
  {
    $dummyTag = Tag::factory()->create([
      'name' => 'Test Tag',
      'color' => 'red',
    ]);
    $dummyProduct = Product::factory()->create([
      'id' => 1,
      'name' => 'Test Product',
      'description' => 'Test Description',
      'price' => 100.00,
      'tag_id' => $dummyTag->id,
    ]);

    $repository = app()->make(ProductRepository::class);
    $result = $repository->getProduct($dummyProduct->id);

    $this->assertNotNull($result, 'The product should not be null.');
    $this->assertEquals($dummyProduct->id, $result->id, 'The product ID does not match the expected value.');
    $this->assertEquals($dummyTag->id, $result->tag->id, 'The tag ID does not match the expected value.');
  }
}