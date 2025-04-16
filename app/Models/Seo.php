<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'seo';
    protected $fillable = [
        'type'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    public function seoContents()
    {
        return $this->hasMany(SeoContent::class, 'seo_id');
    }
}
