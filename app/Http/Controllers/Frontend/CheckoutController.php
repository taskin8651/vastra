<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function payment()
    {
        $summary = $this->cartSummary();

        if ($summary['totalItems'] <= 0) {
            return redirect()
                ->route('frontend.cart.index')
                ->with('success', 'Please add products first.');
        }

        if (! $summary['deliveryMethod']) {
            return redirect()
                ->route('frontend.cart.index')
                ->with('success', 'Please select delivery option first.');
        }

        $address = UserAddress::query()
            ->where('user_id', auth()->id())
            ->where('id', session('selected_address_id'))
            ->first();

        if (! $address) {
            return redirect()
                ->route('frontend.address.index')
                ->with('message', 'Please select delivery address.');
        }

        return view('frontend.checkout.payment', array_merge($summary, compact('address')));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'in:cod'],
        ]);

        $summary = $this->cartSummary();

        if ($summary['totalItems'] <= 0) {
            return redirect()
                ->route('frontend.cart.index')
                ->with('success', 'Please add products first.');
        }

        if (! $summary['deliveryMethod']) {
            return redirect()
                ->route('frontend.cart.index')
                ->with('success', 'Please select delivery option first.');
        }

        $address = UserAddress::query()
            ->where('user_id', auth()->id())
            ->where('id', session('selected_address_id'))
            ->first();

        if (! $address) {
            return redirect()
                ->route('frontend.address.index')
                ->with('message', 'Please select delivery address.');
        }

        $order = DB::transaction(function () use ($summary, $address, $request) {

            $order = Order::create([
                'user_id' => auth()->id(),
                'user_address_id' => $address->id,
                'order_number' => $this->makeOrderNumber(),

                'delivery_method' => $summary['deliveryMethod'],
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'placed',

                'total_mrp' => $summary['totalMrp'],
                'discount' => $summary['discount'],
                'subtotal' => $summary['subtotal'],
                'home_trial_fee' => $summary['homeTrialFee'],
                'platform_fee' => $summary['platformFee'],
                'delivery_charge' => $summary['deliveryCharge'],
                'total_payable' => $summary['totalPayable'],

                'shipping_full_name' => $address->full_name,
                'shipping_mobile' => $address->mobile,
                'shipping_pincode' => $address->pincode,
                'shipping_flat_house' => $address->flat_house,
                'shipping_area_street' => $address->area_street,
                'shipping_landmark' => $address->landmark,
                'shipping_city' => $address->city,
                'shipping_state' => $address->state,
                'shipping_address_type' => $address->address_type,
            ]);

            foreach ($summary['items'] as $item) {
                $product = $item['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'brand_name' => optional($product->brand)->name,
                    'category_name' => optional($product->category)->name,
                    'sku' => $product->sku,
                    'size' => $item['size'],
                    'colour' => $item['colour'],
                    'price' => $item['price'],
                    'mrp' => $item['mrp'],
                    'qty' => $item['qty'],
                    'line_total' => $item['line_total'],
                    'line_mrp' => $item['line_mrp'],
                ]);

                if ($product->stock_quantity !== null && $product->stock_quantity > 0) {
                    $decrementQty = min((int) $product->stock_quantity, (int) $item['qty']);
                    $product->decrement('stock_quantity', $decrementQty);
                }
            }

            return $order;
        });

        session()->forget([
            'cart',
            'delivery_method',
            'selected_address_id',
        ]);

        return redirect()
            ->route('frontend.checkout.success', $order);
    }

    public function success(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load(['items', 'address']);

        return view('frontend.checkout.success', compact('order'));
    }

    private function cartSummary(): array
    {
        $cart = session()->get('cart', []);

        $productIds = collect($cart)
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        $products = Product::query()
            ->with(['brand', 'category.audience'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = collect($cart)->map(function ($row, $key) use ($products) {
            $product = $products->get($row['product_id'] ?? null);

            if (! $product) {
                return null;
            }

            $qty = max(1, (int) ($row['qty'] ?? 1));
            $price = (float) $product->price;

            $mrp = $product->compare_at_price
                ? (float) $product->compare_at_price
                : $price;

            return [
                'key' => $key,
                'product' => $product,
                'qty' => $qty,
                'size' => $row['size'] ?? null,
                'colour' => $row['colour'] ?? $product->colour,
                'price' => $price,
                'mrp' => $mrp,
                'line_total' => $price * $qty,
                'line_mrp' => $mrp * $qty,
            ];
        })->filter()->values();

        $totalItems = $items->sum('qty');
        $totalMrp = $items->sum('line_mrp');
        $subtotal = $items->sum('line_total');
        $discount = max(0, $totalMrp - $subtotal);

        $deliveryMethod = session('delivery_method');

        $homeTrialFee = ($totalItems > 0 && $deliveryMethod === 'home_trial') ? 29 : 0;
        $platformFee = $totalItems > 0 ? 0 : 0;
        $deliveryCharge = 0;

        $totalPayable = $subtotal + $homeTrialFee + $platformFee + $deliveryCharge;

        return compact(
            'items',
            'totalItems',
            'totalMrp',
            'subtotal',
            'discount',
            'deliveryMethod',
            'homeTrialFee',
            'platformFee',
            'deliveryCharge',
            'totalPayable'
        );
    }

    private function makeOrderNumber(): string
    {
        do {
            $number = 'VE-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}