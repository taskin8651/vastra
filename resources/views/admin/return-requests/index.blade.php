@extends('layouts.admin')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Return Requests</h2>
        <p class="admin-page-subtitle">Manage customer return and refund requests.</p>
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
                       placeholder="Search return number or order number"
                       class="field-input">
            </div>

            <div class="col-md-3 mb-2">
                <select name="status" class="field-input">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="picked_up" {{ request('status') === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <button class="btn-primary">Search</button>
            </div>
        </div>
    </form>

    <div class="page-card-table">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th>Return No.</th>
                    <th>Order No.</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($returnRequests as $requestItem)
                    <tr>
                        <td><strong>{{ $requestItem->return_number }}</strong></td>

                        <td>{{ optional($requestItem->order)->order_number }}</td>

                        <td>{{ optional($requestItem->user)->name }}</td>

                        <td>Rs{{ number_format($requestItem->requested_amount, 0) }}</td>

                        <td>{{ ucfirst(str_replace('_', ' ', $requestItem->reason)) }}</td>

                        <td>{{ ucfirst(str_replace('_', ' ', $requestItem->status)) }}</td>

                        <td>{{ $requestItem->created_at->format('d M Y') }}</td>

                        <td>
                            <a class="btn-outline btn-outline-edit"
                               href="{{ route('admin.return-requests.show', $requestItem) }}">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No return request found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px;">
        {{ $returnRequests->appends(request()->query())->links() }}
    </div>

</div>

@endsection