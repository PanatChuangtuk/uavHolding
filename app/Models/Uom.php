<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    use HasFactory;

    protected $table = 'uom';
    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'description',
        'symbol',
        'uom_id',
        'last_modified',
        'created_at'
    ];
}
