<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransitionPoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transition_point';

    protected $fillable = [
        'member_id',
        'order_id',
        'point',
        'status'
    ];

    protected $dates = ['deleted_at'];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
