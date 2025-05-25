<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Устанавливаем кодировку документа -->
    <meta charset="utf-8">
    <!-- Делаем вёрстку адаптивной -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF-токен для защиты AJAX-запросов -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Заголовок страницы из конфига APP_NAME -->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Подключение шрифтов -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Подключаем стили и скрипты через Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div id="app">
    <!-- Навигационная панель -->
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Логотип / название приложения, ссылка на главную -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <!-- Кнопка для открытия меню на мобильных -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Содержимое меню -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Левая часть меню (можно добавить ссылки) -->
                <ul class="navbar-nav me-auto"></ul>

                <!-- Правая часть меню: ссылки аутентификации -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <!-- Если гость — показываем Login/Register -->
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <!-- Если пользователь авторизован — отображаем имя и выпадающее меню -->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <!-- Ссылка на выход с формы POST -->
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <!-- Скрытая форма для logout -->
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Вывод flash-сообщений: success или error -->
    @if(session('success') || session('error'))
        <div id="flash-message"
             class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }}
                        position-fixed top-0 end-0 m-3 shadow p-3 rounded">
            {{ session('success') ?? session('error') }}
        </div>
        <script>
            // Автоматически скрыть сообщение через 3 секунды
            setTimeout(() => {
                document.getElementById('flash-message').style.display = 'none';
            }, 3000);
        </script>
    @endif

    <!-- Основной контент страницы -->
    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
