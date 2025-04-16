<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;
    protected $table = 'notifications';
    protected $fillable = [
        'member_id',
        'module_id',
        'module_name',
        'created_at',
    ];

    protected $dates = ['deleted_at'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    public function news()
    {
        return $this->belongsTo(News::class, 'module_id', 'id');
    }
    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'module_id', 'id');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'module_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'module_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'module_id', 'id');
    }
    public function orderCheckout()
    {
        return $this->belongsTo(Order::class, 'module_id', 'id');
    }
}
