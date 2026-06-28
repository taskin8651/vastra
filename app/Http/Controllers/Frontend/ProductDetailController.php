<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductDetailController extends Controller
{
    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $product->load([
            'brand',
            'category.audience',
        ]);

        $relatedProducts = Product::query()
            ->active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with(['brand', 'category'])
            ->latest()
            ->take(6)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}