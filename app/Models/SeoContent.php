<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeoContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'seo_content';

    protected $fillable = [
        'seo_id',
        'language_id',
        'tag_title',
        'tag_description',
        'tag_keywords',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function seo()
    {
        return $this->belongsTo(Seo::class, 'seo_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
