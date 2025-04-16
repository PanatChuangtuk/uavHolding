<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tambon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tambons';

    protected $fillable = [
        'zip_code',
        'name_th',
        'name_en',
        'amphure_id',
    ];

    protected $dates = ['deleted_at'];
    public function tambons()
    {
        return $this->hasMany(Tambon::class);
    }
}
