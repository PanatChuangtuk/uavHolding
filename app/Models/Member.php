<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $table = 'member';

    protected $fillable = [
        'username',
        'password',
        'status',
        'email',
        'point',
        'mobile_phone',
        'is_source',
        'is_verify',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function favorites()
    {
        return $this->belongsToMany(ProductModel::class, 'favourite', 'member_id', 'product_id');
    }

    public function info()
    {
        return $this->hasOne(MemberInfo::class, 'member_id', 'id');
    }
    public function coupon()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_member')->withPivot('used_at');
    }
    public function order()
    {
        return $this->hasMany(Order::class, 'member_id');
    }

    public function transitionPoints()
    {
        return $this->hasMany(TransitionPoint::class, 'member_id');
    }
    public function addresses()
    {
        return $this->hasMany(MemberAddress::class, 'member_id');
    }
    public function memberGroups()
    {
        return $this->belongsToMany(MemberGroup::class, 'member_to_group', 'member_id', 'member_group_id');
    }
}
