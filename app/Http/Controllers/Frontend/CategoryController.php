<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Audience;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $audiences = Audience::query()
            ->active()
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categories = Category::query()
            ->active()
            ->with(['media', 'audience.media'])
            ->withCount([
                'products as active_products_count' => function ($query) {
                    $query->active();
                }
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('frontend.categories.index', compact(
            'audiences',
            'categories'
        ));
    }
}
