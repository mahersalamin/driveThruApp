<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusChanged;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class OrderController extends Controller
{
    use ApiResponseTrait;

    // Show all orders
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.item'); // eager load related items and their details

        return view('admin.orders.show', compact('order'));
    }

    // Show only pending orders
    public function pendingOrders()
    {
        $orders = Order::whereIn('status', ['pending', 'in_progress'])
            ->latest()
            ->paginate(10);

        return view('admin.orders.pending', compact('orders'));
    }


    // Process a pending order (change status to 'processed')
    public function process(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        // Only allow processing if current status is 'pending' or 'in_progress'
        if (!in_array($order->status, ['pending', 'in_progress'])) {
            return redirect()->back()->with('error', 'Only pending or in-progress orders can be updated.');
        }

        $order->status = $validated['status'];
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

}
