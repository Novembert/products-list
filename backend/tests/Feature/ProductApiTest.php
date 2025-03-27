<?php

namespace Tests\Feature;

use App\Http\Resources\ProductCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Tag;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_all_products_with_tags(): void
    {
        $tag = Tag::factory()->create();
        Product::factory()
            ->count(5)
            ->create(['tag_id' => $tag->id]);
    
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
                        'tag',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
            ]);
    }

    public function test_returns_products_without_tags_if_products_dont_have_them(): void
    {
        Product::factory()
            ->count(5)
            ->create();
    
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
    }

    public function test_returns_specific_product_details(): void
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

    public function test_creates_and_returns_new_product(): void
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
            'vat_rate' => 0.11,
            'price' => 100.50,
            'tag_id' => $response['data']['tag']['id'],
        ]);
    }

    public function test_reuses_existing_tag_when_creating_product_with_equal_tag(): void
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
            'vat_rate' => 0.11,
            'price' => 100.50,
            'tag_id' => $tag->id,
        ]);
    }

    public function test_does_not_create_tag_if_no_tag_data_is_passed(): void
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

    public function test_deletes_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_deletes_tag_if_it_is_no_longer_used_after_product_gets_deleted(): void
    {
        $tag = Tag::factory()->create();
        $product = Product::factory()->create(['tag_id' => $tag->id]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }

    public function test_updates_tag_and_returns_its_new_data(): void
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
            'vat_rate' => 0.22,
            'price' => 200.50,
            'tag_id' => $response['data']['tag']['id'],
        ]);
    }

    public function test_deletes_tag_if_it_is_no_longer_used_after_product_gets_updated(): void
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

    public function test_does_not_delete_tag_if_it_is_still_used_after_product_gets_updated(): void
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
}
