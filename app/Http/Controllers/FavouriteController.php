<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\{Auth, Validator, Http};
use App\Models\{Favourite, ProductModel};

class FavouriteController extends MainController
{
    public function favouriteIndex()
    {
        $member = Auth::guard('member')->user();

        $favorite = Favourite::where('member_id', $member->id)->get();
        $productIds = $favorite->pluck('product_id');
        $isFavorite = ProductModel::find($productIds);
        return view('my-favourite', compact('isFavorite'));
    }
}
