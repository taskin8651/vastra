<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Audience;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::query()
            ->active()
            ->with('media')
            ->withCount([
                'products as active_products_count' => function ($query) {
                    $query->active();
                }
            ])
            ->when($request->q, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%');
            })
            ->orderByDesc('active_products_count')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $audiences = Audience::query()
            ->active()
            ->with('media')
            ->orderBy('sort_order')
            ->get();

        return view('frontend.brands.index', compact('brands', 'audiences'));
    }

    public function show(Request $request, Brand $brand)
    {
        abort_if(! $brand->is_active, 404);

        $audiences = Audience::query()
            ->active()
            ->with('media')
            ->whereHas('categories.products', function ($query) use ($brand) {
                $query->where('brand_id', $brand->id)
                    ->active();
            })
            ->orderBy('sort_order')
            ->get();

        $products = Product::query()
            ->active()
            ->where('brand_id', $brand->id)
            ->with(['media', 'brand.media', 'category.media', 'category.audience.media'])
            ->when($request->q, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%')
                        ->orWhere('sku', 'like', '%' . $request->q . '%')
                        ->orWhere('colour', 'like', '%' . $request->q . '%')
                        ->orWhere('fashion_type', 'like', '%' . $request->q . '%');
                });
            })
            ->when($request->audience, function ($query) use ($request) {
                $query->whereHas('category.audience', function ($audienceQuery) use ($request) {
                    $audienceQuery->where('slug', $request->audience);
                });
            })
            ->when($request->sort === 'price_low', function ($query) {
                $query->orderBy('price');
            })
            ->when($request->sort === 'price_high', function ($query) {
                $query->orderByDesc('price');
            })
            ->when($request->sort === 'latest', function ($query) {
                $query->latest();
            })
            ->when(! in_array($request->sort, ['price_low', 'price_high', 'latest']), function ($query) {
                $query->orderByDesc('is_featured')->latest();
            })
            ->paginate(12);

        $wishlistProductIds = [];

        if (auth()->check()) {
            $wishlistProductIds = Wishlist::query()
                ->where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('frontend.brands.show', compact(
            'brand',
            'products',
            'audiences',
            'wishlistProductIds'
        ));
    }
}
