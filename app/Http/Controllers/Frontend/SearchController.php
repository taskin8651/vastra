<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        $products = Product::query()
            ->active()
            ->with(['media', 'brand.media', 'category.media', 'category.audience.media'])
            ->when($query !== '', function ($productQuery) use ($query) {
                $productQuery->where(function ($searchQuery) use ($query) {
                    $searchQuery
                        ->where('name', 'like', '%' . $query . '%')
                        ->orWhere('sku', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%')
                        ->orWhere('colour', 'like', '%' . $query . '%')
                        ->orWhere('fashion_type', 'like', '%' . $query . '%')
                        ->orWhere('fabric_details', 'like', '%' . $query . '%')
                        ->orWhereHas('brand', function ($brandQuery) use ($query) {
                            $brandQuery->where('name', 'like', '%' . $query . '%');
                        })
                        ->orWhereHas('category', function ($categoryQuery) use ($query) {
                            $categoryQuery->where('name', 'like', '%' . $query . '%');
                        })
                        ->orWhereHas('category.audience', function ($audienceQuery) use ($query) {
                            $audienceQuery->where('name', 'like', '%' . $query . '%');
                        });
                });
            })
            ->when($query === '', function ($productQuery) {
                $productQuery->orderByDesc('is_featured');
            })
            ->latest()
            ->paginate(16)
            ->withQueryString();

        $wishlistProductIds = [];

        if (auth()->check()) {
            $wishlistProductIds = Wishlist::query()
                ->where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('frontend.search.index', compact('products', 'query', 'wishlistProductIds'));
    }
}
