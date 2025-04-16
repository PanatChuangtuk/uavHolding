<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_po';

    protected $fillable = [
        'order_id',
        'image',
    ];

    protected $dates = ['deleted_at'];

    public $timestamps = true;


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
