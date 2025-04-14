<?php

namespace Tests\Unit\ProductServiceTests;

use Tests\Unit\UnitTestCase;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\TagService;
use App\Modules\Product\Models\Product;

class GetAllProductsTest extends UnitTestCase
{
  public function testGetAllProductsCallsRepositoryGetAllProductsMethodAndReturnsItsResult(): void {
    $productRepositoryMock = $this->createMock(ProductRepository::class);
    $tagServiceMock = $this->createMock(TagService::class);
    $dummyProducts = Product::factory()->count(2)->create();
    $productRepositoryMock->expects($this->once())
        ->method('getAllProducts')
        ->willReturn($dummyProducts);

    $productService = new ProductService($productRepositoryMock, $tagServiceMock);
    $products = $productService->getAllProducts();

    $this->assertCount(2, $products);
  }
}