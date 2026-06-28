<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'user_address_id',
        'order_number',
        'delivery_method',
        'payment_method',
        'payment_status',
        'order_status',
        'total_mrp',
        'discount',
        'subtotal',
        'home_trial_fee',
        'platform_fee',
        'delivery_charge',
        'total_payable',
        'shipping_full_name',
        'shipping_mobile',
        'shipping_pincode',
        'shipping_flat_house',
        'shipping_area_street',
        'shipping_landmark',
        'shipping_city',
        'shipping_state',
        'shipping_address_type',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returnRequests()
{
    return $this->hasMany(\App\Models\ReturnRequest::class);
}
}