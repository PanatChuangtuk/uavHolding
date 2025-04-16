<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountProduct extends CoreModel
{
    use HasFactory;

    protected $table = 'discount_product';

    protected $fillable = [
        'discount_id',
        'product_id',
        'discount_type',
        'discount_amount'

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }
}
