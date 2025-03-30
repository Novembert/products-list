<?php

namespace Database\factories\Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Product\Models\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'color' => $this->faker->randomElement(['red', 'blue', 'green', 'yellow', 'purple', 'orange', 'black'])
        ];
    }
}
