<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MemberInfo extends Model
{
    use SoftDeletes;

    protected $table = 'member_infomation';

    protected $fillable = [
        'member_id',
        'first_name',
        'last_name',
        'avatar',
        'company',
        'line_id',
        'vat_register_number',
        'account_type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function memberGroups()
    {
        return $this->hasMany(MemberToGroup::class, 'member_id', 'member_id');
    }
}