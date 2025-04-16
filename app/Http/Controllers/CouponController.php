<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\MainController;
use App\Models\{CouponMember, Coupon, TransitionPoint};
use Illuminate\Support\Facades\Auth;

class CouponController extends MainController
{
    public function couponIndex()
    {
        $member = Auth::guard('member')->user();
        $my_coupon = $member->coupon()
            ->where(function ($query) {
                $query->where('end_date', '>=', now('Asia/Bangkok'));
            })
            ->whereNull('coupon_member.used_at')
            ->get();
        return view('my-coupon', compact('my_coupon'));
    }

    public function pointIndex()
    {
        $userId =  Auth::guard('member')->user();
        $query = TransitionPoint::where('member_id', $userId->id);

        $type = request()->input('type');
        if ($type === 'received') {
            $query->where('status', 1);
        } elseif ($type === 'used') {
            $query->where('status', 0);
        }

        $point = $query->paginate(6);
        return view('my-point', compact('userId', 'point'));
    }
    public function applyCoupon(Request $request)
    {

        if (!Auth::guard('member')->check()) {
            return response()->json([
                'status' => 'unauthenticated',
                'message' => __('messages.login_required')
            ], 401);
        }

        $coupon = Coupon::where('id', $request->input('coupon_id'))
            ->where('status', true)
            ->where('limit', '>', 0)
            ->where('start_date', '<=', now('Asia/Bangkok'))
            ->where('end_date', '>=', now('Asia/Bangkok'))
            ->first();
        $userId =  Auth::guard('member')->user()->id;
        $hasCoupon = CouponMember::where('coupon_id', $coupon->id)
            ->where('member_id', $userId)
            ->exists();

        if ($hasCoupon) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.coupon_collected')
            ], 400);
        }
        CouponMember::create([
            'coupon_id' => $coupon->id,
            'member_id' =>  $userId,
            'used_at' => null
        ]);
        $coupon->decrement('limit');
        return response()->json([
            'status' => 'success',
            'message' => __('messages.coupon_applied_successfully')
        ]);
    }
}
