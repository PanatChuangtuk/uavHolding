<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductBrand extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'product_brand';

    protected $fillable = [
        'product_type_id',
        'name',
        'code',
        'image',
        'status',
        'created_at',
        'created_by',
    ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function productModels()
    {
        return $this->hasMany(ProductModel::class, 'product_brand_id', 'id');
    }
}
