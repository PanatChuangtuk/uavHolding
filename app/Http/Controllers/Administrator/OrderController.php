<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderPayment, Tracking, OrdersProduct};
use Illuminate\Http\Request;


class OrderController extends Controller
{
    private $main_menu = 'orders';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');
        $type = $request->input('type');

        $orderQuery = Order::query()->whereIn('status', ['Approve', 'Delivery', 'Processed', 'Cancel'])->orderBy('status');

        if ($query) {
            $orderQuery->where('order_number', 'LIKE', "%{$query}%")
                ->orWhere('po_number', 'LIKE', "%{$query}%")
                ->orWhereHas('address', function ($q) use ($query) {
                    $q->where('first_name', 'LIKE', "%{$query}%")
                        ->orWhere('last_name', 'LIKE', "%{$query}%");
                });
        }

        if ($status === 'Approve') {
            $orderQuery->where('status', 'Approve');
        } else if ($status === 'Cancel') {
            $orderQuery->where('status', 'Cancel')->orWhere('status', 'Fail');
        } else if ($status === 'Delivery') {
            $orderQuery->where('status', 'Delivery');
        } else if ($status === 'Processed') {
            $orderQuery->where('status', 'Processed');
        }

        $orders = $orderQuery->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends([
                'query' => $query,
                'status' => $status,
            ]);


        $pending = Order::where('status', 'Waiting Approve')->get();
        $success = Order::where('status', 'Approve')->get();
        $fail = Order::where('status', 'Cancel')->get();

        return view('administrator.order.index', compact(
            'orders',
            'query',
            'status',
            'type',
            'main_menu',
            'pending',
            'success',
            'fail'
        ));
    }
    public function edit($id)
    {
        $order = Order::find($id);
        $tracking = Tracking::where('order_id', $id)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('tracking_no');
        // $unitOfMeasureIds = UnitOfMeasureIds::getUnitOfMeasureIds();
        $main_menu = $this->main_menu;
        return view('administrator.order.edit', compact('order', 'main_menu', 'tracking'));
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'item' => 'required',
            'status' => 'required|in:pending,success,fail',
        ]);
        $orderPayment = OrderPayment::where('order_id', $validated['item'])->first();
        if ($orderPayment) {
            $orderPayment->payment_status = $validated['status'];
            $orderPayment->save();
            return response()->json([
                'message' => 'Status updated successfully!',
                'status' => $orderPayment->payment_status,
                'success' => 'success'
            ]);
        }
        return response()->json([
            'message' => 'Order payment not found!',
        ], 404);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('administrator.order')->with('success', 'Order deleted successfully.');
    }
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'item' => 'required',
            'status' => 'required|in:pending,processing,failed,processed,shipped,refunded,complete,expired,canceled',
        ]);
        $orderItem = OrdersProduct::find($validated['item']);
        if ($orderItem) {
            $orderItem->status_product = $validated['status'];
            $orderItem->save();
            return response()->json([
                'message' => 'Status updated successfully!',
                'status' => $orderItem->status_product,
                'success' => true
            ]);
        }
        return response()->json([
            'message' => 'Order product not found!',
        ], 404);
    }
}
