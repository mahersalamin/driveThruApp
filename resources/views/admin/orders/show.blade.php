@extends('layouts.admin')

@section('title', 'معلومات الطلب')

@section('content')
    <h2>معلومات #{{ $order->id }} طلب</h2>

    <p><strong>الزبون:</strong> {{ $order->name }}</p>
    <p><strong>الهاتف:</strong> {{ $order->mobile }}</p>
    <p><strong>الحالة:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>ملاحظات:</strong> {{ $order->note ?? 'N/A' }}</p>
    <p><strong>السعر الكلي:</strong> ${{ number_format($order->total_price, 2) }}</p>
    <p><strong>وقت الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>

    <h3>الأصناف</h3>
    <table>
        <thead>
        <tr>
            <th>اسم الصنف</th>
            <th>الحجم</th>
            <th>السعر</th>
            <th>الكمية</th>
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

    <a href="{{ route('admin.orders.index') }}">الرجوع لكل الطلبات</a>
@endsection
