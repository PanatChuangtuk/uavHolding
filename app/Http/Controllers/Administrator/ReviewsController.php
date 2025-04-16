<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Review};

class ReviewsController extends Controller
{
    private $main_menu = 'products';
    public function index(Request $request)
    {
        $query = $request->input('query');

        $is_show = $request->input('is_show');

        $reviewsQuery = Review::query()->where('status', 1);

        if ($query) {
            $reviewsQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('productModel', function ($productModelQuery) use ($query) {
                    $productModelQuery->whereHas('products', function ($productQuery) use ($query) {
                        $productQuery->where('sku', 'LIKE', "%{$query}%");
                    });
                })->orWhereHas('member', function ($memberQuery) use ($query) {
                    $memberQuery->where('username', 'LIKE', "%{$query}%");
                });
            });
        }
        if ($is_show) {
            $statusValue = ($is_show === 'active') ? 1 : 0;
            $reviewsQuery->where('is_show', $statusValue);
        }
        $reviews = $reviewsQuery->paginate(10)->appends([
            'query' => $query,
            'is_show' => $is_show,
        ]);

        $main_menu = $this->main_menu;
        return view('administrator.reviews.index', compact('main_menu', 'query', 'is_show', 'reviews'));
    }

    public function update(Request $request)
    {

        $itemId = $request->input('id');
        $status = $request->input('is_show');

        $item = Review::find($itemId);

        if ($item) {
            $item->update([
                'is_show' => $status
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => true]);
    }
}
