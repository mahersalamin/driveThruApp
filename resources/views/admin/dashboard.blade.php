@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="color: black;">Welcome, {{ auth()->user()->name }}</h2>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button style="background-color: #D32F2F; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px;">Logout</button>
            </form>
        </div>

        <h3 style="color: #D32F2F; margin-bottom: 1rem;">Dashboard Overview</h3>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem;">
            <div style="background-color: #fff8dc; border: 1px solid #000; padding: 1rem; border-radius: 6px; flex: 1; min-width: 200px;">
                <strong>Total Orders</strong>
                <p style="font-size: 1.5rem; margin: 0;">{{ $totalOrders }}</p>
            </div>

            <div style="background-color: #fff8dc; border: 1px solid #000; padding: 1rem; border-radius: 6px; flex: 1; min-width: 200px;">
                <strong>Total Sales</strong>
                <p style="font-size: 1.5rem; margin: 0;">${{ number_format($totalSales, 2) }}</p>
            </div>

            <div style="background-color: #fff8dc; border: 1px solid #000; padding: 1rem; border-radius: 6px; flex: 1; min-width: 200px;">
                <strong>Pending Orders</strong>
                <p style="font-size: 1.5rem; margin: 0;">{{ $pendingOrders }}</p>
            </div>
        </div>

        <h3 style="color: #D32F2F; margin-bottom: 0.5rem;">Top Items</h3>
        <ul style="list-style: none; padding-left: 1rem;">
            @forelse ($topItems as $item)
                <li style="margin-bottom: 4px;">• {{ $item->name }} <span style="color: #D32F2F;">({{ $item->total }})</span></li>
            @empty
                <li>No items found.</li>
            @endforelse
        </ul>
    </div>
@endsection
