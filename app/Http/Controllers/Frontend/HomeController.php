<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Audience;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Category;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index()
    {
        $audiences = Audience::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $trendingProducts = Product::query()
            ->active()
            ->where('is_featured', true)
            ->with(['media', 'brand.media', 'category.media', 'category.audience.media'])
            ->latest()
            ->take(4)
            ->get();

        $wishlistProductIds = [];

        if (auth()->check()) {
            $wishlistProductIds = Wishlist::query()
                ->where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }

        $audiences = Audience::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $shopCategories = Category::query()
            ->active()
            ->with(['media', 'audience.media'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(6)
            ->get();

            $topPickProducts = Product::query()
            ->active()
            ->with(['media', 'brand.media', 'category.media', 'category.audience.media'])
            ->latest()
            ->take(8)
            ->get();

            $brands = Brand::query()
            ->active()
            ->with('media')
            ->withCount([
                'products as active_products_count' => function ($query) {
                    $query->active();
                }
            ])
            ->orderByDesc('active_products_count')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(12)
            ->get();


        return view('frontend.index', compact(
            'audiences',
            'trendingProducts',
            'wishlistProductIds',
            'audiences',
            'shopCategories',
            'topPickProducts',
            'brands',

        ));
    }
}
