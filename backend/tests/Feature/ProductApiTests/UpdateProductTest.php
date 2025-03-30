<?php

namespace Tests\Feature\ProductApiTests;

use Tests\Feature\FeatureTestCase;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Tag;

class UpdateProductTest extends FeatureTestCase
{
    public function testUpdatesTagAndReturnsItsNewData(): void
    {
        $tag = Tag::factory()->create();
        $product = Product::factory()->create(['tag_id' => $tag->id]);

        $requestData = [
            'name' => 'Updated Product',
            'description' => 'This is an updated product.',
            'vatRate' => 0.22,
            'price' => 200.50,
            'tag' => [
                'name' => 'Updated Tag',
                'color' => 'blue',
            ],
        ];

        $response = $this->putJson("/api/products/{$product->id}", $requestData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'vatRate',
                    'price',
                    'tag' => [
                        'id',
                        'name',
                    ],
                    'createdAt',
                    'updatedAt',
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'description' => 'This is an updated product.',
            'vat_rate' => '0.22',
            'price' => '200.50',
            'tag_id' => $response['data']['tag']['id'],
        ]);
    }

    public function testDeletesTagIfItIsNoLongerUsedAfterProductGetsUpdated(): void
    {
        $tag = Tag::factory()->create();
        $product = Product::factory()->create(['tag_id' => $tag->id]);
        $product2 = Product::factory()->create(['tag_id' => $tag->id]);

        $requestData = [
            'name' => 'Updated Product',
            'description' => 'This is an updated product.',
            'vatRate' => 0.22,
            'price' => 200.50,
            'tag' => [
                'name' => 'Updated Tag',
                'color' => 'blue',
            ],
        ];

        $response = $this->putJson("/api/products/{$product->id}", $requestData);
        $response->assertStatus(200);

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
        ]);

        $response = $this->putJson("/api/products/{$product2->id}", $requestData);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }

    public function testDoesNotDeleteTagIfItIsStillUsedAfterProductGetsUpdated(): void
    {
        $tag = Tag::factory()->create();
        $product = Product::factory()->create(['tag_id' => $tag->id]);

        $requestData = [
            'name' => 'Updated Product',
            'description' => 'This is an updated product.',
            'vatRate' => 0.22,
            'price' => 200.50,
            'tag' => [
                'name' => $tag->name,
                'color' => $tag->color,
            ],
        ];

        $response = $this->putJson("/api/products/{$product->id}", $requestData);
        $response->assertStatus(200);

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
        ]);
        $this->assertDatabaseCount('tags', 1);
    }

    public function testReturnsError404IfProductDoesNotExist(): void
    {
        $requestData = [
            'name' => 'Updated Product',
            'vatRate' => 0.22,
            'price' => 200.50
        ];

      $response = $this->putJson("/api/products/99999", $requestData);
  
      $response->assertStatus(404)
        ->assertJson([
            'error' => [
            'message' => 'Product not found',
            ]
        ]);
    } 
}
