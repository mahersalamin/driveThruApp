<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
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

        return view('admin.dashboard', compact('totalOrders', 'totalSales', 'pendingOrders', 'topItems'));
    }
}

