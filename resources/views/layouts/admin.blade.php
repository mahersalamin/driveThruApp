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
    </nav>
</header>

<main>
    @yield('content')
</main>
</body>
</html>
