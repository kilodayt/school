<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.head')
    <link rel="stylesheet" href="{{ asset('css/pages/about.css') }}">
    <title>О нас</title>
</head>

<body>

@include('includes.header')

<!-- Mission & Vision -->
<section class="benefits">
    <div class="container">
        <h2 class="text-center mb-4" data-aos="fade-down" data-aos-duration="1000">Наша миссия</h2>
        <p class="lead text-center mb-5" data-aos="fade-up" data-aos-duration="1000">
            Мы стремимся дать каждому студенту глубокие практические знания и навыки,
            которые открывают двери в мир IT-карьеры.
        </p>
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-right" data-aos-duration="1000">
                <div class="benefit">
                    <i class="icon fs-1">💡</i>
                    <h3 class="mt-3">Инновации</h3>
                    <p>Используем современные технологии и методики обучения.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-duration="1000">
                <div class="benefit">
                    <i class="icon fs-1">🤝</i>
                    <h3 class="mt-3">Поддержка</h3>
                    <p>Мы сопровождаем студентов на каждом этапе обучения.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-left" data-aos-duration="1000">
                <div class="benefit">
                    <i class="icon fs-1">🚀</i>
                    <h3 class="mt-3">Результат</h3>
                    <p>Готовим специалистов, востребованных на рынке труда.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline: как мы росли -->
<section class="how-it-works py-5">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up" data-aos-duration="1000">Наша история</h2>
        <div class="row">
            <div class="col-md-3" data-aos="flip-left" data-aos-duration="800">
                <div class="step">
                    <i class="icon fs-1">2018</i>
                    <h4>Основание</h4>
                    <p>Запустили первый курс по Python.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="flip-left" data-aos-duration="1200">
                <div class="step">
                    <i class="icon fs-1">2019</i>
                    <h4>Расширение</h4>
                    <p>Добавили курсы по веб-разработке.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="flip-left" data-aos-duration="1600">
                <div class="step">
                    <i class="icon fs-1">2021</i>
                    <h4>Онлайн-платформа</h4>
                    <p>Запустили собственную LMS для дистанционного обучения.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="flip-left" data-aos-duration="2000">
                <div class="step">
                    <i class="icon fs-1">2023</i>
                    <h4>Партнёрства</h4>
                    <p>Сотрудничаем с ведущими IT-компаниями.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Team -->
<section class="our-instructors">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up" data-aos-duration="1000">Наша команда</h2>
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="zoom-in-right" data-aos-duration="1200">
                <div class="instructor">
                    <img src="https://akbflash.ru/files/images/cache/placeHolder/placeHolder.png" alt="Игорь Ковалёв" class="rounded-circle mb-3">
                    <h4>Игорь Ковалёв</h4>
                    <p>Основатель и CEO</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-duration="1400">
                <div class="instructor">
                    <img src="https://akbflash.ru/files/images/cache/placeHolder/placeHolder.png" alt="Елена Смирнова" class="rounded-circle mb-3">
                    <h4>Елена Смирнова</h4>
                    <p>Директор по обучению</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="zoom-in-left" data-aos-duration="1600">
                <div class="instructor">
                    <img src="https://akbflash.ru/files/images/cache/placeHolder/placeHolder.png" alt="Максим Иванов" class="rounded-circle mb-3">
                    <h4>Максим Иванов</h4>
                    <p>Технический директор</p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('includes.footer')

<!-- AOS Animation Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"
        integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/Animation.js') }}"></script>

</body>

</html>
