<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'mobile',
        'pincode',
        'flat_house',
        'area_street',
        'landmark',
        'city',
        'state',
        'address_type',
        'latitude',
        'longitude',
        'save_for_future',
        'is_default',
    ];

    protected $casts = [
        'save_for_future' => 'boolean',
        'is_default' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}