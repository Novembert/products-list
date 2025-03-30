<?php

namespace Tests\Feature\ProductApiTests;

use App\Modules\Product\Models\Tag;
use Tests\Feature\FeatureTestCase;

class CreateProductTest extends FeatureTestCase
{
    public function testCreatesAndReturnsNewProduct(): void
    {
        // Arrange: Create JSON request data
        $requestData = [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'vatRate' => 0.11,
            'price' => 100.50,
            'tag' => [
                'name' => 'Test Tag',
                'color' => 'red',
            ],
        ];

        // Act: Send POST request to /api/products
        $response = $this->postJson('/api/products', $requestData);

        // Assert: Response status is 201 (Created)
        $response->assertStatus(201)
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

        // Assert: Product exists in the database
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'vat_rate' => '0.11',
            'price' => '100.50',
            'tag_id' => $response['data']['tag']['id'],
        ]);
    }

    public function testReusesExistingTagWhenCreatingProductWithEqualTag(): void
    {
        $tag = Tag::factory()->create();

        $requestData = [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'vatRate' => 0.11,
            'price' => 100.50,
            'tag' => [
                'name' => $tag->name,
                'color' => $tag->color,
            ],
        ];

        $response = $this->postJson('/api/products', $requestData);

        $response->assertStatus(201)
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
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'vat_rate' => '0.11',
            'price' => '100.50',
            'tag_id' => $tag->id,
        ]);
    }

    public function testDoesNotCreateTagIfNoTagDataIsPassed(): void
    {
        $requestData = [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'vatRate' => 0.11,
            'price' => 100.50,
        ];

        $response = $this->postJson('/api/products', $requestData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'vatRate',
                    'price',
                    'createdAt',
                    'updatedAt',
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'vat_rate' => 0.11,
            'price' => 100.50,
            'tag_id' => null,
        ]);
        $this->assertDatabaseCount('tags', 0);
    }
}
