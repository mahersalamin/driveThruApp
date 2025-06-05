<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\NewOrderPlaced;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('items.item')->orderByDesc('created_at')->get();
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
}
