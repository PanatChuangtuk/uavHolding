<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends CoreModel
{
    use SoftDeletes;
    protected $table = 'orders';
    protected $fillable = [
        'member_id',
        'order_number',
        'tracking_no',
        'type',
        'po_number',
        'status',
        'subtotal',
        'vat',
        'shipping_free',
        'discount',
        'total',
        'point',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrdersProduct::class, 'order_id');
    }
    public function transitionPoints()
    {
        return $this->hasMany(TransitionPoint::class, 'order_id');
    }
    public function orderPayments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id', 'id');
    }
    public function address()
    {
        return $this->hasOne(OrdersAddress::class, 'order_id');
    }
    public function orderPo()
    {
        return $this->hasOne(OrderPo::class, 'order_id');
    }
}
