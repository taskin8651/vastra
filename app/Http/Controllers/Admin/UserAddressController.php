<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = UserAddress::query()
            ->with('user')
            ->when($request->q, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('full_name', 'like', '%' . $request->q . '%')
                        ->orWhere('mobile', 'like', '%' . $request->q . '%')
                        ->orWhere('pincode', 'like', '%' . $request->q . '%')
                        ->orWhere('city', 'like', '%' . $request->q . '%')
                        ->orWhere('state', 'like', '%' . $request->q . '%');
                });
            })
            ->latest()
            ->paginate(20);

        return view('admin.user-addresses.index', compact('addresses'));
    }

    public function edit(UserAddress $address)
    {
        $address->load('user');

        return view('admin.user-addresses.edit', compact('address'));
    }

    public function update(Request $request, UserAddress $address)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits_between:10,12'],
            'pincode' => ['required', 'digits:6'],
            'flat_house' => ['required', 'string', 'max:255'],
            'area_street' => ['required', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'address_type' => ['required', 'in:home,work,other'],
        ]);

        $address->update($data);

        return redirect()
            ->route('admin.user-addresses.index')
            ->with('message', 'Address updated successfully.');
    }

    public function destroy(UserAddress $address)
    {
        $address->delete();

        return redirect()
            ->route('admin.user-addresses.index')
            ->with('message', 'Address deleted successfully.');
    }
}