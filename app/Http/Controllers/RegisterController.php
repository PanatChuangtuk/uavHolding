<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\{Hash, Auth, Validator, Http};
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Enum\IsSourceEnum;
use Illuminate\Support\Str;
use App\Models\{Member, MemberInfo, MemberToGroup, Otp};

class RegisterController extends MainController
{
    function registerIndex()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('profile', ['lang' => app()->getLocale()]);
        }
        return view('register');
    }
    public function submit(Request $request)
    {
        $userVerify = Member::where('mobile_phone', $request->mobile_phone)->first();
        if (!$userVerify || $userVerify->is_verify === 1) {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:member',
                'password' => 'required|string|min:8|confirmed',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'mobile_phone' => 'required|digits:10|unique:member,mobile_phone',
                'company' => 'nullable|string|max:255',
                'line_id' => 'nullable|string|max:50',
                'vat_register_number' => 'nullable|string|max:13',
                'account_type' => 'required|in:government,private',
                'newsletter' => 'nullable|boolean',
                // 'g-recaptcha-response' => 'required',
            ], [
                'username.required' =>  __('messages.please_enter_username'),
                'username.max' =>  __('messages.username_must_not_exceed_255_characters'),
                'email.required' => __('messages.please_enter_email'),
                'email.email' => __('messages.please_enter_valid_email'),
                'email.unique' => __('messages.email_already_in_use'),
                'password.required' =>  __('messages.please_enter_password'),
                'password.min' =>  __('messages.password_must_be_at_least_8_characters'),
                'password.confirmed' => __('messages.passwords_do_not_match'),
                'first_name.required' =>  __('messages.please_enter_firstname'),
                'last_name.required' => __('messages.please_enter_lastname'),
                'account_type.required' => __('messages.please_select_account_type'),
                'mobile_phone.required' =>  __('messages.please_enter_mobile_number'),
                'mobile_phone.unique' =>  __('messages.phone_exists'),
                'mobile_phone.digits' =>  __('messages.mobile_number_must_be_10_digits'),
                'account_type.in' => __('messages.invalid_account_type_selected'),
                'vat_register_number.max' => __('messages.tax_id_must_not_exceed_13_digits'),
                // 'g-recaptcha-response.required' => __('messages.captcha_is_required'),
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            // $verificationResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            //     'secret' => config('app.nocaptcha.secret'),
            //     'response' => $request->input('g-recaptcha-response'),
            //     'remoteip' => $request->ip(),
            // ]);
            // $result = $verificationResponse->json();
            // if (!$result['success']) {
            //     return redirect()->back()->withErrors(['g-recaptcha-response' => __('messages.recaptcha_verification_failed')]);
            // }
            $user = Member::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile_phone' => $request->mobile_phone,
                'is_source' => IsSourceEnum::Register->value,
                'is_verify' => 0,
                'created_at' => Carbon::now(),
                'created_by' => Auth::check() ? Auth::user()->id : null
            ]);
            MemberToGroup::create([
                'member_id' => $user->id,
                'member_group_id' => 1,
            ]);
            MemberInfo::create([
                'member_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'company' => $request->company,
                'line_id' => $request->line_id,
                'vat_register_number' => $request->vat_register_number,
                'account_type' => $request->account_type,
                'newsletter' => $request->newsletter,
            ]);
        }
        $otp = rand(100000, 999999);
        $ref = Str::random(4);
        Otp::create(
            ['mobile_phone' => $request->mobile_phone, 'otp' => $otp,  'ref' => $ref, 'expires_at' =>  now()->addMinutes(5), 'created_at' => now(), 'updated_at' => now(),]
        );
        session(['otp_verify_passcode' => $request->mobile_phone, 'otp_expire_time' => now()->addMinutes(5)]);
        //OTP SENDER
        $this->sendOtp($request->mobile_phone, 'U&V สมัครเพื่อขอยืนยันตัวตน รหัส OTP ของคุณคือ ' . $otp . ' [REF:' . $ref . '] รหัสจะหมดอายุใน 5 นาที');
        return redirect()->route('register.otp.verify', ['lang' => app()->getLocale()]);
    }
    function registerOtpVerify()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('profile', ['lang' => app()->getLocale()]);
        }
        $user = Otp::where('mobile_phone',   session('otp_verify_passcode'))
            ->orderBy('created_at', 'desc')
            ->first();

        $targetDate = Carbon::parse($user->expires_at);
        return view('register-otp', [
            'expiresAt' => $targetDate->timestamp * 1000,
        ]);
    }
    function registerOtpVerifySubmit(Request $request)
    {
        $otp = $request->input('otp');
        $otpCode = intval(implode('', $otp));
        $otpRecord = Otp::where('mobile_phone',  session('otp_verify_passcode'))->orderBy('created_at', 'desc')->first();
        $user = Member::where('mobile_phone', $otpRecord->mobile_phone)->first();
        if ($otpRecord->otp !== $otpCode) {
            return redirect()->back()->with('error', __('messages.invalid_otp'));
        }
        if ($otpRecord->expires_at < now()) {
            return redirect()->back()->with('error', __('messages.otp_expired'));
        }
        $user->update([
            'is_verify' => 1,
        ]);
        Auth::guard('member')->login($user);
        session()->forget(['otp_verify_passcode', 'otp_expire_time']);
        return redirect()->route('profile', ['lang' => app()->getLocale()])->with('success', __('messages.otp_verified'));
    }
}
