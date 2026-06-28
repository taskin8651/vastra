<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'upcoming');

        $trials = Order::query()
            ->where('user_id', auth()->id())
            ->where('delivery_method', 'home_trial')
            ->with([
                'items.product.brand',
                'items.product.category.audience',
            ])
            ->when($tab === 'upcoming', function ($query) {
                $query->whereNotIn('order_status', [
                    'delivered',
                    'cancelled',
                ]);
            })
            ->when($tab === 'past', function ($query) {
                $query->whereIn('order_status', [
                    'delivered',
                    'cancelled',
                ]);
            })
            ->latest()
            ->paginate(10);

        return view('frontend.trials.index', compact('trials', 'tab'));
    }
}