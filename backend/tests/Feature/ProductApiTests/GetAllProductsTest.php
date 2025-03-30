<?php

namespace Tests\Feature;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Tag;
use Tests\Feature\FeatureTestCase;

class GetAllProductsTest extends FeatureTestCase
{
    public function testReturnsAllProductsWithTags(): void
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

    public function testReturnsProductsWithoutTagsIfProductsDontHaveThem(): void
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
}
