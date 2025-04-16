<?php

namespace App\Http\Controllers;

use App\Models\{ERPWebhookLog, Order, OrdersProduct,Tracking};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ERPWebhookLogController extends Controller
{
    public function index(Request $request)
    {
        $shipments = $request->json()->all();
        foreach ($shipments as $item) {
            $order = Order::where('order_number', $item['orderNo'])->first(); 

            if ($order) { 
                $order->update([
                    'tracking_no' => $item['trackingNo'],
                    'updated_at' => now(),
                ]);
            }
            
            $order_product = OrdersProduct::where('order_id', $order->id) 
            ->where('sku', $item['itemNo'])
            ->first();

            if ($order_product) {
            Tracking::create([
                'order_id' => $order->id,
                'order_product_id' => $order_product->id,
                'tracking_no' => $item['trackingNo'],
                'shipmentQty' => $item['shipmentQty'],
            ]);
            }
        };

        // $contentType = $request->header('Content-Type');

        // switch ($contentType) {
        //     case 'application/json':
        //         $payload = $request->json()->all();
        //         break;

        //     case 'application/xml':
        //         $payload = simplexml_load_string($request->getContent());
        //         break;

        //     case 'text/html':
        //         $payload = $request->getContent();
        //         break;

        //     default:
        //         $payload = $request->getContent();
        // }

        // $log = ERPWebhookLog::create([
        //     'payload' => json_encode($payload,),
        //     'ip_address' => $request->ip(),
        //     'content_type' => $contentType
        // ]);

        return response()->json([
            'message' => 'Data received and stored successfully',
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info('Updating data...');

        $contentType = $request->header('Content-Type');

        switch ($contentType) {
            case 'application/json':
                $payload = $request->json()->all();
                break;

            case 'application/xml':
                $payload = simplexml_load_string($request->getContent());
                break;

            case 'text/html':
                $payload = $request->getContent();
                break;

            default:
                $payload = $request->getContent();
        }

        $log = ERPWebhookLog::findOrFail($id);

        $log->update([
            'payload' => json_encode($payload, JSON_PRETTY_PRINT),
            'ip_address' => $request->ip(),
            'content_type' => $contentType,
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Data updated successfully',
            'updated_data' => $log
        ]);
    }

    public function delete($id)
    {
        $log = ERPWebhookLog::findOrFail($id);

        $log->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
            'deleted_id' => $id
        ]);
    }
}
