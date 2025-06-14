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

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAllNotificationsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->route('admin.notifications')->with('success', 'All notifications marked as read.');
    }

    public function show(Order $order, Request $request)
    {
        if ($request->has('notify')) {
            auth()->user()
                ->unreadNotifications()
                ->where('id', $request->get('notify'))
                ->first()?->markAsRead();
        }

        return view('admin.orders.show', compact('order'));
    }

    public function latest()
    {
        $notification = auth()->user()->unreadNotifications()->latest()->first();

        if ($notification) {
            return response()->json($notification);
        }

        return response()->json(null, 404);
    }
}
