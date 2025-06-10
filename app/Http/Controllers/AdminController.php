<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponseTrait;

class AdminController extends Controller
{
    use ApiResponseTrait;

    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalSales = Order::sum('total_price');
        $pendingOrders = Order::where('status', 'pending')->count();

        $topItems = DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->select('items.id as item_id', 'items.name', DB::raw('SUM(order_items.quantity) as total'))
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $data = [
            'total_orders' => $totalOrders,
            'total_sales' => $totalSales,
            'pending_orders' => $pendingOrders,
            'top_items' => $topItems,
        ];

        return $this->successResponse($data, 'Dashboard data retrieved successfully.');
    }
}
