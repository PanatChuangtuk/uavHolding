<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favourite extends Model
{

    protected $table = 'favourite';
    protected $fillable = [
        'member_id',
        'product_id'
    ];



    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
