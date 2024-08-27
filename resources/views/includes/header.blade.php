<!-- Header -->
<header>
    <div class="header-container d-flex justify-content-between align-items-center p-3">
        <div class="logo">Proga</div>
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
                    <a class="btn btn-primary" href="{{ route('login') }}">Авторизоваться</a>
                @endif
            @else
                <!-- Dropdown -->
                <div class="dropdown">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
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
