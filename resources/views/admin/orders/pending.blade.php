@extends('layouts.admin')

@section('title', 'Pending & In Progress Orders')

@section('content')
    <h2>Pending & In Progress Orders</h2>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Mobile</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Placed At</th>
            <th>Process Order</th>
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
                    <form method="POST" action="{{ route('admin.orders.process', $order) }}">
                        @csrf
                        <select name="status" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
