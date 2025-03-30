<?php

namespace Tests\Feature;

use App\Modules\Product\Models\Product;

class UpdateProductPositionTest extends FeatureTestCase
{
    public function testUpdatesProductPosition(): void
    {
        $product = Product::factory()->create(['position' => 1]);
        Product::factory()->create(['position' => 2]);
        Product::factory()->create(['position' => 3]);

        $response = $this->patchJson("/api/products/{$product->id}/position", [
            'oldPosition' => 1,
            'newPosition' => 2,
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'position' => 2.5,
        ]);
    }

    public function testReturnsProductsWithUpdatedPositionsAfterUpdate(): void
    {
        $product1 = Product::factory()->create(['position' => 1]);
        $product2 = Product::factory()->create(['position' => 2]);
        $product3 = Product::factory()->create(['position' => 3]);

        $this->patchJson("/api/products/{$product1->id}/position", [
            'oldPosition' => 1,
            'newPosition' => 2,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'vatRate',
                        'price',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
            ]);

        $this->assertEquals($product2->id, $response['data'][0]['id']);
        $this->assertEquals($product1->id, $response['data'][1]['id']);
        $this->assertEquals($product3->id, $response['data'][2]['id']);
    }

    public function testReturnsError404IfProductDoesNotExist(): void
    {
        $response = $this->patchJson("/api/products/99999/position", [
          'oldPosition' => 1,
          'newPosition' => 2,
        ]);

        $response->assertStatus(404)
          ->assertJson([
            'error' => [
              'message' => 'Product not found',
            ]
          ]);
    }
}
