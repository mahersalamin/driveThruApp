@extends('layouts.admin')

@section('title', 'Pending & In Progress Orders')

@section('content')
    <div class="container">
        <h2 style="color: #D32F2F; margin-bottom: 1rem;">الطلبات المكعلقة والجارية</h2>

        <table style="width: 100%; border-collapse: collapse; background-color: #fff8dc;">
            <thead style="background-color: #FFD700; color: black;">
            <tr>
                <th style="padding: 0.5rem; border: 1px solid black;">الرقم</th>
                <th style="padding: 0.5rem; border: 1px solid black;">الزبون</th>
                <th style="padding: 0.5rem; border: 1px solid black;">الهاتف</th>
                <th style="padding: 0.5rem; border: 1px solid black;">السعر الكلي</th>
                <th style="padding: 0.5rem; border: 1px solid black;">الحالة</th>
                <th style="padding: 0.5rem; border: 1px solid black;">وقت الطلب</th>
                <th style="padding: 0.5rem; border: 1px solid black;">معالجة الطلب</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($orders as $order)
                <tr style="border-bottom: 1px solid #000;">
                    <td style="padding: 0.5rem; border: 1px solid black;">{{ $order->id }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">{{ $order->name }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">{{ $order->mobile }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">${{ number_format($order->total_price, 2) }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">{{ ucfirst($order->status) }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">
                        <form method="POST" action="{{ route('admin.orders.process', $order) }}" style="display: flex; gap: 0.5rem; align-items: center;">
                            @csrf
                            <select name="status" style="padding: 0.25rem;">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>جاري العمل عليه</option>
                                <option value="completed">مكتمل</option>
                                <option value="cancelled">ملغي</option>
                            </select>
                            <button type="submit" style="background-color: #D32F2F; color: white; border: none; padding: 0.25rem 0.5rem; border-radius: 4px;">تحديث</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 1rem;">لا توجد أي طلبات.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px; font-size: 14px;">
            <div style="display: inline-block;">
                {{ $orders->onEachSide(1)->links('components.pagination.small') }}
            </div>
        </div>
    </div>
@endsection
