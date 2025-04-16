<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('administrator.login');
        }

        $user = Auth::guard('web')->user();

        if ($user->status == 0) {
            Auth::guard('web')->logout();
            return redirect()->route('administrator.login')->with('error', 'Your account has been disabled.');
        }

        return $next($request);
    }
}
