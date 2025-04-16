<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberToGroup extends Model
{

    protected $table = 'member_to_group';
    public $timestamps = false;
    protected $fillable = [
        'member_id',
        'member_group_id',
    ];

    public function memberGroup()
    {
        return $this->belongsTo(MemberGroup::class);
    }
}
