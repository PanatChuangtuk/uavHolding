<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupon';


    protected $fillable = [
        'name',
        'limit',
        'status',
        'coupon_type',
        'discount_type',
        'discount_amount',
        'base_price',
        'max_discount',
        'start_date',
        'end_date'
    ];
    public function couponContent()
    {
        return $this->hasMany(CouponContent::class, 'coupon_id');
    }
    public function member()
    {
        return $this->belongsToMany(Member::class, 'coupon_member')->withPivot('used_at');
    }
    public function couponBrands()
    {
        return $this->hasMany(CouponBrand::class, 'coupon_id', 'id');
    }

    public function couponCategories()
    {
        return $this->hasMany(CouponCategory::class);
    }

    public function couponProducts()
    {
        return $this->hasMany(CouponProduct::class);
    }
}
