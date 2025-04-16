<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Order, Notification, Tracking};
use Illuminate\Http\Request;
use App\Enum\NotificationType;
use Carbon\Carbon;

class OrderApproveController extends Controller
{
    private $main_menu = 'orders';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');
        // $orderStatus = $request->input('order', '');
        $orderQuery = Order::orderBy('created_at', 'desc');

        if ($query) {
            $orderQuery->where(function ($q) use ($query) {
                $q->where('order_number', 'LIKE', "%{$query}%")
                    ->orWhere('po_number', 'LIKE', "%{$query}%")
                    ->orWhereHas('address', function ($q) use ($query) {
                        $q->where('first_name', 'LIKE', "%{$query}%")
                            ->orWhere('last_name', 'LIKE', "%{$query}%");
                    });
            });
        }

        if ($status) {
            $orderQuery->where('status', $status);
        }

        $orders = $orderQuery->where('status', 'Waiting Approve')->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
            'page' => 1,
            // 'order' => $orderStatus,
        ]);
        return view('administrator.order_approve.index', compact(
            'orders',
            'query',
            'status',
            'main_menu',
        ));
    }

    public function edit($id)
    {
        $order = Order::find($id); // $unitOfMeasureIds = UnitOfMeasureIds::getUnitOfMeasureIds();
        $tracking = Tracking::where('order_id', $id)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('tracking_no');
        $main_menu = $this->main_menu;
        return view('administrator.order_approve.edit', compact('order', 'main_menu', 'tracking'));
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'item' => 'required|integer|exists:orders,id',
            'status' => 'required|in:waiting approve,approve,cancel,delivery,processed',
        ]);
        $orderPayment = Order::where('id', $validated['item'])->first();

        if ($orderPayment->type === 'po' && $validated['status'] === 'processed') {
            Notification::create([
                'member_id' => $orderPayment->member_id,
                'module_id' => $orderPayment->id,
                'module_name' => NotificationType::order->value,
                'created_at' => Carbon::now()
            ]);
        }
        if ($orderPayment) {

            $orderPayment->status = $validated['status'];
            $orderPayment->updated_at = now();
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

        return redirect()->route('administrator.order_approve')->with('success', 'Order deleted successfully.');
    }
}
