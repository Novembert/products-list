<?php

namespace Database\factories\Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Product\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1000, 9999),
            'tag_id' => null,
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'vat_rate' => $this->faker->randomFloat(2, 0, 1),
        ];
    }
}
