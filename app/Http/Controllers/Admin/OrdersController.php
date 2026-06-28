<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with(['user'])
            ->when($request->q, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('order_number', 'like', '%' . $request->q . '%')
                        ->orWhere('shipping_full_name', 'like', '%' . $request->q . '%')
                        ->orWhere('shipping_mobile', 'like', '%' . $request->q . '%');
                });
            })
            ->when($request->order_status, function ($query) use ($request) {
                $query->where('order_status', $request->order_status);
            })
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'order_status' => [
                'required',
                'in:placed,confirmed,packed,out_for_delivery,delivered,cancelled'
            ],
            'payment_status' => [
                'required',
                'in:pending,paid,failed'
            ],
        ]);

        $order->update($data);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('message', 'Order status updated successfully.');
    }
}