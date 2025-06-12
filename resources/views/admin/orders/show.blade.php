@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
    <h2>Order #{{ $order->id }} Details</h2>

    <p><strong>Customer:</strong> {{ $order->name }}</p>
    <p><strong>Mobile:</strong> {{ $order->mobile }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Note:</strong> {{ $order->note ?? 'N/A' }}</p>
    <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
    <p><strong>Placed At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>

    <h3>Items</h3>
    <table>
        <thead>
        <tr>
            <th>Item Name</th>
            <th>Size</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->item->name ?? 'Deleted Item' }}</td>
                <td>{{ $item->size }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.orders.index') }}">Back to all orders</a>
@endsection
