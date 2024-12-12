<!-- Header -->
<header>
    <div class="header-container d-flex justify-content-between align-items-center p-3">
        <div class="logo">Proga</div>
        <button class="menu-toggle menu-toggle-test" aria-label="Открыть меню">
            <span class="menu-icon"></span>
            <span class="menu-icon"></span>
            <span class="menu-icon"></span>
        </button>
        <nav>
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('courses') ? 'active' : '' }}"
                       href="/courses">Курсы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="/about">О
                        нас</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contacts') ? 'active' : '' }}"
                       href="/contacts">Контакты</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('blog') ? 'active' : '' }}"
                       href="/blog">Блог</a>
                </li>
            </ul>
        </nav>

        <div class="user-auth">
            @guest
                @if (Route::has('login'))
                    <!-- Сохраняем текущий URL и передаем его в качестве intended параметра -->
                    <a class="btn btn-primary" href="{{ route('login', ['intended' => url()->full()]) }}">
                        Авторизоваться
                    </a>
                @endif
            @else
                <!-- Dropdown -->
                <div class="dropdown">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <!-- Добавляем новые ссылки для профиля и моих курсов -->
                        <a class="dropdown-item" href="{{ route('user.user') }}">Профиль</a>
                        <a class="dropdown-item" href="{{ route('user.courses', ['id' => Auth::user()->id]) }}">Мои
                            курсы</a>

                        <!-- Ссылка для выхода -->
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                            Выйти
                        </a>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            @endguest
        </div>
    </div>
</header>

<!-- Подключение Bootstrap JS и jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.querySelector('.menu-toggle');
        const nav = document.querySelector('.nav');
        const header = document.querySelector('header');
        let lastScrollTop = 0;

        // Управление отображением меню
        menuToggle.addEventListener('click', function () {
            this.classList.toggle('active');
            nav.style.display = nav.style.display === 'block' ? 'none' : 'block';
        });

        // Сброс стилей навигации при изменении размера окна
        window.addEventListener('resize', function () {
            if (window.innerWidth > 900) {
                nav.style.display = 'flex';
                menuToggle.style.display = 'none';
            } else {
                nav.style.display = 'none';
                menuToggle.style.display = 'block';
            }
        });

        // Управление видимостью шапки при скролле
        window.addEventListener('scroll', function () {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            header.style.top = scrollTop > lastScrollTop ? '-155px' : '0';
            lastScrollTop = scrollTop;
        });
    });
</script>
