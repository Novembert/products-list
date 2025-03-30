<?php

namespace Tests\Feature;

use App\Modules\Product\Models\Tag;
use App\Modules\Product\Models\Product;
use Tests\Feature\FeatureTestCase;

class DeleteProductTest extends FeatureTestCase
{
  public function testDeletesProduct(): void
  {
      $product = Product::factory()->create();

      $response = $this->deleteJson("/api/products/{$product->id}");

      $response->assertStatus(204);

      $this->assertDatabaseMissing('products', [
          'id' => $product->id,
      ]);
  }

  public function testDeletesTagIfItIsNoLongerUsedAfterProductGetsDeleted(): void
  {
      $tag = Tag::factory()->create();
      $product = Product::factory()->create(['tag_id' => $tag->id]);

      $response = $this->deleteJson("/api/products/{$product->id}");

      $response->assertStatus(204);

      $this->assertDatabaseMissing('tags', [
          'id' => $tag->id,
      ]);
  }

  public function testReturnsError404IfProductDoesNotExist(): void
  {
      $response = $this->deleteJson('/api/products/9999');

      $response->assertStatus(404)
          ->assertJson([
            'error' => [
              'message' => 'Product not found',
            ]
          ]);
  } 
}