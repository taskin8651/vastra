<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_id',
        'return_number',
        'reason',
        'description',
        'refund_method',
        'status',
        'requested_amount',
        'admin_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(ReturnRequestItem::class);
    }
}