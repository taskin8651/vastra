@extends('layouts.admin')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Return Request Details</h2>
        <p class="admin-page-subtitle">{{ $returnRequest->return_number }}</p>
    </div>

    <a href="{{ route('admin.return-requests.index') }}" class="btn-outline">
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
            <h4>Request Details</h4>

            <p><strong>Return No:</strong> {{ $returnRequest->return_number }}</p>
            <p><strong>Order No:</strong> {{ optional($returnRequest->order)->order_number }}</p>
            <p><strong>User:</strong> {{ optional($returnRequest->user)->name }}</p>
            <p><strong>Amount:</strong> Rs{{ number_format($returnRequest->requested_amount, 0) }}</p>
            <p><strong>Reason:</strong> {{ ucfirst(str_replace('_', ' ', $returnRequest->reason)) }}</p>
            <p><strong>Refund Method:</strong> {{ strtoupper($returnRequest->refund_method) }}</p>
            <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $returnRequest->status)) }}</p>
        </div>

        <div class="col-md-6 mb-3">
            <h4>User Description</h4>
            <p>{{ $returnRequest->description ?: 'No description provided.' }}</p>

            <h4>Admin Note</h4>
            <p>{{ $returnRequest->admin_note ?: 'No admin note yet.' }}</p>
        </div>
    </div>

    <hr>

    <h4>Update Return Status</h4>

    <form method="POST" action="{{ route('admin.return-requests.updateStatus', $returnRequest) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="field-label">Status</label>

                <select name="status" class="field-input">
                    <option value="pending" {{ $returnRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $returnRequest->status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $returnRequest->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="picked_up" {{ $returnRequest->status === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    <option value="refunded" {{ $returnRequest->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label class="field-label">Admin Note</label>

                <textarea name="admin_note"
                          class="field-input"
                          rows="4"
                          placeholder="Write note for customer or internal record">{{ old('admin_note', $returnRequest->admin_note) }}</textarea>
            </div>
        </div>

        <button class="btn-primary">
            Update Request
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
                    <th>Refund Amount</th>
                </tr>
            </thead>

            <tbody>
                @foreach($returnRequest->items as $item)
                    <tr>
                        <td><strong>{{ $item->product_name }}</strong></td>
                        <td>{{ $item->brand_name }}</td>
                        <td>{{ $item->size }}</td>
                        <td>{{ $item->colour }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>Rs{{ number_format($item->amount, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection