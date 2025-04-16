<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Models\{Order, OrdersProduct,Tracking};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseController extends MainController
{
    public function purchaseIndex(Request $request)
    {
        $userId = Auth::guard('member')->user()->id;
        $status = $request->get('status', 'all');

        $orderProductQuery = OrdersProduct::with('order')
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('member_id', $userId);
            });

        if ($status != 'all') {
            $orderProductQuery->where('status_product', $status);
        }

        $orderProduct = $orderProductQuery->orderBy('id', 'desc')->get();
        return view('my-purchase', compact('orderProduct', 'status'));
    }
}
