@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container">
        <h2>Welcome, {{ auth()->user()->name }}</h2>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" style="margin-bottom: 1.5rem;">Logout</button>
        </form>

        <h3 style="color: var(--red); margin-bottom: 0.5rem;">Dashboard</h3>
        <ul style="list-style: none; padding: 0; margin-bottom: 2rem;">
            <li><strong>Total Orders:</strong> {{ $totalOrders }}</li>
            <li><strong>Total Sales:</strong> ${{ number_format($totalSales, 2) }}</li>
            <li><strong>Pending Orders:</strong> {{ $pendingOrders }}</li>
        </ul>

        <h3 style="color: var(--red); margin-bottom: 0.5rem;">Top Items</h3>
        <ul style="list-style: none; padding: 0;">
            @foreach ($topItems as $item)
                <li>{{ $item->name }} ({{ $item->total }})</li>
            @endforeach
        </ul>
    </div>
@endsection
