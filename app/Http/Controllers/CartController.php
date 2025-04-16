<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Models\{Product, MemberAddressTax, MemberAddress, Order, OrdersProduct, OrderPayment, CouponMember, Member, Review, ShippingCost, TransitionPoint, Notification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Http};
use Carbon\Carbon;
use App\Enum\NotificationType;

class CartController extends MainController
{
    public function cartCheckIndex($lang, $id, Request $request)
    {
        $id = base64_decode($id);
        $order = Order::find($id);
        $order_product = OrdersProduct::with('product')->where('order_id', $id)->get();
        $userId = Auth::guard('member')->user()->id;

        $address = MemberAddress::select('member_address.*')
            ->where('member_address.member_id', $userId)
            ->orderBy('is_default', 'desc')
            ->get();
        $tax = MemberAddressTax::select('member_address_tax.*')
            ->where('member_address_tax.member_id', $userId)
            ->orderBy('is_default', 'desc')
            ->get();

        do {
            $reference = mt_rand(100000000000, 999999999999);
        } while (OrderPayment::where('reference', $reference)->exists());

        return view('cart-check-out', compact('order_product', 'order', 'address', 'tax', 'reference'));
    }
    public function cartIndex($lang, Request $request)
    {
        $shippingCost = ShippingCost::all()->first();
        $user = Auth::guard('member')->user();
        $cart = session('cart') ?? [];
        $ids =  array_keys($cart);
        $products = Product::whereIn('id', $ids)->get();

        return view('cart', compact('products', 'shippingCost', 'user'));
    }
    public function addToCart($lang, $id)
    {
        $product = Product::find($id);
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += 1;
        } else {
            $cart[$product->id]['quantity'] = 1;
        }
        session()->put('cart', $cart);
        return response()->json([
            'status' => 'add'
        ]);
    }
    public function removeCart($lang, $id)
    {
        $product = Product::find($id);
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id]) && $cart[$product->id]['quantity'] > 1) {
            $cart[$product->id]['quantity'] -= 1;
            session()->put('cart', $cart);
        }
        return response()->json([
            'status' => 'remove'
        ]);
    }
    public function deleteCart($lang, $id)
    {
        $product = Product::find($id);
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);
        return response()->json([
            'status' => 'delete'
        ]);
    }
    public function order($lang, Request $request)
    {
        // dd($request->all());
        $prefix = 'SOW';
        $yearMonth = now()->format('y') . now()->format('m');
        $lastOrderNumber = Order::where('order_number', 'like', "$prefix$yearMonth%")
            ->max('order_number');
        if ($lastOrderNumber) {
            $lastNumber = (int) substr($lastOrderNumber, -5);
            $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }
        $orderNumber = $prefix . $yearMonth . $nextNumber;
        $subtotal = $request->input('subtotal');
        $vat = $request->input('vat');
        $shippingFee = $request->input('shipping_fee');
        $discount = $request->input('discount');
        $total = $request->input('total');
        $cart = session()->get('cart', []);
        $items  = $request->input('items');
        $couponIds = $request->input('coupon_id');
        $member = Auth::guard('member')->user();
        if (!empty($couponIds)) {
            $couponMember = CouponMember::where('member_id', $member->id)->where('coupon_id', $couponIds);
            if ($couponMember) {
                $couponMember->update([
                    'used_at' => now(),
                ]);
            }
        }
        $order = Order::create([
            'member_id' => $member->id,
            'order_number' => $orderNumber,
            'subtotal' =>  $subtotal,
            'vat' =>   $vat,
            'shipping_free' => $shippingFee,
            'discount' => $discount,
            'total' => $total,
            'point' => $request->input('points'),
            'created_at' => Carbon::now(),
        ]);
        Notification::create([
            'member_id' => $member->id,
            'module_id' => $order->id,
            'module_name' => NotificationType::order_checkout->value,
            'created_at' => Carbon::now()
        ]);
        $member->decrement('point', $request->input('usePoint'));

        if ($request->input('points') > 0) {
            TransitionPoint::create([
                'member_id' => $member->id,
                'order_id' => $order->id,
                'point' => $request->input('points'),
                'status' => 0,
                'created_at' => Carbon::now(),
            ]);
        }

        foreach ($items as $item) {
            if (isset($cart[$item])) {
                $data = $cart[$item];
                OrdersProduct::create([
                    'product_id' => $item,
                    'order_id' => $order->id,
                    'name' =>  $data['name'],
                    'sku' =>  $data['sku'],
                    'size' =>  $data['size'],
                    'model' =>  $data['model'],
                    'price' =>  $data['price'],
                    'quantity' =>  $data['quantity'],
                    'total' => $data['price'] * $data['quantity'],
                    'created_at' => Carbon::now(),
                ]);
                // session()->forget("cart.$item");
            }
        }
        $encoded = substr(base64_encode($order->id), 0, 10);
        return redirect()->route('cart.check.index', ['lang' => app()->getLocale(), 'id' => $encoded]);
    }
}
