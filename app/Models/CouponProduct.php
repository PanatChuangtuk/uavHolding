<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupon_product';
    protected $fillable = [
        'coupon_id',
        'product_id',
        'type'
    ];
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
