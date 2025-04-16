<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Models\{Order, OrdersProduct, Review, Product};
use Illuminate\Support\Facades\Auth;

class ReviewsController extends MainController
{
    public function reviewsIndex()
    {
        $userId = Auth::guard('member')->user()->id;
        $review = Review::with(['member', 'productModel', 'orderProduct'])
            ->join('orders_product', 'reviews.order_product_id', '=', 'orders_product.id')
            ->join('orders', 'orders_product.order_id', '=', 'orders.id')
            ->join('product', 'orders_product.product_id', '=', 'product.id')
            ->join('product_model', 'product.product_model_id', '=', 'product_model.id')
            ->where('reviews.member_id', $userId)
            ->where('reviews.status', 0)
            ->select([
                'reviews.id as review_id',
                'reviews.comments',
                'reviews.star_rating',
                'orders_product.name as order_product_name',
                'orders_product.size as order_product_size',
                'orders_product.model as order_product_model',
                'orders_product.price as order_product_price',
                'orders_product.quantity as order_product_quantity',
                'orders.order_number',
                'orders.total as order_total',
                'product.name as product_name',
                'product.size as product_size',
                'product.model as product_model',
                'product.price as product_price',
                'product_model.id as product_model_id',
                'product_model.name as product_model_name',
                'product_model.code as product_model_code',
            ])
            ->orderBy('orders.id', 'desc')
            ->get();
        return view('reviews', compact('review'));
    }
    public function myReviewIndex()
    {
        $userId = Auth::guard('member')->user()->id;
        $review = Review::with(['member', 'productModel', 'orderProduct'])
            ->join('orders_product', 'reviews.order_product_id', '=', 'orders_product.id')
            ->join('orders', 'orders_product.order_id', '=', 'orders.id')
            ->join('product', 'orders_product.product_id', '=', 'product.id')
            ->join('product_model', 'product.product_model_id', '=', 'product_model.id')
            ->where('reviews.member_id', $userId)
            ->where('reviews.status', 1)
            ->select([
                'reviews.id as review_id',
                'reviews.comments',
                'reviews.star_rating',
                'orders_product.name as order_product_name',
                'orders_product.size as order_product_size',
                'orders_product.model as order_product_model',
                'orders_product.price as order_product_price',
                'orders_product.quantity as order_product_quantity',
                'orders.order_number',
                'orders.total as order_total',
                'product.name as product_name',
                'product.size as product_size',
                'product.model as product_model',
                'product.price as product_price',
                'product_model.id as product_model_id',
                'product_model.name as product_model_name',
                'product_model.code as product_model_code',
            ])
            ->orderBy('orders.id', 'desc')
            ->get();
        return view('my-reviews', compact('review'));
    }
}
