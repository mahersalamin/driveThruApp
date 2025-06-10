<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\NewOrderPlaced;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponseTrait;

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

        $orders = $query->orderByDesc('created_at')->get();
        return $this->successResponse($orders);
    }

    public function show($id)
    {
        $order = Order::with('items.item')->find($id);
        return $order
            ? $this->successResponse($order)
            : $this->notFoundResponse('Order not found.');
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $validated = $request->validated();

            $total = 0;
            $orderItems = [];

            foreach ($validated['items'] as $entry) {
                $item = Item::find($entry['item_id']);
                if (! $item) {
                    return $this->notFoundResponse("Item ID {$entry['item_id']} not found.");
                }

                $sizeRecord = $item->sizes()->where('size', $entry['size'])->first();
                if (! $sizeRecord) {
                    return $this->validationErrorResponse([], "Invalid size '{$entry['size']}' for item ID {$item->id}.");
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

            $order = DB::transaction(function () use ($validated, $orderItems, $total) {
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

                // Notify all admins
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new NewOrderPlaced($order));
                }

                return $order;
            });

            return $this->successResponse([
                'order_id' => $order->id,
            ], 'Order placed successfully.', 201);

        } catch (\Exception $e) {
            return $this->errorResponse('Order placement failed.', 500, ['error' => $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $order = Order::find($id);
        if (! $order) {
            return $this->notFoundResponse();
        }

        $order->status = $validated['status'];
        $order->save();

        return $this->successResponse(null, 'Order status updated.');
    }

    public function filterByStatus($status)
    {
        if (! in_array($status, ['pending', 'in_progress', 'completed', 'cancelled'])) {
            return $this->validationErrorResponse([], 'Invalid status');
        }

        $orders = Order::where('status', $status)->with('items.item')->get();
        return $this->successResponse($orders);
    }

    public function myOrders(Request $request)
    {
        $orders = $request->user()->orders()->with('items.item')->latest()->get();
        return $this->successResponse($orders);
    }
}
