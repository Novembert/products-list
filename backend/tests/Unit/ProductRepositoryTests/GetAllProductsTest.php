<?php 

namespace Tests\Unit\ProductRepositoryTests;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Tag;
use App\Modules\Product\Repositories\ProductRepository;

use Tests\Unit\UnitTestCase;

class GetAllProductsTest extends UnitTestCase {
  public function testGetAllProductsOrderedByPosition(): void
  {
    $dummyProduct = Product::factory()->create([
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
    $dummyProduct->position = 2;
    $dummyProduct2->position = 1;
    $dummyProduct->save();
    $dummyProduct2->save();

    $repository = app()->make(ProductRepository::class);
    $result = $repository->getAllProducts();

    $this->assertCount(2, $result, 'The number of products retrieved is incorrect.');
    $this->assertEquals($dummyProduct2->id, $result[0]->id, 'The first product is not ordered correctly.');
    $this->assertEquals($dummyProduct->id, $result[1]->id, 'The second product is not ordered correctly.');
  }

  public function testGetAllProductsOrderedByPositionReturnsTagsIfPresent(): void
  {
    // Arrange: Create dummy products with specific positions and tags
    $dummyTag = Tag::factory()->create([
      'name' => 'Test Tag',
      'color' => 'red',
    ]);
    Product::factory()->create([
      'id' => 1,
      'name' => 'Test Product',
      'description' => 'Test Description',
      'price' => 100.00,
      'tag_id' => $dummyTag->id,
    ]);

    // Act: Retrieve all products using the repository
    $repository = app()->make(ProductRepository::class);
    $result = $repository->getAllProducts();

    $resultTag = $result[0]->tag;
    $this->assertNotNull($resultTag, 'The tag should not be null.');
    $this->assertEquals($dummyTag->id, $resultTag->id, 'The tag ID does not match the expected value.');
    $this->assertEquals($dummyTag->name, $resultTag->name, 'The tag name does not match the expected value.');
    $this->assertEquals($dummyTag->color, $resultTag->color, 'The tag color does not match the expected value.');
  }
}