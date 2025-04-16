<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Coupon, Product};
use Illuminate\Support\Facades\{Auth, DB, Http};
use App\Enum\{DiscountType, CouponType};
use Illuminate\Support\Facades\Log;


class ProductApiController extends Controller
{
    public function getProducts(Request $request)
    {
        // dump($request->all());
        $query = $request->get('query');
        $selectedIds = $request->input('selected_ids', []);

        $products = DB::table('product')
            ->select('id', 'sku', 'name')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('sku', 'like', '%' . $query . '%');
            })
            ->whereNotIn('id', $selectedIds)
            ->take(10)
            ->get();

        return response()->json(['results' => $products]);
    }
    public function getBrands(Request $request)
    {
        $query = $request->get('query');
        $selectedIds = $request->input('selected_ids', []);

        $products = DB::table('product_brand')
            ->select('id', 'code', 'name')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('code', 'like', '%' . $query . '%');
            })
            ->whereNotIn('id', $selectedIds)->whereNull('deleted_at')
            ->take(10)
            ->get();

        return response()->json(['results' => $products]);
    }
    public function getCategory(Request $request)
    {
        $query = $request->get('query');
        $selectedIds = $request->input('selected_ids', []);

        $products = DB::table('product_category')
            ->select('id', 'status', 'name')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%');
            })
            ->whereNotIn('id', $selectedIds)
            ->where('product_category.status', 1)
            ->whereNull('deleted_at')
            ->take(10)
            ->get();

        return response()->json(['results' => $products]);
    }
    public function getProductModel(Request $request)
    {
        $query = $request->get('query');
        $products = DB::table('product_model')->select('id', 'name')->where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('id', 'like', '%' . $query . '%')
                ->orWhere('name', 'like', '%' . $query . '%');
        })->take(10)->get();

        return response()->json(['results' => $products]);
    }

    public function getCouponDiscount(Request $request)
    {
        // dump($request->all());
        $coupons = $request->input('coupons');
        $subtotals = $request->input('subtotal');
        $couponDetails = [];
        $totalDiscount = 0;
        foreach ($coupons as $coupon) {
            $couponId = $coupon['coupon_id'];
            $productId = $coupon['product_id'];
            $couponDetails = Coupon::find($couponId);
            $product = Product::whereIn('id', $productId)
                ->with('productPrices')
                ->get()
                ->pluck('productPrices')
                ->flatten()
                ->pluck('price')
                ->max();
            // dump();
            if ($couponDetails->coupon_type === CouponType::DISCOUNT->value) {
                if ($subtotals >= ($couponDetails->base_price ?? 0)) {
                    if ($couponDetails->discount_type === DiscountType::PERCENTAGE->value) {
                        $discount = ($product * $couponDetails->discount_amount) / 100;
                        $totalDiscount += isset($couponDetails->max_discount)
                            ? min($discount, $couponDetails->max_discount)
                            : $discount;
                    } elseif ($couponDetails->discount_type === DiscountType::AMOUNT->value) {
                        $totalDiscount += $couponDetails->discount_amount;
                    }
                }
            } else if ($couponDetails->coupon_type === CouponType::FREE_SHIPPING->value) {
                return response()->json([
                    'free_shipping' => 100
                ], 200);
            }
        }
        // dump($totalDiscount);
        return response()->json([
            'discount' => $totalDiscount
        ], 200);
    }
    public function getUsedCoupons(Request $request)
    {
        $ids = $request->input('product_id');

        if (empty($ids)) {
            return;
        }

        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        $products = Product::whereIn('id', $ids)->get();

        $member = Auth::guard('member')->user();
        if (!$member) {
            return response()->json([
                'message' => 'ไม่มีคูปองที่ใช้งาน',
                'coupons' => []
            ], 401);
        }

        $usedCoupons = $member->coupon()
            ->where(function ($query) {
                $query->where('end_date', '>=', now('Asia/Bangkok'))
                    ->where('status', 1);
            })
            ->whereNull('coupon_member.used_at')
            ->whereHas('couponProducts', function ($query) use ($products) {
                if ($products->isNotEmpty()) {
                    $query->whereIn('product_id', $products->pluck('id'))
                        ->orWhere('product_id', 0);
                } else {
                    $query->where('product_id', 0);
                }
            })
            ->with(['couponProducts', 'couponBrands.brand.productModels', 'couponCategories.category.productModels'])
            ->get();

        $couponsThatCanBeUsed = [];

        foreach ($usedCoupons as $coupon) {
            $couponProducts = $coupon->couponProducts->filter(function ($couponProduct) use ($products) {
                return in_array($couponProduct->product_id, $products->pluck('id')->toArray()) || $couponProduct->product_id == 0;
            });

            if ($couponProducts->isNotEmpty()) {
                $couponBrands = $coupon->couponBrands->filter(function ($couponBrand) {
                    return $couponBrand->brand_id === 0;
                });

                $couponCategories = $coupon->couponCategories->filter(function ($couponCategory) {
                    return $couponCategory->category_id === 0;
                });

                $couponsThatCanBeUsed[] = [
                    'coupon' => $coupon,
                    'products' => $couponProducts,
                    'brands' => $couponBrands->map(fn($couponBrand) => $couponBrand->brand),
                    'categories' => $couponCategories->map(fn($couponCategory) => $couponCategory->category),
                ];
            }
        }
        // dump($couponBrands->map(fn($couponBrand) => $couponBrand->brand));
        return response()->json([
            'coupons' => $couponsThatCanBeUsed
        ]);
    }
    public function getCategorySingle(Request $request)
    {
        $query = $request->get('query');
        $selectedIds = $request->input('selected_ids');

        $products = DB::table('product_category')
            ->select('id', 'status', 'name')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%');
            })
            ->where('product_category.status', 1)
            ->whereNull('deleted_at')
            ->whereNot('id', $selectedIds)
            ->take(10)
            ->get();

        return response()->json(['results' => $products]);
    }
    public function getBrandsSingle(Request $request)
    {
        $query = $request->get('query');
        $selectedIds = $request->input('selected_ids');

        $products = DB::table('product_brand')
            ->select('id', 'code', 'name')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('code', 'like', '%' . $query . '%');
            })
            ->whereNull('deleted_at')
            ->whereNot('id', $selectedIds)
            ->take(10)
            ->get();

        return response()->json(['results' => $products]);
    }
    public function getTypesSingle(Request $request)
    {
        $query = $request->get('query');
        $selectedIds = $request->input('selected_ids');

        $types = DB::table('product_type')
            ->select('id', 'status', 'name')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%');
            })
            ->where('product_type.status', 1)
            ->whereNull('deleted_at')
            ->whereNot('id', $selectedIds)
            ->take(10)
            ->get();

        return response()->json(['results' => $types]);
    }
    public function getModelSingle(Request $request)
    {
        $query = $request->get('query');

        $products = DB::table('product_model')
            ->select('id', 'product_type_id', 'product_brand_id', 'product_category_id', 'name', 'code')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('code', 'like', '%' . $query . '%');
            })
            ->whereNull('deleted_at')
            ->take(10)
            ->get();
        // dd($products);
        return response()->json(['query' => $query, 'results' => $products]);
    }
    // public function getCartInfo()
    // {
    //     $cart = session('cart', []);
    //     $totalItems = count($cart);
    //     $totalPrice = array_sum(array_column($cart, 'price'));

    //     return response()->json([
    //         'success' => true,
    //         'total_items' => $totalItems,
    //         'total_price' => $totalPrice
    //     ]);
    // }
}
// public function getUsedCoupons(Request $request)
// {
//     $ids = $request->input('product_id');

//     if (empty($ids)) {
//         return;
//     }

//     if (!is_array($ids)) {
//         $ids = explode(',', $ids);
//     }

//     $products = Product::whereIn('id', $ids)->get();

//     $member = Auth::guard('member')->user();
//     if (!$member) {
//         return response()->json([
//             'message' => 'ไม่มีคูปองที่ใช้งาน',
//             'coupons' => []
//         ], 401);
//     }

//     $usedCoupons = $member->coupon()
//         ->where(function ($query) {
//             $query->where('end_date', '>=', now('Asia/Bangkok'))
//                 ->where('status', 1);
//         })
//         ->whereNull('coupon_member.used_at')
//         ->get();

//     $couponsThatCanBeUsed = [];

//     foreach ($usedCoupons as $coupon) {
//         if ($coupon->couponProducts) {
//             foreach ($coupon->couponProducts as $couponProduct) {
//                 dd($couponProduct->id);
//             }
//         }

//         $couponsThatCanBeUsed[] = [
//             'coupon' => $coupon
//         ];
//     }

//     return response()->json([
//         'coupons' => $couponsThatCanBeUsed
//     ]);
// }