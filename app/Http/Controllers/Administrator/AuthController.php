<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('administrator.dashboard');
        }
        return view('administrator.auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();

            if ($user->status == 0) {
                Auth::guard('web')->logout();
                return back()->with('error', 'บัญชีนี้ถูกปิดการใช้งาน โปรดติดต่อผู้ดูแลระบบ');
            }

            $user->last_activity = now();
            $user->save();

            return redirect()->route('administrator.dashboard')->with('success', 'Login berhasil');
        }

        return back()->with('error', 'Email or Password salah');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('administrator.login');
    }
}
