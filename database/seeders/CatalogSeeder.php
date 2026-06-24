<?php

namespace Database\Seeders;

use App\Models\Audience;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $men = Audience::updateOrCreate(['slug' => 'men'], ['name' => 'Men', 'is_active' => true, 'sort_order' => 1]);
        $women = Audience::updateOrCreate(['slug' => 'women'], ['name' => 'Women', 'is_active' => true, 'sort_order' => 2]);
        $kids = Audience::updateOrCreate(['slug' => 'kids'], ['name' => 'Kids', 'is_active' => true, 'sort_order' => 3]);
        $shirt = Category::updateOrCreate(['slug' => 'men-shirts'], ['audience_id' => $men->id, 'name' => 'Shirts', 'image_path' => 'assets/images/men-tile-1.png', 'is_active' => true, 'sort_order' => 1]);
        Category::updateOrCreate(['slug' => 'women-dresses'], ['audience_id' => $women->id, 'name' => 'Dresses', 'image_path' => 'assets/images/women-tile-8.png', 'is_active' => true, 'sort_order' => 1]);
        Category::updateOrCreate(['slug' => 'kids-tshirts'], ['audience_id' => $kids->id, 'name' => 'T-Shirts', 'image_path' => 'assets/images/kids-tile-3.png', 'is_active' => true, 'sort_order' => 1]);
        $zara = Brand::updateOrCreate(['slug' => 'zara'], ['name' => 'ZARA', 'is_active' => true, 'sort_order' => 1]);
        Brand::updateOrCreate(['slug' => 'hm'], ['name' => 'H&M', 'is_active' => true, 'sort_order' => 2]);
        Product::updateOrCreate(['sku' => 'ZARA-LINEN-001'], ['category_id' => $shirt->id, 'brand_id' => $zara->id, 'name' => 'Linen Blend Shirt', 'slug' => 'linen-blend-shirt', 'description' => 'Lightweight linen blend shirt for everyday casual wear.', 'price' => 1350, 'compare_at_price' => 1800, 'stock_quantity' => 20, 'image_path' => 'assets/images/shirts-product.png', 'is_active' => true, 'is_featured' => true]);
    }
}
