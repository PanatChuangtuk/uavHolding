<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;
    protected $table = 'tracking';

    protected $fillable = [
        'order_id',
        'order_product_id',
        'tracking_no',
        'shipmentQty',
    ];

    protected $dates = ['deleted_at'];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrdersProduct::class, 'order_product_id');
    }
}
