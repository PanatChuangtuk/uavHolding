<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponBrand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupon_brand';
    protected $fillable = [
        'coupon_id',
        'brand_id',
        'type'

    ];
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'brand_id');
    }
}
