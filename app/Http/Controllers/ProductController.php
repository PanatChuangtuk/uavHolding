<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Models\{News, Language, ProductModel, ProductCategory, ProductType, ProductInformation, ProductPrice, Review, ProductBrand, Favourite};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator, Http};

class ProductController extends MainController
{
    //favorites() error but using normal
    public function productIndex()
    {
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
        $product_category = ProductCategory::whereIn('product_type_id', $product_type->pluck('id'))->get();
        return view('product', compact('news', 'product_type', 'product_category', 'product_brand'));
    }
    public function productDetail($lang, $id)
    {
        $product = ProductModel::with('products.productPrices')->where('status', 1)->find($id);
        $totalReviews = Review::where('product_model_id', $product->id)->where('status', 1)->where('is_show', 1)->count();
        $review = Review::join('member', 'reviews.member_id', '=', 'member.id')
            ->where('reviews.product_model_id', $product->id)
            ->where('reviews.status', 1)
            ->where('reviews.is_show', 1)
            ->select('reviews.*', 'member.*')
            ->paginate(3);

        $member = Auth::guard('member')->user();

        if ($member) {
            $isFavorite = $member->favorites()->where('product_id', $product->id)->exists();
            $userGroup = $member->memberGroups->first()->id;
        } else {
            $isFavorite = false;
            $userGroup = 1;
        }


        $product_list = ProductPrice::with('product')
            ->where('member_group_id', $userGroup)
            ->whereIn('product_id', $product->products->pluck('id')->toArray())
            ->get()
            ->map(function ($item) {
                $discountedPrice = $item->calculateDiscountedPriceByProductId($item->product_id);
                $item->discounted_price = $discountedPrice;
                return $item;
            });
        $product_info = ProductInformation::with('productAttribute')
            ->where('product_model_id', $product->id)
            ->get();
        return view('product-detail', compact('product', 'product_list', 'product_info', 'review', 'totalReviews', 'isFavorite', 'userGroup'));
    }

    public function myFavourite($lang, $id)
    {
        $member = Auth::guard('member')->user();
        if (!$member) {
            return response()->json([
                'status' => 'unauthenticated',
                'message' => __('messages.login_required')
            ], 401);
        }
        $product = ProductModel::where('id', $id)->where('status', 1);
        $isFavorite = $member->favorites()->where('member_id', $member->id)->where('product_id', $product->id)->exists();
        if ($isFavorite) {
            $member->favorites()->detach($product->id);
            return response()->json([
                'status' => 'removed',
                'message' => 'Removed from favorites'
            ]);
        } else {
            $member->favorites()->attach($product->id, [
                'created_at' => now(),
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Added to favorites'
            ]);
        }
    }

    public function submit($lang, Request $request)
    {
        $cart = session()->get('cart', []);
        $ids = $request->input('id');
        $prices = $request->input('price');
        $names = $request->input('name');
        $skus = $request->input('sku');
        $sizes = $request->input('size');
        $quantities = $request->input('quantity');
        $models = $request->input('model');
        $validQuantityFound = false;
        foreach ($quantities as $quantity) {
            if ((int)$quantity > 0) {
                $validQuantityFound = true;
                break;
            }
        }
        if (!$validQuantityFound) {
            return redirect()->back()->with('zeroQty', __('messages.select_quantity'));
        }
        foreach ($ids as $index => $id) {
            $name = isset($names[$index]) ? $names[$index] : 0;
            $sku = isset($skus[$index]) ? $skus[$index] : 0;
            $size = isset($sizes[$index]) ? $sizes[$index] : 0;
            $model = isset($models[$index]) ? $models[$index] : 0;
            $price = isset($prices[$index]) ? $prices[$index] : 0;
            $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
            if ((int) $quantity > 0) {
                if (isset($cart[$id])) {
                    $cart[$id]['quantity'] += $quantity;
                } else {
                    $cart[$id] = [
                        'name' => $name,
                        'sku' => $sku,
                        'size' => $size,
                        'model' => $model,
                        'quantity' => $quantity,
                        'price' => $price,
                    ];
                }
            }
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index', ['lang' => app()->getLocale()]);
    }
    public function productReviewSubmit($lang, Request $request)
    {

        if (!Auth::guard('member')->check()) {
            return redirect()->route('login', ['lang' => app()->getLocale()]);
        }
        $member_id = Auth::guard('member')->user()->id;
        $validator = Validator::make($request->all(), [
            // 'g-recaptcha-response' => 'required',
        ], [
            // 'g-recaptcha-response.required' => __('messages.captcha_is_required'),
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
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
        $alredyReview = Review::where('member_id', $member_id)->where('product_model_id', $request->input('product_model_id'))->where('status', 0)->first();
        if (!$alredyReview) {
            return response()->json([
                'status' => 'already',
            ]);
        }

        $alredyReview->update([
            'member_id' => $member_id,
            'comments' => $request->input('comment'),
            'star_rating' => $request->input('star_rating'),
            'status' => $request->input('status', 1),
            'created_at' => Carbon::now(),
            'updated_by' => $member_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Review updated successfully!'
        ]);
    }
}
