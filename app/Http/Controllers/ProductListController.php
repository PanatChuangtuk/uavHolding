<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Models\{ProductModel};
use Illuminate\Http\Request;

class ProductListController extends MainController
{
    public function productListIndex(Request $request)
    {
        $queryVaule = $request->query('queryVaule');
        $modelId = $request->query('Modelid');
        $category = $request->query('categoryid');
        $type = $request->query('type');
        $id = $request->query('id');
        $sort = $request->query('sort', 'desc');
        if ($modelId) {
            $product = ProductModel::with('productBrand')
                ->where('id', $modelId)
                ->orderBy('created_at', $sort)->where('status', 1)
                ->paginate($request->input('per_page', 15));
        } elseif ($queryVaule) {
            $product = ProductModel::with('productBrand')
                ->orwhere('name', 'like', "%{$queryVaule}%")->orWhere('code', 'like', "%{$queryVaule}%")
                ->orderBy('created_at', $sort)->where('status', 1)
                ->paginate($request->input('per_page', 15));
        } else {
            $product = ProductModel::with('productBrand')
                ->where('product_type_id', $type)
                ->when($id, function ($query) use ($id) {
                    return $query->where('product_brand_id', $id);
                })
                ->when(!$id, function ($query) use ($category) {
                    return $query->where('product_category_id', $category);
                })
                ->orderBy('created_at', $sort)->where('status', 1)
                ->paginate($request->input('per_page', 15));
        }
        $product->appends($request->query());
        return view('product-list', compact('product'));
    }
}
