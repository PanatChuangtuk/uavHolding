<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'product';

    protected $fillable = [
        'product_model_id',
        'item_id',
        'sku',
        'name',
        'size',
        'model',
        'price',
        'quantity',
        'cost_price',
        'status',
        'created_at'
    ];

    // ความสัมพันธ์กับ ProductModel
    public function productModel()
    {
        return $this->belongsTo(ProductModel::class, 'product_model_id', 'id');
    }

    // ความสัมพันธ์กับ ProductPrice (ใช้ชื่อเดียวกัน)
    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

    // ความสัมพันธ์กับ ProductInformation
    public function productInformation()
    {
        return $this->hasMany(ProductInformation::class, 'product_id', 'id');
    }

    // ความสัมพันธ์กับ ProductSize
    public function productSizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id', 'id');
    }

    // ความสัมพันธ์ผ่าน HasManyThrough เพื่อเข้าถึง ProductUnitValue ผ่าน ProductSize
    public function productUnitValues()
    {
        return $this->hasManyThrough(
            ProductUnitValue::class,
            ProductSize::class,
            'product_id', // Foreign key ใน ProductSize
            'id',          // Foreign key ใน ProductUnitValue
            'id',          // Local key ใน Product
            'product_unit_value_id' // Local key ใน ProductSize
        );
    }
}
