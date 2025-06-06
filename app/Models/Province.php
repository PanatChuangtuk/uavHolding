<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'provinces';

    protected $fillable = [
        'name_th',
        'name_en',
        'geography_id',
    ];

    protected $dates = ['deleted_at'];
    public function amphures()
    {
        return $this->hasMany(Amphure::class);
    }
}
