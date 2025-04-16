<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $manageName): Response
    {
        if (Auth::check() && Auth::user()->role && Auth::user()->role->permissions->contains('route_name', $manageName)) {
            return $next($request);
        }

        $main_menu = 'default';
        if (isset($request->route()->action['main_menu'])) {
            $main_menu = $request->route()->action['main_menu'];
        }

        return response()->view('errors.permission', compact('main_menu'), 403);
    }
}