<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProductPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_price';

    protected $fillable = [
        'product_id',
        'member_group_id',
        'price',
        'status',
        'created_at',
        'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function memberGroup()
    {
        return $this->belongsTo(MemberGroup::class, 'member_group_id');
    }

    public function calculateDiscountedPriceByProductId($productId)
    {
        $member = Auth::guard('member')->user();
        if ($member) {
            $userGroup = $member->memberGroups->first()->id;
        } else {
            $userGroup = 1;
        }

        $productPrice = $this->where('product_id', $productId)->where('member_group_id', $userGroup)->first();

        if ($productPrice) {

            $discount = DiscountProduct::join('discount', 'discount_product.discount_id', '=', 'discount.id')
                ->select('discount_product.*', 'discount.discount_amount as dis_amount ', 'discount.discount_type as dis_type', 'discount.start_date', 'discount.end_date', 'discount.status')
                ->where('discount_product.product_id', $productId)
                ->where('discount.status', '1')
                ->where('discount.start_date', '<=', now('Asia/Bangkok'))
                ->where('discount.end_date', '>=', now('Asia/Bangkok'))
                ->first();
            // dump($discount);
            if ($discount) {
                $discountAmount = $discount->discount_amount;
                if ($discount->discount_type == 'percentage') {
                    $discountedPrice = $productPrice->price * (1 - ($discountAmount / 100));
                } else {
                    $discountedPrice = ($productPrice->price - $productPrice->price) + $discountAmount;
                }
                return $discountedPrice;
            }

            return $productPrice->price;
        }

        return null;
    }
}
