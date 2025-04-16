<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\{MainController};
use Illuminate\Http\Request;
use App\Models\{Order};
use Carbon\Carbon;

class TrackApiController extends MainController
{
    public function trackShow(Request $request)
    {
        $trackingData = Order::where('tracking_no',  $request->input('tracking_number'))->first();
        if (!$trackingData) {
            return response()->json(['error' => 'ไม่พบข้อมูลหมายเลขพัสดุ'], 404);
        }
        $productNames = $trackingData->orderProducts;
        // dd($productNames->toArray());
        return response()->json([
            'tracking_no' => $trackingData->tracking_no,
            'status' => $trackingData->status,
            'updated_at' => Carbon::parse($trackingData->updated_at)
            ->timezone('Asia/Bangkok')
            ->format('d/m/Y H:i:s'),
            'products' => $productNames,
        ]);
    }
}
