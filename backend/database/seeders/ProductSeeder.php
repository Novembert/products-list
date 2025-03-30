<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Tag;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::all();

        Product::factory()
            ->count(10)
            ->create()
            ->each(function ($product) use ($tags) {
                if ($tags->isNotEmpty()) {
                    $tag = $tags->random(); 
                    $product->tag_id = $tag->id; 
                    $product->save();
                }
            });
    }
}
