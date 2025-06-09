<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\NewOrderPlaced;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.item');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('mobile')) {
            $query->where('mobile', 'like', "%{$request->mobile}%");
        }

        if ($request->has('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }

        return $query->orderByDesc('created_at')->get();
    }


    public function show($id)
    {
        return Order::with('items.item')->findOrFail($id);
    }


    public function store(StoreOrderRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validated();

            $total = 0;
            $orderItems = [];
            foreach ($validated['items'] as $entry) {
                $item = Item::findOrFail($entry['item_id']);
                $sizeRecord = $item->sizes()->where('size', $entry['size'])->first();

                if (!$sizeRecord) {
                    return response()->json([
                        'message' => "Invalid size '{$entry['size']}' for item ID {$item->id}."
                    ], 422);
                }

                $subtotal = $sizeRecord->price * $entry['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'item_id'  => $item->id,
                    'size'     => $entry['size'],
                    'price'    => $sizeRecord->price,
                    'quantity' => $entry['quantity'],
                ];
            }

            $order = Order::create([
                'customer_id' => auth()->check() ? auth()->id() : null,
                'name'        => $validated['name'] ?? null,
                'mobile'      => $validated['mobile'] ?? null,
                'note'        => $validated['note'] ?? null,
                'total_price' => $total,
            ]);

            foreach ($orderItems as $orderItem) {
                $order->items()->create($orderItem);
            }
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewOrderPlaced($order));
            }

            return response()->json([
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
            ]);
        });
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Order status updated.']);
    }

    public function filterByStatus($status)
    {
        if (!in_array($status, ['pending', 'in_progress', 'completed', 'cancelled'])) {
            return response()->json(['message' => 'Invalid status'], 400);
        }

        $orders = Order::where('status', $status)->with('items')->get();
        return response()->json($orders);
    }

    public function myOrders(Request $request)
    {
        $orders = $request->user()->orders()->with('items')->latest()->get();

        return response()->json($orders);
    }

}
