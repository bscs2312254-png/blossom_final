<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Roses',
                'description' => 'Beautiful roses in various colors',
                'image' => 'rose.jpg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Lilies',
                'description' => 'Elegant lilies with delicate fragrance',
                'image' => 'lily.jpg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Tulips',
                'description' => 'Vibrant tulips in spring colors',
                'image' => 'tulip.jpg',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Orchids',
                'description' => 'Exotic orchids for special occasions',
                'image' => 'orchid.jpg',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Sunflowers',
                'description' => 'Bright sunflowers that bring happiness',
                'image' => 'sunflower.jpg',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Mixed Bouquets',
                'description' => 'Beautiful mixed flower arrangements',
                'image' => 'peony.jpg',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}