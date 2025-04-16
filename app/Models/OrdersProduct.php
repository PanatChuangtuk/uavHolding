<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders_product';

    protected $fillable = [
        'product_id',
        'order_id',
        'status_product',
        'name',
        'sku',
        'size',
        'model',
        'price',
        'quantity',
        'total',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
