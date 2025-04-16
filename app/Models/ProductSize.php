<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $table = 'product_size';

    protected $fillable = [
        'id',
        'product_id', 
        'product_unit_id', 
        'product_unit_value_id', 
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id', 'id');
    }

    public function productUnitValue()
    {
        return $this->belongsTo(ProductUnitValue::class, 'product_unit_value_id', 'id');
    }
}

