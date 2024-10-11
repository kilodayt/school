<!doctype html>
<html lang="en">

<head>
    @include('includes.head')
    <title>Профиль</title>
    <link rel="stylesheet" href="{{ asset('css/user/profile.css') }}">
</head>

<body>
@include('includes.header')

<div class="container mt-5 profile-container">
    <h1 class="profile-title">Профиль пользователя</h1>

    <div class="profile-content d-flex justify-content-between">
        <!-- Информация о пользователе -->
        <div class="profile-info">
            <h3 class="profile-section-title">Информация</h3>
            <div class="profile-info-item">
                <span class="label">Имя:</span>
                <span class="value">{{ Auth::user()->name }}</span>
            </div>
            <div class="profile-info-item">
                <span class="label">Email:</span>
                <span class="value">{{ Auth::user()->email }}</span>
            </div>
        </div>

        <!-- Действия -->
        <div class="profile-actions">
            <h3 class="profile-section-title">Действия</h3>
            <ul class="profile-actions-list">
                <li><a class="btn" href="#!">Мои курсы</a></li>
                <li><a class="btn" href="#!">Настройки профиля</a></li>
                <li><a class="btn btn-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Выйти
                    </a></li>
            </ul>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

@include('includes.footer')

</body>

</html>
