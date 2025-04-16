<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recommend extends CoreModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'recommend';

    protected $fillable = [
        'product_id',
        'product_model_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productModel()
    {
        return $this->belongsTo(ProductModel::class, 'product_model_id');
    }
    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }
}
