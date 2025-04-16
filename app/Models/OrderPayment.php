<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderPayment extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;
    protected $table = 'orders_payment';

    protected $fillable = [
        'order_id',
        'cart_type',
        'reference',
        'payment_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
