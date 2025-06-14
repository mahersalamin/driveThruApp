@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
    <div class="container">
        <h2 style="color: var(--red); margin-bottom: 1rem;">🔔 Notifications</h2>

        @if(session('success'))
            <div style="background: var(--yellow); padding: 10px; border-left: 4px solid var(--red); margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($notifications->isEmpty())
            <p style="color: var(--black); font-size: 1.1rem;">No notifications yet.</p>
        @else
            <form method="POST" action="{{ route('admin.notifications.markAllRead') }}" style="margin-bottom: 1.5rem;">
                @csrf
                <button type="submit" style="background: var(--red); color: white; padding: 8px 12px; border: none; cursor: pointer; border-radius: 4px;">
                    Mark all as read
                </button>
            </form>

            <ul style="list-style: none; padding: 0;">
                @foreach($notifications as $notification)
                    <li style="
                        background: {{ $notification->read_at ? '#fffbe6' : '#ffe5e5' }};
                        border: 1px solid var(--black);
                        border-left: 4px solid {{ $notification->read_at ? 'var(--yellow)' : 'var(--red)' }};
                        padding: 10px 15px;
                        margin-bottom: 10px;
                        border-radius: 4px;
                        font-weight: {{ $notification->read_at ? 'normal' : 'bold' }};
                        color: var(--black);
                    ">
                        📦 Order #{{ $notification->data['order_id'] }} —
                        ${{ number_format($notification->data['total_price'], 2) }} —
                        <span>Status: {{ ucfirst($notification->data['status']) }}</span> —
                        <small>{{ \Carbon\Carbon::parse($notification->data['placed_at'])->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>

            <div style="margin-top: 20px; font-size: 14px;">
                {{ $notifications->onEachSide(1)->links('components.pagination.small') }}
            </div>
        @endif
    </div>
@endsection
