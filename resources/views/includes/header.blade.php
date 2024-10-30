<!-- Header -->
<header>
    <div class="header-container d-flex justify-content-between align-items-center p-3">
        <div class="logo">Proga</div>
        <button class="menu-toggle menu-toggle-test" aria-label="Открыть меню">
            <span class="menu-icon"></span>
        </button>
        <nav>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="/">Главная</a></li>
                <li class="nav-item"><a class="nav-link" href="/courses">Курсы</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">О нас</a></li>
                <li class="nav-item"><a class="nav-link" href="/contacts">Контакты</a></li>
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
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <!-- Добавляем новые ссылки для профиля и моих курсов -->
                        <a class="dropdown-item" href="{{ route('user.profile') }}">Профиль</a>
                        <a class="dropdown-item" href="{{ route('user.courses', ['id' => Auth::user()->id]) }}">Мои курсы</a>

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
    document.addEventListener('DOMContentLoaded', function() {
        let loginButton = document.querySelector('.btn-primary[href="{{ route('login') }}"]');
        if (loginButton) {
            loginButton.addEventListener('click', function(e) {
                e.preventDefault();
                let intendedUrl = window.location.href;
                localStorage.setItem('intended_url', intendedUrl);
                window.location.href = "{{ route('login') }}";
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        var menuToggle = document.querySelector('.menu-toggle');
        var nav = document.querySelector('.nav');

        menuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            if (nav.style.display === 'block') {
                nav.style.display = 'none';
            } else {
                nav.style.display = 'block';
            }
        });
    });

    let lastScrollTop = 0;
    const header = document.querySelector('header');

    window.addEventListener('scroll', function () {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            // Скроллим вниз, прячем шапку
            header.style.top = '-155px'; // Прячем шапку за верх экрана
        } else {
            // Скроллим вверх, показываем шапку
            header.style.top = '0';
        }

        lastScrollTop = scrollTop;
    });
</script>

