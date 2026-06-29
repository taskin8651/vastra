<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReturnRequestController extends Controller
{
    public function create(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        if ($order->order_status !== 'delivered') {
            return redirect()
                ->route('frontend.orders.show', $order)
                ->with('message', 'Return request is available only for delivered orders.');
        }

        $order->load(['items.product.media', 'items.product.brand.media', 'items.product.category.media']);

        return view('frontend.returns.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        if ($order->order_status !== 'delivered') {
            return redirect()
                ->route('frontend.orders.show', $order)
                ->with('message', 'Return request is available only for delivered orders.');
        }

        $data = $request->validate([
            'item_ids' => ['required', 'array', 'min:1'],
            'item_ids.*' => ['required', 'integer'],
            'reason' => ['required', 'in:size_issue,damaged_product,wrong_item,quality_issue,other'],
            'description' => ['nullable', 'string', 'max:1000'],
            'refund_method' => ['required', 'in:cash,upi,bank,wallet'],
        ]);

        $order->load('items');

        $items = $order->items()
            ->whereIn('id', $data['item_ids'])
            ->get();

        if ($items->isEmpty()) {
            return back()->withErrors([
                'item_ids' => 'Please select valid order item.',
            ]);
        }

        $alreadyRequestedItemIds = ReturnRequestItem::query()
            ->whereIn('order_item_id', $items->pluck('id'))
            ->whereHas('returnRequest', function ($query) {
                $query->whereNotIn('status', ['rejected']);
            })
            ->pluck('order_item_id')
            ->toArray();

        if (! empty($alreadyRequestedItemIds)) {
            return back()->withErrors([
                'item_ids' => 'Selected item already has an active return request.',
            ]);
        }

        $returnRequest = DB::transaction(function () use ($order, $items, $data) {

            $requestedAmount = $items->sum('line_total');

            $returnRequest = ReturnRequest::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'return_number' => $this->makeReturnNumber(),
                'reason' => $data['reason'],
                'description' => $data['description'] ?? null,
                'refund_method' => $data['refund_method'],
                'status' => 'pending',
                'requested_amount' => $requestedAmount,
            ]);

            foreach ($items as $item) {
                $returnRequest->items()->create([
                    'order_item_id' => $item->id,
                    'product_name' => $item->product_name,
                    'brand_name' => $item->brand_name,
                    'size' => $item->size,
                    'colour' => $item->colour,
                    'qty' => $item->qty,
                    'amount' => $item->line_total,
                ]);
            }

            return $returnRequest;
        });

        return redirect()
            ->route('frontend.orders.show', $order)
            ->with('message', 'Return request submitted successfully. Request No: ' . $returnRequest->return_number);
    }

    private function makeReturnNumber(): string
    {
        do {
            $number = 'RR-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        } while (ReturnRequest::where('return_number', $number)->exists());

        return $number;
    }
}
