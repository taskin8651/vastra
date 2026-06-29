<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $productIds = collect($cart)
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        $products = Product::query()
            ->with(['media', 'brand.media', 'category.media', 'category.audience.media'])
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

        // Cart empty ho to delivery method reset
        if ($totalItems <= 0) {
            session()->forget('delivery_method');
        }

        $deliveryMethod = session('delivery_method');

        $totalMrp = $items->sum('line_mrp');
        $subtotal = $items->sum('line_total');
        $discount = max(0, $totalMrp - $subtotal);

        // Home Trial selected hoga tabhi Rs29 add hoga
        $homeTrialFee = ($totalItems > 0 && $deliveryMethod === 'home_trial') ? 29 : 0;

        $platformFee = $totalItems > 0 ? 0 : 0;
        $deliveryCharge = 0;

        $totalPayable = $subtotal + $homeTrialFee + $platformFee + $deliveryCharge;

        return view('frontend.cart.index', compact(
            'items',
            'totalItems',
            'totalMrp',
            'subtotal',
            'discount',
            'homeTrialFee',
            'platformFee',
            'deliveryCharge',
            'totalPayable',
            'deliveryMethod'
        ));
    }

    public function add(Request $request, Product $product)
    {
        abort_if(! $product->is_active, 404);

        $sizes = is_array($product->available_sizes) ? $product->available_sizes : [];
        $colours = is_array($product->available_colours) ? $product->available_colours : [];

        $size = $request->input('size') ?: ($sizes[0] ?? null);

        $colour = $request->input('colour') ?: ($product->colour ?: ($colours[0] ?? null));

        $qty = max(1, (int) $request->input('qty', 1));

        $key = md5($product->id . '|' . $size . '|' . $colour);

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'qty' => $qty,
                'size' => $size,
                'colour' => $colour,
            ];
        }

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Product added to cart.',
                'cart_count' => collect($cart)->sum('qty'),
            ]);
        }

        return redirect()
            ->route('frontend.cart.index')
            ->with('success', 'Product added to bag successfully.');
    }

    public function update(Request $request, string $key)
    {
        $cart = session()->get('cart', []);

        if (! isset($cart[$key])) {
            return back();
        }

        $qty = (int) $request->input('qty', 1);

        if ($qty <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['qty'] = $qty;
        }

        session()->put('cart', $cart);

        // Agar cart empty ho jaye to delivery method remove
        if (count($cart) === 0) {
            session()->forget('delivery_method');
        }

        return back();
    }

    public function remove(string $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
        }

        session()->put('cart', $cart);

        // Agar cart empty ho jaye to delivery method remove
        if (count($cart) === 0) {
            session()->forget('delivery_method');
        }

        return back();
    }

    public function deliveryMethod(Request $request)
    {
        $request->validate([
            'delivery_method' => ['required', 'in:home_trial,standard'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('frontend.cart.index')
                ->with('success', 'Please add products before selecting delivery.');
        }

        session([
            'delivery_method' => $request->delivery_method,
        ]);

        return redirect()
            ->route('frontend.cart.index')
            ->with('success', 'Delivery option selected successfully.');
    }
}
