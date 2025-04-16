<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupon_member';

    protected $fillable = [
        'member_id',
        'coupon_id',
        'used_at',
    ];
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
