@extends('layouts.admin')

@section('title', 'كل الطلبات')

@section('content')
    <div class="container">
        <h2 style="color: #D32F2F; margin-bottom: 1rem;">كل الطلبات</h2>

        <table style="width: 100%; border-collapse: collapse; background-color: #fff8dc;">
            <thead style="background-color: #FFD700; color: black;">
            <tr>
                <th style="padding: 0.5rem; border: 1px solid black;">الرقم</th>
                <th style="padding: 0.5rem; border: 1px solid black;">الزبون</th>
                <th style="padding: 0.5rem; border: 1px solid black;">الهاتف</th>
                <th style="padding: 0.5rem; border: 1px solid black;">السعر الكلي</th>
                <th style="padding: 0.5rem; border: 1px solid black;">الحالة</th>
                <th style="padding: 0.5rem; border: 1px solid black;">وقت الطلب</th>
                <th style="padding: 0.5rem; border: 1px solid black;">إجراءات</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($orders as $order)
                <tr style="border-bottom: 1px solid #000;">
                    <td style="padding: 0.5rem; border: 1px solid black;">{{ $order->id }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">{{ $order->name }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">{{ $order->mobile }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">${{ number_format($order->total_price, 2) }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">
                        @switch($order->status)
                            @case('pending')
                                <span style="color: orange; font-weight: bold;">معلق</span>
                                @break

                            @case('in_progress')
                                <span style="color: blue; font-weight: bold;">جاري العمل عليه</span>
                                @break

                            @case('completed')
                                <span style="color: teal; font-weight: bold;">مكتمل</span>
                                @break

                            @case('cancelled')
                                <span style="color: red; font-weight: bold;">ملغي</span>
                                {{-- <i class="fas fa-times-circle"></i> --}}
                                @break

                            @default
                                {{ ucfirst($order->status) }}
                        @endswitch
                    </td>
                    <td style="padding: 0.5rem; border: 1px solid black;">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td style="padding: 0.5rem; border: 1px solid black;">
                        <a href="{{ route('admin.orders.show', $order) }}" style="color: #D32F2F; text-decoration: underline;">عرض</a>
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
