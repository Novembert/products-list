<?php

namespace Tests\Feature;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Tag;
use Tests\Feature\FeatureTestCase;

class GetProductTest extends FeatureTestCase
{
  public function testReturnsSpecificProductDetails(): void
  {
      $tag = Tag::factory()->create();
      $product = Product::factory()->create(['tag_id' => $tag->id]);

      $response = $this->getJson("/api/products/{$product->id}");

      $response->assertStatus(200)
          ->assertJsonStructure([
              'data' => [
                  'id',
                  'name',
                  'description',
                  'vatRate',
                  'price',
                  'tag',
                  'createdAt',
                  'updatedAt',
              ],
          ]);
  }

  public function testReturnsError404IfProductDoesNotExist(): void
  {
      $response = $this->getJson('/api/products/9999');

      $response->assertStatus(404)
        ->assertJson([
          'error' => [
            'message' => 'Product not found',
          ]
        ]);
  } 
}