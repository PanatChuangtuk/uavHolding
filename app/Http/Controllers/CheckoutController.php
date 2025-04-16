<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function exitCheckout(Request $request)
    {
        $orderData = json_decode($request->input('order_data'), true);
        foreach ($orderData as $item) {
            session()->forget("cart.{$item['product_id']}");
        }
        return response()->json(['status' => 'success']);
    }
}
