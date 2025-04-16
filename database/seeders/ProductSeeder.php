<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = DB::table('oc_product')
            ->join('oc_product_description', 'oc_product.product_id', '=', 'oc_product_description.product_id')
            ->select(
                'oc_product.product_id',
                'oc_product.model',
                'oc_product.quantity',
                'oc_product.price',
                'oc_product_description.name as product_name',
                'oc_product_description.description as product_description'
            )
            ->get();

        foreach ($products as $product) {
            $productModelId = DB::table('product_model')->insertGetId([
                'name' => $product->product_name,
                'code' => $product->model,
                'description' => $product->product_description,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            DB::table('product')->insert([
                'id' => $product->product_id,
                'product_model_id' => $productModelId,
                'name' => $product->product_name,
                'model' => $product->model,
                'price' => $product->price,
                'quantity' => $product->quantity,
            ]);
        }
    }
}
