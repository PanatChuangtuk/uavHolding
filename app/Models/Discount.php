<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'discount';


    protected $fillable = [
        'name',
        'discount_type',
        'discount_amount',
        'status',
        'start_date',
        'end_date',
    ];


    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class, 'discount_id');
    }
}
