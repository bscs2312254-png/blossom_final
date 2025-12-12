<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing products
        Product::truncate();
        
        // Get all categories
        $categories = Category::all();
        
        // Create products with proper category linking
        $products = [
            [
                'name' => 'Red Roses Bouquet',
                'description' => 'Beautiful fresh red roses bouquet perfect for special occasions.',
                'price' => 39.99,
                'image' => 'rose.jpg',
                'category' => 'roses',
                'featured' => true,
                'stock' => 50,
            ],
            [
                'name' => 'Sunflower Bundle',
                'description' => 'Bright and cheerful sunflowers that bring sunshine to any room.',
                'price' => 29.99,
                'image' => 'sunflower.jpg',
                'category' => 'sunflowers',
                'featured' => true,
                'stock' => 30,
            ],
            [
                'name' => 'Elegant Lily Arrangement',
                'description' => 'Graceful white lilies with a delicate fragrance.',
                'price' => 34.99,
                'image' => 'lily.jpg',
                'category' => 'lilies',
                'featured' => true,
                'stock' => 25,
            ],
            [
                'name' => 'Purple Tulips',
                'description' => 'Vibrant purple tulips that symbolize royalty and elegance.',
                'price' => 27.99,
                'image' => 'tulip.jpg',
                'category' => 'tulips',
                'featured' => true,
                'stock' => 40,
            ],
            [
                'name' => 'White Orchids',
                'description' => 'Exotic white orchids for a sophisticated touch.',
                'price' => 45.99,
                'image' => 'orchid.jpg',
                'category' => 'orchids',
                'featured' => false,
                'stock' => 15,
            ],
            [
                'name' => 'Pink Peonies',
                'description' => 'Soft pink peonies symbolizing romance and prosperity.',
                'price' => 32.99,
                'image' => 'peony.jpg',
                'category' => 'mixed-bouquets',
                'featured' => false,
                'stock' => 35,
            ],
            [
                'name' => 'Wild Daisy Mix',
                'description' => 'Cheerful mix of colorful daisies for a natural look.',
                'price' => 24.99,
                'image' => 'daisy.jpg',
                'category' => 'mixed-bouquets',
                'featured' => false,
                'stock' => 60,
            ],
            [
                'name' => 'Lavender Field',
                'description' => 'Soothing lavender bouquet with calming fragrance.',
                'price' => 28.99,
                'image' => 'lavender.jpg',
                'category' => 'mixed-bouquets',
                'featured' => false,
                'stock' => 20,
            ],
        ];

        foreach ($products as $productData) {
            // Find the category by slug
            $category = $categories->where('slug', $productData['category'])->first();
            
            // Create product with category_id
            Product::create([
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'image' => $productData['image'],
                'category_id' => $category ? $category->id : null,
                'category' => $category ? $category->name : $productData['category'],
                'featured' => $productData['featured'],
                'stock' => $productData['stock'],
            ]);
        }
    }
}