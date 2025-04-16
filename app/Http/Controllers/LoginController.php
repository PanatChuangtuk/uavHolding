<?php

namespace App\Http\Controllers;

use App\Enum\IsSourceEnum;
use Illuminate\Support\Facades\{Auth, Validator, Hash, Http};
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\{Member, Otp};
use Firebase\JWT\{JWT, Key};
use Carbon\Carbon;

class LoginController extends MainController
{
    public function loginIndex()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('profile', ['lang' => app()->getLocale()]);
        }
        return view('login');
    }

    public function login(Request $request)
    {
        return redirect()->route('profile', ['lang' => app()->getLocale()]);
    }

    public function otpVerifyPassword()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('profile', ['lang' => app()->getLocale()]);
        }
        $user = Otp::where('mobile_phone', session('otp_verify_passcode'))
            ->orderBy('created_at', 'desc')
            ->first();
        $targetDate = Carbon::parse($user->expires_at);
        return view('otp-verify-password', [
            'expiresAt' => $targetDate->timestamp * 1000,
        ]);
    }

    public function sendRequestOtp()
    {
        $user = Member::where('mobile_phone', session('otp_verify_passcode'))
            ->orWhere('mobile_phone', Auth::guard('member')->user()->mobile_phone)->first();
        if (!$user) {
            return response()->json([
                'message' => __('messages.please_click_get_otp'),
                'errors' => ['error' => __('messages.please_click_get_otp')]
            ], 400);
        }

        $otp = rand(100000, 999999);
        $ref = Str::random(4);
        Otp::create(
            ['mobile_phone' => $user->mobile_phone, 'otp' => $otp,  'ref' => $ref, 'expires_at' =>  now()->addMinutes(5), 'created_at' => now(), 'updated_at' => now(),]
        );
        session(['otp_verify_passcode' => $user->mobile_phone]);
        //OTP SENDER
        $this->sendOtp($user->mobile_phone, 'U&V Resend รหัส OTP ของคุณคือ ' . $otp . ' [REF:' . $ref . '] รหัสจะหมดอายุใน 5นาที');
        return response()->json([
            'message' => __('messages.otp_sent')
        ]);
    }

    public function otpVerifyPasswordSubmit(Request $request)
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('profile', ['lang' => app()->getLocale()]);
        }
        $otp = $request->input('otp');
        $otpCode = intval(implode('', $otp));
        $otpRecord = Otp::where('mobile_phone',  session('otp_verify_passcode'))->orderBy('created_at', 'desc')->first();

        if ($otpRecord->otp !== $otpCode) {
            return redirect()->back()->with('error', __('messages.invalid_otp'));
        }

        if ($otpRecord->expires_at < now()) {
            return redirect()->back()->with('error', __('messages.otp_expired'));
        }
        $user = Member::where('mobile_phone', session('otp_verify_passcode'))->first();
        $user->update(['is_verify' => 1]);
        Auth::guard('member')->login($user);

        session()->forget(['otp_verify_passcode', 'otp_expire_time']);
        return redirect()->route('profile', ['lang' => app()->getLocale()])->with('success', __('messages.otp_verified'));
    }


    public function dataForgotPassword()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('profile', ['lang' => app()->getLocale()]);
        }

        return view('otp-forgot-password-login');
    }


    public function dataForgotPasswordSubmit(Request $request)
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('profile', ['lang' => app()->getLocale()]);
        }
        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required|string',

        ], [
            'email_or_phone.required' =>  __('messages.email_or_phone_field_required'),

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userData = Member::where('email', $request->email_or_phone)
            ->orWhere('mobile_phone', $request->email_or_phone)
            ->first();

        if (!$userData) {
            return response()->json([
                'status' => 'error',
            ], 404);
        }

        $payload = [
            'email' => $request->email_or_phone,
            'iat' => time(),
            'exp' => time() + 300,
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        session(['reset_token' => $token]);
        $otp = rand(100000, 999999);
        $ref = Str::random(4);
        Otp::create([
            'mobile_phone' => $userData->mobile_phone,
            'otp' => $otp,
            'ref' =>  $ref,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        session(['otp_verify_passcode' => $userData->mobile_phone, 'otp_expire_time' => now()->addMinutes(5)]);

        //OTP SENDER
        $this->sendOtp($userData->mobile_phone, 'U&V Reset password รหัส OTP ของคุณคือ ' . $otp . ' [REF:' . $ref . '] รหัสจะหมดอายุใน 5 นาที');
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function otpForgotPassword()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('profile', ['lang' => app()->getLocale()]);
        }
        $user = Otp::where('mobile_phone', session('otp_verify_passcode'))
            ->orderBy('created_at', 'desc')
            ->first();

        $targetDate = Carbon::parse($user->expires_at);
        return view('otp-forgot-password', [
            'expiresAt' => $targetDate->timestamp * 1000,
        ]);
    }


    public function otpForgotPasswordSubmit(Request $request)
    {
        $otp = $request->input('otp');
        $otpCode = intval(implode('', $otp));
        $otpRecord = Otp::where('mobile_phone',  session('otp_verify_passcode'))->orderBy('created_at', 'desc')->first();

        if ($otpRecord->otp !== $otpCode) {
            return redirect()->back()->with('error', __('messages.invalid_otp'));
        }

        if ($otpRecord->expires_at < now()) {
            return redirect()->back()->with('error', __('messages.otp_expired'));
        }
        session()->forget(['otp_verify_passcode', 'otp_expire_time']);
        return redirect()->route('login.reset.password', [
            'lang' => app()->getLocale(),
        ]);
    }

    public function resetPasswordIndex()
    {
        if (!session('reset_token')) {
            return redirect()->route('index', ['lang' => app()->getLocale()])->with('error', 'ไม่พบ token');
        }
        if (Auth::guard('member')->check()) {
            return redirect()->route('profile', ['lang' => app()->getLocale()]);
        }
        return view('set-new-password');
    }

    public function resetPasswordSubmit($lang, Request $request)
    {
        $token = JWT::decode(session('reset_token'), new Key(env('JWT_SECRET'), 'HS256'));
        $userData = Member::where('email', $token->email)
            ->orWhere('mobile_phone', $token->email)
            ->first();
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => __('messages.password_field_required'),
            'password.confirmed' => __('messages.passwords_do_not_match'),
            'password.min' => __('messages.password_must_be_at_least_8_characters'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('new_password', 'password_confirmation'));
        }
        $userData->update([
            'is_source' => IsSourceEnum::Register->value,
            'password' => Hash::make($request->password),
        ]);
        session()->forget('reset_token');

        return redirect()->route('login', ['lang' => app()->getLocale()]);
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required|string',
            'password' => 'required|string|min:8',
        ], [
            'email_or_phone.required' => __('messages.email_or_phone_field_required'),
            'password.required' => __('messages.password_field_required'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $emailOrPhone = $request->input('email_or_phone');
        $password = $request->input('password');
        $user = Member::where('email', $emailOrPhone)->where('status', 1)
            ->orWhere('mobile_phone', $emailOrPhone)
            ->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email_or_phone' => __('messages.user_not_found')])->withInput();
        } elseif ($user->is_source === IsSourceEnum::Admin->value) {
            return redirect()->route('login.forgot.password', ['lang' => app()->getLocale()]);
        } else if (!Hash::check($password, $user->password)) {
            return redirect()->back()->withErrors(['password' => __('messages.invalid_password')])->withInput();
        }



        // $otp = rand(100000, 999999);
        // $ref = Str::random(4);
        // Otp::create(
        //     ['mobile_phone' => $user->mobile_phone, 'otp' => $otp,  'ref' => $ref, 'expires_at' =>  now()->addMinutes(5), 'created_at' => now(), 'updated_at' => now(),]
        // );
        // session(['otp_verify_passcode' => $user->mobile_phone, 'otp_expire_time' => now()->addMinutes(5)]);
        //OTP SENDER
        // $this->sendOtp($user->mobile_phone, 'U&V Login รหัส OTP ของคุณคือ ' . $otp . ' [REF:' . $ref . '] รหัสจะหมดอายุใน 5 นาที');
        // return redirect()->route('verify.password', ['lang' => app()->getLocale()]);
        Auth::guard('member')->login($user);
        return redirect()->route('profile', ['lang' => app()->getLocale()])->with('success', __('messages.login_success'));
    }
}
