<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::query()
            ->where('user_id', auth()->id())
            ->with([
                'product.brand',
                'product.category.audience',
            ])
            ->latest()
            ->get();

        return view('frontend.wishlist.index', compact('wishlists'));
    }

    public function store(Product $product)
    {
        abort_if(! $product->is_active, 404);

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Product added to wishlist.');
    }

    public function destroy(Product $product)
    {
        Wishlist::query()
            ->where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Product removed from wishlist.');
    }

    public function toggle(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $wishlist = Wishlist::query()
            ->where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Product removed from wishlist.',
                    'wishlisted' => false,
                ]);
            }

            return back()->with('success', 'Product removed from wishlist.');
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Product added to wishlist.',
                'wishlisted' => true,
            ]);
        }

        return back()->with('success', 'Product added to wishlist.');
    }
}
