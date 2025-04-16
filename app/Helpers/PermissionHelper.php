<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermissionHelper
{
    public static function checkPermission($manageName)
    {
        // Log::info('Checking permission for: ' . $manageName);
        if (Auth::check()) {
            foreach (Auth::user()->role->permissions as $permission) {
                // Log::info('Checking permission: ' . $permission->route_name);
                if ($permission->route_name == $manageName) {
                    // Log::info('Permission found for: ' . $manageName);
                    return true;
                }
            }
        }
        // Log::info('Permission not found for: ' . $manageName);
        return false;
    }
}