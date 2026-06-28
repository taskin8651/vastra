@extends('layouts.admin')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">User Addresses</h2>
        <p class="admin-page-subtitle">Manage customer delivery addresses.</p>
    </div>
</div>

<div class="page-card">

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form method="GET" style="padding:16px;">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Search name, mobile, city, pincode"
               class="field-input">

        <button class="btn-primary" style="margin-top:10px;">
            Search
        </button>
    </form>

    <div class="page-card-table">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Type</th>
                    <th>Default</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($addresses as $address)
                    <tr>
                        <td>{{ optional($address->user)->name ?? 'N/A' }}</td>
                        <td>{{ $address->full_name }}</td>
                        <td>{{ $address->mobile }}</td>
                        <td>
                            {{ $address->flat_house }},
                            {{ $address->area_street }},
                            {{ $address->city }},
                            {{ $address->state }} -
                            {{ $address->pincode }}
                        </td>
                        <td>{{ ucfirst($address->address_type) }}</td>
                        <td>{{ $address->is_default ? 'Yes' : 'No' }}</td>
                        <td class="action-row">
                            <a class="btn-outline btn-outline-edit"
                               href="{{ route('admin.user-addresses.edit', $address) }}">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.user-addresses.destroy', $address) }}"
                                  style="display:inline-block;">
                                @csrf
                                @method('DELETE')

                                <button class="btn-outline btn-outline-delete"
                                        onclick="return confirm('Delete this address?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No address found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px;">
        {{ $addresses->links() }}
    </div>

</div>

@endsection