<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberAddress extends CoreModel
{
    use SoftDeletes;

    protected $table = 'member_address';

    protected $fillable = [
        'member_id',
        'first_name',
        'last_name',
        'email',
        'mobile_phone',
        'province_id',
        'district_id',
        'subdistrict_id',
        'postal_code',
        'detail',
        'is_default',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $dates = ['deleted_at'];
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function amphure()
    {
        return $this->belongsTo(Amphure::class, 'district_id');
    }

    public function tambon()
    {
        return $this->belongsTo(Tambon::class, 'subdistrict_id');
    }
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
