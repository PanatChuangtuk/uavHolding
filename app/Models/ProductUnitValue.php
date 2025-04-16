<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnitValue extends Model
{
    public $timestamps = false;

    protected $table = 'product_unit_value';

    protected $fillable = [
        'product_unit_id',
        'name',
        'uom_id',
        'created_at',
    ];

    // ความสัมพันธ์ใหม่: ProductUnitValue เชื่อมผ่าน ProductSize
    public function productSizes()
    {
        return $this->hasMany(ProductSize::class, 'product_unit_value_id');
    }

    // ความสัมพันธ์ใหม่: ProductUnitValue เชื่อมไปถึง Product ผ่าน ProductSize
    public function products()
    {
        return $this->hasManyThrough(
            Product::class,          // ปลายทาง
            ProductSize::class,      // ตารางกลาง
            'product_unit_value_id', // Foreign key ในตาราง product_size
            'id',                    // Foreign key ในตาราง product
            'id',                    // Local key ในตาราง product_unit_value
            'product_id'             // Local key ในตาราง product_size
        );
    }
}
