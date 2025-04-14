<?php

namespace Tests\Unit\ProductServiceTests;

use Tests\Unit\UnitTestCase;
use App\Modules\Product\Exceptions\ProductNotFoundException;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\TagService;
use App\Modules\Product\Models\Product;

class GetProductTest extends UnitTestCase
{
  public function testGetProductCallsRepositoryGetProductAndReturnsItsResultIfSuchProductExists(): void {
    $productRepositoryMock = $this->createMock(ProductRepository::class);
    $tagServiceMock = $this->createMock(TagService::class);
    $product = Product::factory()->create();
    $productRepositoryMock->expects($this->once())
        ->method('getProduct')
        ->with(1)
        ->willReturn($product);

    $productService = new ProductService($productRepositoryMock, $tagServiceMock);
    $result = $productService->getProduct(1);

    $this->assertSame($product, $result);
  }

  public function testGetProductThrowsExceptionIfProductDoesNotExist(): void {
    $productRepositoryMock = $this->createMock(ProductRepository::class);
    $tagServiceMock = $this->createMock(TagService::class);
    $productRepositoryMock->expects($this->once())
        ->method('getProduct')
        ->with(1)
        ->willReturn(null);

    $productService = new ProductService($productRepositoryMock, $tagServiceMock);

    $this->expectException(ProductNotFoundException::class);
    $productService->getProduct(1);
  }
}