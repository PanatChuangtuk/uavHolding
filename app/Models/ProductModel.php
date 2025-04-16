<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductModel extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'product_model';
    protected $fillable = [
        'product_type_id',
        'product_brand_id',
        'product_category_id',
        'image',
        'name',
        'code',
        'description',
        'status',
        'created_at',
        'created_by',
    ];
    protected $dates = ['deleted_at'];


    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class, 'product_brand_id')
                    ->withTrashed();
    }
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'id')
                    ->withTrashed();
    }
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'product_model_id', 'id');
    }
    public function productInformations()
    {
        return $this->hasMany(ProductInformation::class, 'product_model_id', 'id');
    }
    public function productPrices()
    {
        return $this->hasManyThrough(ProductPrice::class, Product::class, 'product_model_id', 'product_id', 'id', 'id');
    }
    public function favorites()
    {
        return $this->belongsToMany(Member::class, 'favourite', 'product_id', 'member_id');
    }
    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class);
    }
}
