<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ERPWebhookLog extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'erp_webhook_logs';

    protected $fillable = [
        'payload',
        'ip_address',
        'content_type',
        'payload',
    ];
}