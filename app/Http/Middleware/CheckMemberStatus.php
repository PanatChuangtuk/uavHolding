<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMemberStatus
{
    public function handle(Request $request, Closure $next)
    {
        $member = Auth::guard('member')->user();

        if ($member && $member->status == 0) {
            Auth::guard('member')->logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account has been deactivated.']);
        }

        return $next($request);
    }
}
