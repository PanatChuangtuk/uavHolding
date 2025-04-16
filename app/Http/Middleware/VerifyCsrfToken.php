<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */

     protected $addHttpCookie = true;

    protected $except = [
        'erp-webhook',  // เพิ่ม route ที่คุณต้องการยกเว้น CSRF
        'erp-webhook/*', // สำหรับการใช้ route ที่มี {id} ด้วย
    ];
}
