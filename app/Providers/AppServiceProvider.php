<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{View, URL, Blade};


class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
        Paginator::defaultView('administrator.pagination');
        Blade::if('notSuperAdmin', function ($roleId) {
            return $roleId != 1;
        });
    }
}
