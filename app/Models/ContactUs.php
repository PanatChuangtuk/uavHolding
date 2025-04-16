<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use SoftDeletes;

    protected $table = 'contact_us';

    protected $fillable = [
        'name',
        'subject',
        'email',
        'phone_number',
        'image',
        'message',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];
}
