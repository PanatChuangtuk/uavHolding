<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Banner, Language, News, ProductType, Recommend, ProductCategory, ProductBrand, Coupon, CouponMember, Order, Product, ProductPrice, ERPWebhookLog, Seo};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IndexController extends MainController
{
    function bannerShow()
    {
        // $test = ERPWebhookLog::all();
        // $test->map(function ($item) {
        //     $item->payload = json_decode($item->payload);
        //     return $item;
        // });
        // dump($test->pluck('payload'));
        $locale = app()->getLocale();
        $language = Language::where('code', $locale)->first();

        $news = News::select(
            'news.*',
            'news_content.*',
            'news_content.id as news_content.content_id ',
            'news_content.name as content_name',
            'news_image.image'
        )
            ->where('news.status', true)
            ->join('news_content', 'news_content.news_id', '=', 'news.id')
            ->where('news_content.language_id', $language->id)
            ->join('news_image', 'news_image.news_id', '=', 'news.id')
            ->paginate(10);

        $product_type = ProductType::orderBy('sort')->get();
        $product_brand = ProductBrand::select('product_type_id', 'id', 'name', 'image')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->get();
        // dd($product_brand);
        $product_category = ProductCategory::whereIn('product_type_id', $product_type->pluck('id'))->get();
        $member = Auth::guard('member')->user();
        if ($member) {
            $userGroup = $member->memberGroups->first()->id;
        } else {

            $userGroup = 1;
        }
        $recommends = Recommend::join('product_model', 'recommend.product_model_id', '=', 'product_model.id')
            ->join('product_brand', 'product_model.product_brand_id', '=', 'product_brand.id')
            ->join('product', 'product_model.id', '=', 'product.product_model_id')
            ->join('product_price', 'product.id', '=', 'product_price.product_id')
            ->select(
                'recommend.id as recommend_id',
                'recommend.product_model_id',
                'product.id as product_id',
                'product.product_model_id as model_product_id',
                'product_price.id as product_price_id',
                'product_price.member_group_id',
                'product_model.name as product_model_name',
                'product_model.code',
                'product_model.image',
                'product_brand.name as product_brand_name',
                'product_brand.image as product_brand_image',
                'product.name as product_name',
                'product.sku',
                'product.size',
                'product_price.price as product_price'
            )
            ->where('recommend.status', true)
            ->where('product_price.member_group_id', $userGroup)
            ->whereIn('product.product_model_id', Recommend::pluck('product_model_id'))
            ->whereIn('product.id', Recommend::pluck('product_id'))
            ->take(8)
            ->get()
            ->map(function ($item) {
                $productPriceObject = ProductPrice::where('product_id', $item->product_id)
                    ->first();
                $discountedPrice = $productPriceObject->calculateDiscountedPriceByProductId($item->product_id);
                $item->discounted_price = $discountedPrice;
                return $item;
            });
        // dd($recommends);
        if (Auth::guard('member')->check()) {
            $couponHasMember = CouponMember::whereHas('member', function ($query) {
                $query->where('member_id', Auth::guard('member')->user()->id);
            })->get();
            $coupon = Coupon::where('status', true)
                ->whereNotIn('id', $couponHasMember->pluck('coupon_id'))
                ->where('limit', '>', 0)
                ->where('start_date', '<=', Carbon::now('Asia/Bangkok'))
                ->where('end_date', '>=', Carbon::now('Asia/Bangkok'))->get();
        } else {
            $coupon = Coupon::where('status', true)
                ->where('limit', '>', 0)
                ->where('start_date', '<=', Carbon::now('Asia/Bangkok'))
                ->where('end_date', '>=', Carbon::now('Asia/Bangkok'))->get();
        }
        $banner = Banner::where('status', true)->get();
        $order = Order::where('status', 'Approve')->get();
        $bestSellers = Order::where('status', 'Approve')
            ->with('orderProducts')
            ->get()
            ->pluck('orderProducts')
            ->flatten()
            ->groupBy('product_id')
            ->map(fn($items, $product_id) => [
                'product_id' => $product_id,
                'quantity' => $items->sum('quantity')
            ])
            ->sortByDesc('quantity')
            ->take(10)
            ->values();
        $productIds = $bestSellers->pluck('product_id')->toArray();

        if (!empty($productIds)) {
            $productsBestSeller = Product::whereIn('id', $productIds)
                ->orderByRaw("FIELD(id, " . implode(',', $productIds) . ")")
                ->get()->map(function ($product) {
                    $discountedPrice = $product->productPrices->first()->calculateDiscountedPriceByProductId($product->id);
                    $product->discounted_price = $discountedPrice;
                    return $product;
                });
        } else {
            $productsBestSeller = collect([]);
        }
        $member = Auth::guard('member')->user();
        if ($member) {

            $userGroup = $member->memberGroups->first()->id;
        } else {
            $userGroup = 1;
        }

        return view('index', compact('banner', 'news', 'product_type', 'product_category', 'recommends', 'coupon', 'productsBestSeller', 'product_brand', 'userGroup'));
    }
}
