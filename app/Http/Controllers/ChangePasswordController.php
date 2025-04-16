<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\{Auth, Http};
use App\Models\{Otp, Member};
use App\Http\Controllers\MainController;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ChangePasswordController extends MainController
{
    public function changePasswordIndex()
    {
        $user = Otp::where('mobile_phone',  Auth::guard('member')->user()->mobile_phone)
            ->orderBy('created_at', 'desc')
            ->first();

        $targetDate = Carbon::parse($user->expires_at);
        return view('change-password', [
            'expiresAt' => $targetDate->timestamp * 1000,
        ]);
    }
    public function getOtpRequest()
    {
        $user = Member::where('mobile_phone',  Auth::guard('member')->user()->mobile_phone)
            ->first();
        $otp = rand(100000, 999999);
        $ref = Str::random(4);
        Otp::create(
            ['mobile_phone' => $user->mobile_phone, 'otp' => $otp,  'ref' => $ref, 'expires_at' =>  now()->addMinutes(5), 'created_at' => now(), 'updated_at' => now(),]
        );
        session(['otp_verify_passcode' => $user->mobile_phone, 'otp_expire_time' => now()->addMinutes(5)]);
        //OTP SENDER
        $this->sendOtp($user->mobile_phone, 'U&V Resend รหัส OTP ของคุณคือ ' . $otp . ' [REF:' . $ref . '] รหัสจะหมดอายุใน 5 นาที');
        return response()->json(['status' => 'success']);
    }
    public function otpRequestSubmit(Request $request)
    {

        $otp = $request->input('otp');
        $otpCode = intval(implode('', $otp));
        $otpRecord = Otp::where('mobile_phone',  Auth::guard('member')->user()->mobile_phone)->orderBy('created_at', 'desc')->first();

        if ($otpRecord->otp !== $otpCode) {
            return redirect()->back()->with('error', __('messages.invalid_otp'));
        }

        if ($otpRecord->expires_at < now()) {
            return redirect()->back()->with('error', __('messages.otp_expired'));
        }
        session()->forget(['otp_verify_passcode', 'otp_expire_time']);
        return redirect()->route('profile.reset.password', ['lang' => app()->getLocale()])->with('success', __('messages.otp_verified'));
    }
}
