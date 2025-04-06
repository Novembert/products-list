<?php

namespace Tests\Unit\ProductServiceTests;

use Tests\Unit\UnitTestCase;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\TagService;
use App\Modules\Product\Models\Product;
use App\Modules\Product\DTOs\CreateProduct\CreateProductDTO;
use App\Modules\Product\Models\Tag;
use App\Modules\Product\DTOs\CreateProduct\TagDTO;

class CreateProductTest extends UnitTestCase
{
  public function testCreateProductCallsRepositorySaveProductAndReturnsCreatedProduct(): void {
    $productRepositoryMock = $this->createMock(ProductRepository::class);
    $tagServiceMock = $this->createMock(TagService::class);

    $productService = new ProductService($productRepositoryMock, $tagServiceMock);
    $result = $productService->createProduct(new CreateProductDTO(
        name: 'Test Product',
        description: 'Test Description',
        price: 100.00,
        vatRate: 0.11,
        tag: null,
    ));

    $this->assertInstanceOf(Product::class, $result);
    $this->assertEquals('Test Product', $result->name, 'The product name does not match the expected value.');
    $this->assertEquals('Test Description', $result->description, 'The product description does not match the expected value.');
    $this->assertEquals(100.00, $result->price, 'The product price does not match the expected value.');
    $this->assertEquals(0.11, $result->vat_rate, 'The product VAT rate does not match the expected value.');
  }

  public function testCreateProductCallsTagServiceIfTagDataIsProvided(): void {
    $productRepositoryMock = $this->createMock(ProductRepository::class);
    $tagServiceMock = $this->createMock(TagService::class);
    $dummyTag = Tag::factory()->create([
        'name' => 'Test Tag',
        'color' => 'red',
    ]);

    $tagServiceMock->expects($this->once())
        ->method('findOrCreateTag')
        ->willReturn($dummyTag);

    $productService = new ProductService($productRepositoryMock, $tagServiceMock);
    $result = $productService->createProduct(new CreateProductDTO(
        name: 'Test Product',
        description: 'Test Description',
        price: 100.00,
        vatRate: 0.11,
        tag: new TagDTO(
            name: 'Test Tag',
            color: 'red',
        ),
    ));

    $this->assertInstanceOf(Product::class, $result);
    $this->assertEquals($dummyTag->id, $result->tag_id, 'The product tag ID does not match the expected value.');
  }

  public function testDoesNotSaveProductIfTagServiceThrowsException(): void {
    $productRepositoryMock = $this->createMock(ProductRepository::class);
    $tagServiceMock = $this->createMock(TagService::class);

    $tagServiceMock->expects($this->once())
        ->method('findOrCreateTag')
        ->willThrowException(new \Exception('Tag creation failed'));

    $productService = new ProductService($productRepositoryMock, $tagServiceMock);

    $this->expectException(\Exception::class);
    $productService->createProduct(new CreateProductDTO(
        name: 'Test Product',
        description: 'Test Description',
        price: 100.00,
        vatRate: 0.11,
        tag: new TagDTO(
            name: 'Test Tag',
            color: 'red',
        ),
    ));

    $this->assertDatabaseMissing('products', [
        'name' => 'Test Product',
        'description' => 'Test Description',
        'price' => 100.00,
        'vat_rate' => 0.11,
    ]);
  }
}