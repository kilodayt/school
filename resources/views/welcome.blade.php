<!DOCTYPE html>
<html lang="en"><!-- Устанавливаем язык страницы -->
<head>
    <!-- Подключаем общие <head> элементы (мета-теги, стили, скрипты) -->
    @include('includes.head')
    <link rel="stylesheet" href="{{ asset('css/pages/welcome.css') }}">
    <!-- Заголовок вкладки браузера -->
    <title>Онлайн школа программирования</title>
</head>
<body>

<!-- Включаем шапку сайта (логотип, меню навигации и т.п.) -->
@include('includes.header')

<!-- Hero Section: главный баннер на главной странице -->
<section class="hero">
    <div class="container">
        <h1>Изучайте программирование с нуля</h1>
        <p>Начните свою карьеру в IT с нашими профессиональными курсами</p>
        <!-- Кнопка-запись на курс (пока заглушка "#!") -->
        <a href="#!" class="btn-primary">Записаться на курс</a>
    </div>
</section>

<!-- Benefits: блок преимуществ школы -->
<section class="benefits">
    <div class="container">
        <h1>Почему именно мы?</h1>
        <!-- Преимущество 1: практические задания -->
        <div data-aos="fade-down" data-aos-easing="linear" data-aos-duration="4500" class="benefit">
            <i class="icon">🎓</i>
            <h3>Практические задания</h3>
            <p>Учитесь на реальных примерах и задачах.</p>
        </div>
        <!-- Преимущество 2: опытные наставники -->
        <div data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500" class="benefit">
            <i class="icon">👨‍🏫</i>
            <h3>Опытные наставники</h3>
            <p>Наши преподаватели — профессионалы с многолетним опытом.</p>
        </div>
        <!-- Преимущество 3: круглосуточный доступ -->
        <div data-aos="fade-down" data-aos-easing="linear" data-aos-duration="3000" class="benefit">
            <i class="icon">🕒</i>
            <h3>Доступ к материалам 24/7</h3>
            <p>Учитесь в любое удобное для вас время.</p>
        </div>
    </div>
</section>

<!-- Featured Courses: блок популярных курсов -->
<section class="featured-courses">
    <div class="container">
        <h2>Популярные курсы</h2>
        <div class="courses-list" data-aos="fade-up" data-aos-duration="2000">
            @foreach($courses->take(2) as $course)
                <!-- Каждый курс -->
                <div class="course">
                    <!-- Логотип/изображение курса -->
                    <img src="{{ asset($course->image) }}" alt="{{ $course->title }}">
                    <!-- Название курса -->
                    <h3>{{ $course->title }}</h3>
                    <!-- Краткое описание -->
                    <p>{{ $course->subtitle }}</p>
                    <!-- Ссылка на страницу курса -->
                    <a href="{{ route('courses.show', ['id' => $course->id]) }}" class="btn-secondary">
                        Подробнее
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How It Works: как устроен процесс обучения -->
<section class="how-it-works">
    <div class="container">
        <h2>Как это работает</h2>
        <!-- Шаг 1: регистрация -->
        <div data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="500" class="step">
            <i class="icon">1</i>
            <h3>Регистрация</h3>
            <p>Зарегистрируйтесь на нашем сайте.</p>
        </div>
        <!-- Шаг 2: выбор курса -->
        <div data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="1300" class="step">
            <i class="icon">2</i>
            <h3>Выбор курса</h3>
            <p>Выберите интересующий вас курс.</p>
        </div>
        <!-- Шаг 3: обучение -->
        <div data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="2100" class="step">
            <i class="icon">3</i>
            <h3>Обучение</h3>
            <p>Начните обучение по выбранному курсу.</p>
        </div>
        <!-- Шаг 4: сертификат -->
        <div data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="2900" class="step">
            <i class="icon">4</i>
            <h3>Получение сертификата</h3>
            <p>Получите сертификат по окончании курса.</p>
        </div>
    </div>
</section>

<!-- Testimonials: отзывы студентов -->
<section class="testimonials">
    <div class="container">
        <h2>Отзывы студентов</h2>
        <!-- Отзыв 1 -->
        <div data-aos="zoom-in-right" data-aos-duration="2000" class="testimonial">
            <p>"Отличные курсы! Много практики и интересных заданий."</p>
            <h4>Иван Иванов</h4>
        </div>
        <!-- Отзыв 2 -->
        <div data-aos="zoom-in-left" data-aos-duration="2000" class="testimonial">
            <p>"Учителя очень отзывчивые и профессиональные."</p>
            <h4>Мария Петрова</h4>
        </div>
    </div>
</section>

<!-- Our Instructors: наши преподаватели -->
<section class="our-instructors">
    <div class="container">
        <h2>Наши преподаватели</h2>
        <!-- Преподаватель 1 -->
        <div data-aos="zoom-in-right" data-aos-duration="3000" class="instructor">
            <img src="https://akbflash.ru/files/images/cache/placeHolder/placeHolder.png" alt="Instructor 1">
            <h3>Дмитрий Смирнов</h3>
            <p>Эксперт по Python</p>
        </div>
        <!-- Преподаватель 2 -->
        <div data-aos="zoom-in-left" data-aos-duration="3000" class="instructor">
            <img src="https://akbflash.ru/files/images/cache/placeHolder/placeHolder.png" alt="Instructor 2">
            <h3>Анна Кузнецова</h3>
            <p>Специалист по веб-разработке</p>
        </div>
    </div>
</section>

<!-- Blog: последние посты блога -->
<section class="blog">
    <div class="container">
        <h2>Блог</h2>
        <!-- Пост 1 -->
        <div data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="2000" class="blog-post">
            <img src="https://akbflash.ru/files/images/cache/placeHolder/placeHolder.png" alt="Blog 1">
            <h3>Как стать веб-разработчиком</h3>
            <a href="{{ route('blog.show', ['slug' => 'kak-stat-veb-razrabotchikom']) }}" class="btn-secondary">
                Читать больше
            </a>
        </div>
        <!-- Пост 2 -->
        <div data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="2000" class="blog-post">
            <img src="https://akbflash.ru/files/images/cache/placeHolder/placeHolder.png" alt="Blog 2">
            <h3>10 советов для программистов</h3>
            <a href="{{ route('blog.show', ['slug' => '10-sovetov-dlya-programmistov']) }}" class="btn-secondary">
                Читать больше
            </a>
        </div>
        <!-- Пост 3 -->
        <div data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="2000" class="blog-post">
            <img src="https://akbflash.ru/files/images/cache/placeHolder/placeHolder.png" alt="Blog 3">
            <h3>Обзор языков программирования</h3>
            <a href="{{ route('blog.show', ['slug' => 'obzor-yazykov-programmirovaniya']) }}" class="btn-secondary">
                Читать больше
            </a>
        </div>
    </div>
</section>

<!-- Footer: включаем нижний колонтитул сайта -->
@include('includes.footer')

<!-- Подключение скрипта AOS для анимаций -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"
        integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Ваш кастомный скрипт анимации -->
<script src="{{ asset('js/Animation.js') }}"></script>
</body>
</html>
