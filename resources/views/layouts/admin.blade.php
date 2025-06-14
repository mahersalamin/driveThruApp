<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'لوحة المسؤول')</title>
    <style>
        body {
            background-color: #FFD700; /* Yellow background */
            color: #000000; /* Black text */
            font-family: Arial, sans-serif;
            margin: 0; padding: 0;
        }
        header {
            background-color: #D32F2F; /* Red */
            color: #FFD700;
            padding: 15px 20px;
        }
        nav a {
            color: #FFD700;
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            padding: 20px;
        }
        button {
            background-color: #D32F2F;
            border: none;
            color: #FFD700;
            padding: 8px 12px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #a12727;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #D32F2F;
            color: #FFD700;
        }
    </style>
    @stack('styles')
</head>
<body>
<header>
    <h1>لوحة المسؤول</h1>
    <nav>
        <a href="{{ route('admin.dashboard') }}">الرئيسية</a>
        <a href="{{ route('admin.orders.index') }}">كل الطلبات</a>
        <a href="{{ route('admin.orders.pending') }}">طلبات معلقة وجارية</a>
        <form style="display:inline" method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit">تسجيل الخروج</button>
        </form>
        <a href="{{ route('admin.notifications') }}" style="position: relative;">
            🛎️
            <span id="notification-count"
                  style="position: absolute; top: -5px; right: -10px; background-color: red; color: white;
                 border-radius: 50%; padding: 2px 6px; font-size: 12px; display: {{ auth()->user()->unreadNotifications->count() > 0 ? 'inline-block' : 'none' }};">
        {{ auth()->user()->unreadNotifications->count() }}
    </span>
        </a>

    </nav>
</header>
<div id="toast" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #FFD700;
    color: #000;
    padding: 15px 20px;
    border-left: 6px solid #D32F2F;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    border-radius: 5px;
    z-index: 10000;
    display: none;
    min-width: 250px;
">
    <div id="toast-message" style="margin-bottom: 10px;"></div>
    <a id="toast-link" href="#" style="
        background-color: #D32F2F;
        color: #fff;
        padding: 6px 12px;
        text-decoration: none;
        border-radius: 3px;
        font-size: 14px;
    ">View Order</a>
</div>

<main>
    @yield('content')
</main>
</body>
</html>

<script>
    let notificationCount = {{ auth()->user()->unreadNotifications->count() }};
    let notifyAudio = new Audio('/sounds/notify.mp3');
    let canPlaySound = false;

    // Allow sound after interaction
    window.addEventListener('click', () => {
        if (!canPlaySound) {
            notifyAudio.play().then(() => canPlaySound = true).catch(() => canPlaySound = true);
        }
    });

    function playNotificationSound() {
        if (canPlaySound) {
            notifyAudio.currentTime = 0;
            notifyAudio.play();
        }
    }

    function showToast(message, linkUrl, notificationId) {
        const toast = document.getElementById('toast');
        const messageDiv = document.getElementById('toast-message');
        const link = document.getElementById('toast-link');

        messageDiv.textContent = message;
        link.href = linkUrl + `?notify=${notificationId}`;
        toast.style.display = 'block';

        setTimeout(() => {
            toast.style.display = 'none';
        }, 8000);
    }


    function updateNotificationCountUI(count) {
        const badge = document.getElementById('notification-count');
        if (badge) {
            badge.innerText = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }

    setInterval(() => {
        fetch('{{ route("admin.notifications.count") }}')
            .then(response => response.json())
            .then(data => {
                if (data.count > notificationCount) {
                    // fetch the latest unread notification
                    fetch('{{ route("admin.notifications.latest") }}')
                        .then(res => res.json())
                        .then(notification => {
                            const orderId = notification.data.order_id;
                            const price = notification.data.total_price;
                            const notificationId = notification.id;

                            showToast(`🛒 New Order #${orderId} placed ($${price})`, `/orders/${orderId}`, notificationId);
                            playNotificationSound();
                        });


                    updateNotificationCountUI(data.count);
                }
                notificationCount = data.count;
            });
    }, 15000);
</script>


