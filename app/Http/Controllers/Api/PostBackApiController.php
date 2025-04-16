<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\{Product, MemberAddressTax, MemberAddress, OrderPayment, OrdersAddress, Review, TransitionPoint, Order, MemberInfo, ProductSize, OrderPo};
use Illuminate\Support\Facades\{Auth, DB, Http};
use App\Enum\TypeAddress;
use Carbon\Carbon;

class PostBackApiController extends Controller
{
    public function handlePostback(Request $request)
    {
        $data = $request->all();
        $refno = $data['refno'];
        $productDetail = $data['productdetail'];
        $cardType = $data['cardtype'];
        $statusName = $data['statusname'];
        $time = now()->toDateTimeString();
        if ($statusName === 'COMPLETED') {
            $order_product = DB::table('orders')
                ->join('orders_product', 'orders.id', '=', 'orders_product.order_id')
                ->where('orders.order_number', $productDetail)
                ->select(
                    'orders.*',
                    'orders_product.id as order_product_id',
                    'orders_product.sku',
                    'orders_product.quantity',
                    'orders_product.price',
                    'orders_product.product_id as product_id',
                )
                ->get();
            $orderId = $order_product->first()->id;
            $memberID = $order_product->first()->member_id;
            OrderPayment::create([
                'order_id' => $orderId,
                'cart_type' => $cardType,
                'reference' => $refno,
                'created_at' => $time
            ]);

            $address = MemberAddress::where('member_id',  $memberID)->where('is_default', 1)->first();
            OrdersAddress::create([
                'order_id' => $orderId,
                'member_id' => $address->member_id,
                'first_name' => $address->first_name,
                'last_name' => $address->last_name,
                'mobile_phone' => $address->mobile_phone,
                'email' => $address->email,
                'province_id' => $address->province_id,
                'district_id' => $address->district_id,
                'subdistrict_id' => $address->subdistrict_id,
                'postal_code' => $address->postal_code,
                'detail' => $address->detail,
                'type' => TypeAddress::Shipping->value,
                'description' => $request->work_description,
                'created_by' =>  $memberID
            ]);
            $tax = MemberAddressTax::where('member_id',  $memberID)->where('is_default', 1)->first();
            OrdersAddress::create([
                'order_id' => $orderId,
                'member_id' => $tax->member_id,
                'first_name' => $tax->first_name,
                'last_name' => $tax->last_name,
                'mobile_phone' => $tax->mobile_phone,
                'email' => $tax->email,
                'province_id' => $tax->province_id,
                'district_id' => $tax->district_id,
                'subdistrict_id' => $tax->subdistrict_id,
                'postal_code' => $tax->postal_code,
                'detail' => $tax->detail,
                'type' => TypeAddress::Bill->value,
                'description' => $request->work_description,
                'created_by' =>  $memberID
            ]);
            TransitionPoint::create([
                'member_id' =>  $memberID,
                'order_id' => $orderId,
                'point' => $order_product->first()->point,
                'status' => 1,
                'created_at' => Carbon::now(),
            ]);
            foreach ($order_product as $order_product_item) {
                $product = Product::find($order_product_item->product_id);
                if ($product) {
                    Review::create([
                        'member_id' =>  $memberID,
                        'product_model_id' => $product->product_model_id,
                        'order_product_id' => $order_product_item->order_product_id,
                        'comments' => 'Waiting for review',
                        'star_rating' => 0,
                        'status' => 0,
                        'is_show' => 0,
                        'created_at' => $time,
                        'created_by' =>  $memberID
                    ]);
                }
            }

            $formattedItems = $order_product->map(function ($item, $index) {
                $orderProduct = ProductSize::where('product_id', $item->product_id)->get();
                $productItem = Product::where('id', $item->product_id)->get();
                return [
                    'lineNo' => ($index + 1),
                    'itemNo' => $productItem->first()->item_id,
                    'quantity' => $item->quantity,
                    'unitOfMeasureCode' => $orderProduct->first()->productUnitValue->uom_id,
                    'unitPrice' => (float)$item->price,
                    'lineDiscountAmount' => (float)$item->discount,
                ];
            });
            $orderDate = Carbon::parse($order_product->first()->created_at)->format('Y-m-d');
            $payload = [
                "requestTime" => $time,
                "partnerCode" => "001",
                "transactionChannel" => "001",
                "orderNo" => $order_product->first()->order_number,
                "orderDate" =>  (string)$orderDate,
                "externalDocNo" => "EXTERNAL001",
                "remark" => "",
                "customerNo" => (string)$address->member->id,
                "customerName" => $address->member->username,
                "customerType" => 'private',
                // $info->account_type
                "vatRegistrationNo" => $address->info->vat_register_number ?? null,
                "address" => $address->detail,
                "district" =>  (string)optional($address->amphure)->name_th,
                "city" => (string)optional($address->tambon)->name_th,
                "province" => (string) optional($address->province)->name_th,
                "postCode" => (string)$address->postal_code,
                "tel" => $address->mobile_phone,
                "email" => $address->email,
                "billToAddress" => $tax->detail,
                "billToDistrict" =>  (string)$tax->district_id,
                "billToCity" => (string)$tax->subdistrict_id,
                "billToProvince" => (string)$tax->province_id,
                "billToPostCode" => (string)$tax->postal_code,
                "billToTel" => $tax->mobile_phone,
                "billToEmail" => $tax->email,
                "shipToAddress" => $address->detail,
                "shipToDistrict" => (string) $address->district_id,
                "shipToCity" => (string)$address->subdistrict_id,
                "shipToProvince" => (string)$address->province_id,
                "shipToPostCode" => (string)$address->postal_code,
                "shipToTel" => $address->mobile_phone,
                "shipToEmail" => $address->email,
                "totalLine" => $formattedItems->count(),
                "items" => $formattedItems->toArray()
            ];
            Http::withBasicAuth('web', 'Nav#1234')->post(
                'http://183.88.232.152:14148/bc14uvtst/api/amco/app/v1.0/companies(66e8e884-a363-4012-aef1-ce9c4855f09f)/CreateSalesOrders',
                $payload
            );
            // Log::info('API Response:', [
            //     'status' => $response->status(),
            //     'body' => $response->body(),
            //     'json' => $response->json()
            // ]);

            // if ($response->successful()) {
            //     echo  $response->body();
            // } else {
            //     $error = $response->body();
            //     echo "Failed to send data: $error";
            //     echo "Response: " . json_encode($response->json());
            // }
        }

        // $data = $request->all();
        // Log::info($data);
        return response()->json(['data' => $data]);
    }
    public function handlePoPostback(Request $request)
    {
        $data = $request->all();
        $po_number = $data['po_number'];
        $productDetail = $data['productdetail'];
        $statusName = $data['statusname'];
        $poCheck = Order::where('order_number', $productDetail)->first();
        $time = now()->toDateTimeString();
        if ($poCheck && !empty($poCheck->po_number)) {
            return response()->json(['message' => 'PO Number already exists.'], 400);
        }
        if ($statusName === 'COMPLETED') {
            $order_product = DB::table('orders')
                ->join('orders_product', 'orders.id', '=', 'orders_product.order_id')
                ->where('orders.order_number', $productDetail)
                ->select(
                    'orders.*',
                    'orders_product.id as order_product_id',
                    'orders_product.sku',
                    'orders_product.quantity',
                    'orders_product.price',
                    'orders_product.product_id as product_id',
                )
                ->get();
            $filename = null;
            if ($request->hasFile('file')) {
                $filename = $this->uploadsImage($request->file('file'), 'order_po');
            }
            $orderId = $order_product->first()->id;
            $memberID = $order_product->first()->member_id;

            OrderPo::create(['order_id' => $orderId, 'image' => $filename, 'created_at' => $time]);
            $poCheck->update(['type' => 'po', 'po_number' => $po_number]);

            $address = MemberAddress::where('member_id',  $memberID)->where('is_default', 1)->first();
            OrdersAddress::create([
                'order_id' => $orderId,
                'member_id' => $address->member_id,
                'first_name' => $address->first_name,
                'last_name' => $address->last_name,
                'mobile_phone' => $address->mobile_phone,
                'email' => $address->email,
                'province_id' => $address->province_id,
                'district_id' => $address->district_id,
                'subdistrict_id' => $address->subdistrict_id,
                'postal_code' => $address->postal_code,
                'detail' => $address->detail,
                'type' => TypeAddress::Shipping->value,
                'description' => $request->work_description,
                'created_by' =>  $memberID
            ]);
            $tax = MemberAddressTax::where('member_id',  $memberID)->where('is_default', 1)->first();
            OrdersAddress::create([
                'order_id' => $orderId,
                'member_id' => $tax->member_id,
                'first_name' => $tax->first_name,
                'last_name' => $tax->last_name,
                'mobile_phone' => $tax->mobile_phone,
                'email' => $tax->email,
                'province_id' => $tax->province_id,
                'district_id' => $tax->district_id,
                'subdistrict_id' => $tax->subdistrict_id,
                'postal_code' => $tax->postal_code,
                'detail' => $tax->detail,
                'type' => TypeAddress::Bill->value,
                'description' => $request->work_description,
                'created_by' =>  $memberID
            ]);

            foreach ($order_product as $order_product_item) {
                $product = Product::find($order_product_item->product_id);
                if ($product) {
                    Review::create([
                        'member_id' =>  $memberID,
                        'product_model_id' => $product->product_model_id,
                        'order_product_id' => $order_product_item->order_product_id,
                        'comments' => 'Waiting for review',
                        'star_rating' => 0,
                        'status' => 0,
                        'is_show' => 0,
                        'created_at' => $time,
                        'created_by' =>  $memberID
                    ]);
                }
            }


            $formattedItems = $order_product->map(function ($item, $index) {
                $orderProduct = ProductSize::where('product_id', $item->product_id)->get();
                $productItem = Product::where('id', $item->product_id)->get();
                return [
                    'lineNo' => ($index + 1),
                    'itemNo' => $productItem->first()->item_id,
                    'quantity' => $item->quantity,
                    'unitOfMeasureCode' => $orderProduct->first()->productUnitValue->uom_id,
                    'unitPrice' => (float)$item->price,
                    'lineDiscountAmount' => (float)$item->discount,
                ];
            });
            $orderDate = Carbon::parse($order_product->first()->created_at)->format('Y-m-d');
            $payload = [
                "requestTime" => $time,
                "partnerCode" => "001",
                "transactionChannel" => "001",
                "orderNo" => $order_product->first()->order_number,
                "orderDate" =>  (string)$orderDate,
                "externalDocNo" => "EXTERNAL001",
                "remark" => "",
                "customerNo" => (string)$address->member->id,
                "customerName" => $address->member->username,
                "customerType" => 'private',
                // "customerType" => $info->account_type,
                "vatRegistrationNo" => $address->info->vat_register_number ?? null,
                "address" => $address->detail,
                "district" =>  (string)optional($address->amphure)->name_th,
                "city" => (string)optional($address->tambon)->name_th,
                "province" => (string) optional($address->province)->name_th,
                "postCode" => (string)$address->postal_code,
                "tel" => $address->mobile_phone,
                "email" => $address->email,
                "billToAddress" => $tax->detail,
                "billToDistrict" =>  (string)$tax->district_id,
                "billToCity" => (string)$tax->subdistrict_id,
                "billToProvince" => (string)$tax->province_id,
                "billToPostCode" => (string)$tax->postal_code,
                "billToTel" => $tax->mobile_phone,
                "billToEmail" => $tax->email,
                "shipToAddress" => $address->detail,
                "shipToDistrict" => (string) $address->district_id,
                "shipToCity" => (string)$address->subdistrict_id,
                "shipToProvince" => (string)$address->province_id,
                "shipToPostCode" => (string)$address->postal_code,
                "shipToTel" => $address->mobile_phone,
                "shipToEmail" => $address->email,
                "totalLine" => $formattedItems->count(),
                "items" => $formattedItems->toArray()
            ];

            Http::withBasicAuth('web', 'Nav#1234')->post(
                'http://183.88.232.152:14148/bc14uvtst/api/amco/app/v1.0/companies(66e8e884-a363-4012-aef1-ce9c4855f09f)/CreateSalesOrders',
                $payload
            );
            // Log::info('API Response:', [
            //     'status' => $response->status(),
            //     'body' => $response->body(),
            //     'json' => $response->json()
            // ]);
        }
        return response()->json(['data' => $data]);
    }
}
