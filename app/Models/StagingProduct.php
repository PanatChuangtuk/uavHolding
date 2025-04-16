<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StagingProduct extends Model
{
    use HasFactory;
    protected $table = 'staging_products';
    public $timestamps = false;
    protected $fillable = [
        'erp_sku',
        'description',
        'packsize',
        'last_direct_cost',
        'user',
        'dealer',
        'wholesales',
        'partner',
        'cas',
        'linearformula',
        'formulaweight',
        'density',
        'flashpoint',
        'meltingpoint',
        'boilingpoint',
        'unnumber',
        'hazardclass',
        'packinggroup',
        'tariffcode',
        'storageconditions',
        'shipment',
        'shelflifemonths',
        'brand',
        'item_id',
    ];
}
