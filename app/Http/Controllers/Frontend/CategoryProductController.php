<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Audience;
use App\Models\Category;
use App\Models\Product;

class CategoryProductController extends Controller
{
    public function show(Audience $audience, Category $category)
    {
        abort_if(! $audience->is_active, 404);
        abort_if(! $category->is_active, 404);

        // Security check: category same audience ki honi chahiye
        abort_if($category->audience_id !== $audience->id, 404);

        $products = Product::query()
            ->active()
            ->where('category_id', $category->id)
            ->with(['category', 'brand'])
            ->latest()
            ->paginate(16);

        return view('frontend.audience.products', compact(
            'audience',
            'category',
            'products'
        ));
    }
}