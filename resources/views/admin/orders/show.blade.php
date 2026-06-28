@extends('layouts.admin')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Order Details</h2>
        <p class="admin-page-subtitle">{{ $order->order_number }}</p>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn-outline">
        Back
    </a>
</div>

<div class="page-card" style="padding:18px;">

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <h4>Customer Details</h4>

            <p>
                <strong>Name:</strong>
                {{ $order->shipping_full_name }}
            </p>

            <p>
                <strong>Mobile:</strong>
                {{ $order->shipping_mobile }}
            </p>

            <p>
                <strong>Address:</strong><br>
                {{ $order->shipping_flat_house }},
                {{ $order->shipping_area_street }},
                @if($order->shipping_landmark)
                    {{ $order->shipping_landmark }},
                @endif
                {{ $order->shipping_city }},
                {{ $order->shipping_state }} -
                {{ $order->shipping_pincode }}
            </p>
        </div>

        <div class="col-md-6 mb-3">
            <h4>Order Summary</h4>

            <p><strong>Delivery:</strong> {{ ucfirst(str_replace('_', ' ', $order->delivery_method)) }}</p>
            <p><strong>Payment:</strong> {{ strtoupper($order->payment_method) }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
            <p><strong>Order Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</p>
            <p><strong>Total Payable:</strong> Rs{{ number_format($order->total_payable, 0) }}</p>
        </div>
    </div>

    <hr>

    <h4>Update Status</h4>

    <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="field-label">Order Status</label>

                <select name="order_status" class="field-input">
                    <option value="placed" {{ $order->order_status === 'placed' ? 'selected' : '' }}>Placed</option>
                    <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="packed" {{ $order->order_status === 'packed' ? 'selected' : '' }}>Packed</option>
                    <option value="out_for_delivery" {{ $order->order_status === 'out_for_delivery' ? 'selected' : '' }}>Out For Delivery</option>
                    <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="field-label">Payment Status</label>

                <select name="payment_status" class="field-input">
                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
        </div>

        <button class="btn-primary">
            Update Status
        </button>
    </form>

</div>

<div class="page-card" style="margin-top:18px;">
    <div class="page-card-table">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Brand</th>
                    <th>Size</th>
                    <th>Colour</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            <small>{{ $item->sku }}</small>
                        </td>

                        <td>{{ $item->brand_name }}</td>

                        <td>{{ $item->size }}</td>

                        <td>{{ $item->colour }}</td>

                        <td>{{ $item->qty }}</td>

                        <td>Rs{{ number_format($item->price, 0) }}</td>

                        <td>Rs{{ number_format($item->line_total, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="page-card" style="margin-top:18px;padding:18px;">
    <h4>Price Details</h4>

    <p>Total MRP: <strong>Rs{{ number_format($order->total_mrp, 0) }}</strong></p>
    <p>Discount: <strong>-Rs{{ number_format($order->discount, 0) }}</strong></p>
    <p>Subtotal: <strong>Rs{{ number_format($order->subtotal, 0) }}</strong></p>
    <p>Home Trial Fee: <strong>Rs{{ number_format($order->home_trial_fee, 0) }}</strong></p>
    <p>Platform Fee: <strong>Rs{{ number_format($order->platform_fee, 0) }}</strong></p>
    <p>Delivery Charge: <strong>Rs{{ number_format($order->delivery_charge, 0) }}</strong></p>

    <hr>

    <h4>Total Payable: Rs{{ number_format($order->total_payable, 0) }}</h4>
</div>

@endsection