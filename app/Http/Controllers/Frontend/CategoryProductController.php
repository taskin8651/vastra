<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Audience;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    public function show(Request $request, Audience $audience, Category $category)
    {
        abort_if(! $audience->is_active, 404);
        abort_if(! $category->is_active, 404);

        // Security check: category same audience ki honi chahiye
        abort_if($category->audience_id !== $audience->id, 404);

        $products = Product::query()
            ->active()
            ->where('category_id', $category->id)
            ->with(['category', 'brand'])
            ->when($request->q, function ($query) use ($request) {
                $query->where(function ($searchQuery) use ($request) {
                    $searchQuery
                        ->where('name', 'like', '%' . $request->q . '%')
                        ->orWhere('sku', 'like', '%' . $request->q . '%')
                        ->orWhere('colour', 'like', '%' . $request->q . '%')
                        ->orWhere('fashion_type', 'like', '%' . $request->q . '%')
                        ->orWhereHas('brand', function ($brandQuery) use ($request) {
                            $brandQuery->where('name', 'like', '%' . $request->q . '%');
                        });
                });
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

        return view('frontend.audience.products', compact(
            'audience',
            'category',
            'products',
            'wishlistProductIds'
        ));
    }
}
