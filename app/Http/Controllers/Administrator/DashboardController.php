<?php

namespace App\Http\Controllers\Administrator;

use App\Models\{Member, Order, OrdersProduct};
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    private $main_menu = 'dashborad';


    public function index()
    {
        $main_menu = $this->main_menu;
        $orders = Order::all();
        $member = Member::all();

        $orders_member = Order::join('member_infomation', 'orders.member_id', '=', 'member_infomation.member_id')
            ->select('orders.*', 'orders.id as orders_id', 'member_infomation.*', 'orders.created_at as order_created_at')
            ->orderBy('orders.created_at', 'desc')
            ->take(5)->get();

        return view('administrator.dashboard', compact('orders', 'member', 'orders_member', 'main_menu'));
    }

    // public function salesOverview(Request $request)
    // {
    //     $type = $request->query('type', 'day'); // ค่าเริ่มต้นเป็น 'day'

    //     $query = Order::query();

    //     if ($type === 'day') {
    //         $query->whereDate('created_at', Carbon::today());
    //     } elseif ($type === 'week') {
    //         $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    //     } elseif ($type === 'month') {
    //         $query->whereMonth('created_at', Carbon::now()->month)
    //             ->whereYear('created_at', Carbon::now()->year);
    //     }

    //     $totalSales = $query->sum('total');

    //     return response()->json([
    //         'totalSales' => number_format($totalSales, 2)
    //     ]);
    // }
}
