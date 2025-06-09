<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return [
            'total_orders' => Order::count(),
            'total_sales' => Order::sum('total_price'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'top_items' => \DB::table('order_items')
                ->select('item_id', \DB::raw('SUM(quantity) as total'))
                ->groupBy('item_id')
                ->orderByDesc('total')
                ->take(5)
                ->get(),
        ];
    }

}
