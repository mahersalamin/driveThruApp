@extends('layouts.admin')

@section('title', 'Admin Login')

@section('content')
    <div class="container">
        <h2>كوفي وي - Coffee Way</h2>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <label for="email">الإيميل</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')
            <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">كلمة المرور</label>
            <input type="password" name="password" id="password" required>
            @error('password')
            <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">تسجيل الدخول</button>
        </form>
    </div>
@endsection
