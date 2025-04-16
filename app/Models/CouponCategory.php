<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupon_category';
    protected $fillable = [
        'coupon_id',
        'category_id',
        'type'
    ];
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
