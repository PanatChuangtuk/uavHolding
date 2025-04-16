<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupon_content';

    protected $fillable = [
        'coupon_id',
        'language_id',
        'name',
        'description'
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}
