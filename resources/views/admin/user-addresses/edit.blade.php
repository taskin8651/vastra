@extends('layouts.admin')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Edit Address</h2>
        <p class="admin-page-subtitle">
            Customer: {{ optional($address->user)->name ?? 'N/A' }}
        </p>
    </div>

    <a href="{{ route('admin.user-addresses.index') }}" class="btn-outline">
        Back
    </a>
</div>

<div class="page-card">
    <form method="POST"
          action="{{ route('admin.user-addresses.update', $address) }}"
          class="p-4">

        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="field-label">Full Name</label>
                <input class="field-input" name="full_name" value="{{ old('full_name', $address->full_name) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="field-label">Mobile</label>
                <input class="field-input" name="mobile" value="{{ old('mobile', $address->mobile) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="field-label">Pincode</label>
                <input class="field-input" name="pincode" value="{{ old('pincode', $address->pincode) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="field-label">Address Type</label>
                <select class="field-input" name="address_type">
                    <option value="home" {{ $address->address_type === 'home' ? 'selected' : '' }}>Home</option>
                    <option value="work" {{ $address->address_type === 'work' ? 'selected' : '' }}>Work</option>
                    <option value="other" {{ $address->address_type === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label class="field-label">Flat / House</label>
                <input class="field-input" name="flat_house" value="{{ old('flat_house', $address->flat_house) }}">
            </div>

            <div class="col-md-12 mb-3">
                <label class="field-label">Area / Street</label>
                <input class="field-input" name="area_street" value="{{ old('area_street', $address->area_street) }}">
            </div>

            <div class="col-md-12 mb-3">
                <label class="field-label">Landmark</label>
                <input class="field-input" name="landmark" value="{{ old('landmark', $address->landmark) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="field-label">City</label>
                <input class="field-input" name="city" value="{{ old('city', $address->city) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="field-label">State</label>
                <input class="field-input" name="state" value="{{ old('state', $address->state) }}">
            </div>

        </div>

        <button class="btn-primary">
            Update Address
        </button>

    </form>
</div>

@endsection