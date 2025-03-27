<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Tag;

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
