@extends('layouts.admin')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Orders</h2>
        <p class="admin-page-subtitle">Manage customer orders, payment and delivery status.</p>
    </div>
</div>

<div class="page-card">

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form method="GET" style="padding:16px;">
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search order number, name, mobile"
                       class="field-input">
            </div>

            <div class="col-md-3 mb-2">
                <select name="order_status" class="field-input">
                    <option value="">All Status</option>
                    <option value="placed" {{ request('order_status') === 'placed' ? 'selected' : '' }}>Placed</option>
                    <option value="confirmed" {{ request('order_status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="packed" {{ request('order_status') === 'packed' ? 'selected' : '' }}>Packed</option>
                    <option value="out_for_delivery" {{ request('order_status') === 'out_for_delivery' ? 'selected' : '' }}>Out For Delivery</option>
                    <option value="delivered" {{ request('order_status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('order_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <button class="btn-primary">
                    Search
                </button>
            </div>
        </div>
    </form>

    <div class="page-card-table">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th>Order No.</th>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <strong>{{ $order->order_number }}</strong>
                        </td>

                        <td>{{ $order->shipping_full_name ?? optional($order->user)->name }}</td>

                        <td>{{ $order->shipping_mobile }}</td>

                        <td>Rs{{ number_format($order->total_payable, 0) }}</td>

                        <td>
                            {{ strtoupper($order->payment_method) }}
                            /
                            {{ ucfirst($order->payment_status) }}
                        </td>

                        <td>{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</td>

                        <td>{{ $order->created_at->format('d M Y h:i A') }}</td>

                        <td>
                            <a class="btn-outline btn-outline-edit"
                               href="{{ route('admin.orders.show', $order) }}">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px;">
        {{ $orders->appends(request()->query())->links() }}
    </div>

</div>

@endsection