@extends('layouts.admin')

@section('title', 'All Orders')

@section('content')
    <h2>All Orders</h2>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Mobile</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Placed At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->mobile }}</td>
                <td>${{ number_format($order->total_price, 2) }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px; font-size: 14px;">
        <div style="display: inline-block;">
            {{ $orders->onEachSide(1)->links('components.pagination.small') }}
        </div>
    </div>

@endsection
