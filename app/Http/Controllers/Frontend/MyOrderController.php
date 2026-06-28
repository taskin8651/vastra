<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class MyOrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->with([
                'items.product.category.audience',
                'items.product.brand',
            ])
            ->when($status === 'processing', function ($query) {
                $query->whereIn('order_status', [
                    'placed',
                    'confirmed',
                    'packed',
                ]);
            })
            ->when($status === 'shipped', function ($query) {
                $query->where('order_status', 'out_for_delivery');
            })
            ->when($status === 'delivered', function ($query) {
                $query->where('order_status', 'delivered');
            })
            ->when($status === 'cancelled', function ($query) {
                $query->where('order_status', 'cancelled');
            })
            ->latest()
            ->paginate(10);

        return view('frontend.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load([
            'items.product.category.audience',
            'items.product.brand',
        ]);

        return view('frontend.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        if (in_array($order->order_status, ['delivered', 'cancelled'])) {
            return back()->with('message', 'This order cannot be cancelled.');
        }

        $order->update([
            'order_status' => 'cancelled',
        ]);

        return redirect()
            ->route('frontend.orders.show', $order)
            ->with('message', 'Order cancelled successfully.');
    }
}