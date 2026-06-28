<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Audience;

class AudienceController extends Controller
{
    public function show(Audience $audience)
    {
        abort_if(! $audience->is_active, 404);

        $audience->load([
            'categories' => function ($query) {
                $query->active()
                    ->withCount([
                        'products as active_products_count' => function ($productQuery) {
                            $productQuery->active();
                        }
                    ])
                    ->orderBy('sort_order')
                    ->orderBy('name');
            }
        ]);

        return view('frontend.audience.show', compact('audience'));
    }
}