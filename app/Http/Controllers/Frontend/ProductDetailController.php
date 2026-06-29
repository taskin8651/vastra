<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;

class ProductDetailController extends Controller
{
    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $product->load([
            'brand',
            'brand.media',
            'media',
            'category.audience',
            'category.media',
            'category.audience.media',
        ]);

        $relatedProducts = Product::query()
            ->active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with(['media', 'brand.media', 'category.media'])
            ->latest()
            ->take(6)
            ->get();

        $isWishlisted = auth()->check()
            ? Wishlist::query()
                ->where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists()
            : false;

        return view('frontend.products.show', compact('product', 'relatedProducts', 'isWishlisted'));
    }
}
