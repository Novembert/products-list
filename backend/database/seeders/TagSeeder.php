<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Popular', 'color' => 'red'],
            ['name' => 'New', 'color' => 'blue'],
            ['name' => 'On sale', 'color' => 'green'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
