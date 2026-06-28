<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()
            ->addresses()
            ->latest()
            ->get();

        if ($addresses->isEmpty()) {
            return redirect()
                ->route('frontend.address.create')
                ->with('message', 'Please add your delivery address.');
        }

        $selectedAddressId = session('selected_address_id');

        if (! $selectedAddressId) {
            $selectedAddressId = optional($addresses->firstWhere('is_default', true))->id
                ?? optional($addresses->first())->id;
        }

        return view('frontend.addresses.index', compact('addresses', 'selectedAddressId'));
    }

    public function create()
    {
        $address = new UserAddress();

        return view('frontend.addresses.form', compact('address'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['user_id'] = auth()->id();
        $data['save_for_future'] = $request->boolean('save_for_future', true);

        $hasAddress = auth()->user()->addresses()->exists();

        if (! $hasAddress || $request->boolean('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        $address = UserAddress::create($data);

        session(['selected_address_id' => $address->id]);

        return redirect()
            ->route('frontend.address.index')
            ->with('message', 'Address saved successfully.');
    }

    public function edit(UserAddress $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        return view('frontend.addresses.form', compact('address'));
    }

    public function update(Request $request, UserAddress $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $data = $this->validated($request);

        $data['save_for_future'] = $request->boolean('save_for_future', true);

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update([
                'is_default' => false,
            ]);

            $data['is_default'] = true;
        }

        $address->update($data);

        return redirect()
            ->route('frontend.address.index')
            ->with('message', 'Address updated successfully.');
    }

    public function select(UserAddress $address)
{
    abort_if($address->user_id !== auth()->id(), 403);

    auth()->user()->addresses()->update(['is_default' => false]);

    $address->update(['is_default' => true]);

    session(['selected_address_id' => $address->id]);

    return redirect()
        ->route('frontend.address.index')
        ->with('message', 'Address selected successfully. Please continue to payment.');
}

    public function destroy(UserAddress $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        if (session('selected_address_id') == $address->id) {
            session()->forget('selected_address_id');
        }

        $address->delete();

        return redirect()
            ->route('frontend.address.index')
            ->with('message', 'Address deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits_between:10,12'],
            'pincode' => ['required', 'digits:6'],
            'flat_house' => ['required', 'string', 'max:255'],
            'area_street' => ['required', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'address_type' => ['required', 'in:home,work,other'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'is_default' => ['nullable'],
            'save_for_future' => ['nullable'],
        ]);
    }
}