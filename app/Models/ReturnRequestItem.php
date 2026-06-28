<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequestItem extends Model
{
    protected $fillable = [
        'return_request_id',
        'order_item_id',
        'product_name',
        'brand_name',
        'size',
        'colour',
        'qty',
        'amount',
    ];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}