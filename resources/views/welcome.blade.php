<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head')
    <title>Онлайн школа программирования</title>
</head>
<body>

<!-- Header -->
@include('includes.header')

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Изучайте программирование с нуля</h1>
        <p>Начните свою карьеру в IT с нашими профессиональными курсами</p>
        <a href="#!" class="btn-primary">Записаться на курс</a>
    </div>
</section>

<!-- Benefits -->
<section class="benefits">
    <div class="container">
        <h1>Почему именно мы?</h1>
        <div data-aos="fade-down"
             data-aos-easing="linear"
             data-aos-duration="4500"
             class="benefit">
            <i class="icon">🎓</i>
            <h3>Практические задания</h3>
            <p>Учитесь на реальных примерах и задачах.</p>
        </div>
        <div data-aos="fade-down"
             data-aos-easing="linear"
             data-aos-duration="1500"
             class="benefit">
            <i class="icon">👨‍🏫</i>
            <h3>Опытные наставники</h3>
            <p>Наши преподаватели - профессионалы с многолетним опытом.</p>
        </div>
        <div data-aos="fade-down"
             data-aos-easing="linear"
             data-aos-duration="3000"
             class="benefit">
            <i class="icon">🕒</i>
            <h3>Доступ к материалам 24/7</h3>
            <p>Учитесь в любое удобное для вас время.</p>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<section class="featured-courses">
    <div class="container">
        <h2>Популярные курсы</h2>
        <div data-aos="fade-up" data-aos-duration="2000" class="course">
            <img src="https://img.freepik.com/free-photo/programming-background-with-person-working-with-codes-computer_23-2150010129.jpg?t=st=1724270693~exp=1724274293~hmac=2ec03eda5dccd8af94f62083f5a32a4403149258c168be86eb2ade136a05da33&w=1380" alt="https://via.placeholder.com/300">
            <h3>Python Junior</h3>
            <p>Основы языка Python для начинающих </p>
            <a href="{{ route('courses.show', ['id' => 1]) }}" class="btn-secondary">Подробнее</a>
        </div>
        <div data-aos="fade-up" data-aos-duration="2000" class="course">
            <img src="https://img.freepik.com/free-photo/programming-background-with-person-working-with-codes-on-computer_23-2150010125.jpg?w=900&t=st=1724270004~exp=1724270604~hmac=6bc29bad9c667c6f6032904b6cdcc06727d63e5471ccfc5b434a3e072dccc151" alt="https://via.placeholder.com/300">
            <h3>Python Medium</h3>
            <p>Более продвинутый курс по Python</p>
            <a href="{{ route('courses.show', ['id' => 2]) }}" class="btn-secondary">Подробнее</a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works">
    <div class="container">
        <h2>Как это работает</h2>
        <div data-aos="flip-left"
             data-aos-easing="ease-out-cubic"
             data-aos-duration="500"
             class="step">
            <i class="icon">1</i>
            <h3>Регистрация</h3>
            <p>Зарегистрируйтесь на нашем сайте.</p>
        </div>
        <div data-aos="flip-left"
             data-aos-easing="ease-out-cubic"
             data-aos-duration="1300"
             class="step">
            <i class="icon">2</i>
            <h3>Выбор курса</h3>
            <p>Выберите интересующий вас курс.</p>
        </div>
        <div data-aos="flip-left"
             data-aos-easing="ease-out-cubic"
             data-aos-duration="2100"
             class="step">
            <i class="icon">3</i>
            <h3>Обучение</h3>
            <p>Начните обучение по выбранному курсу.</p>
        </div>
        <div data-aos="flip-left"
             data-aos-easing="ease-out-cubic"
             data-aos-duration="2900"
             class="step">
            <i class="icon">4</i>
            <h3>Получение сертификата</h3>
            <p>Получите сертификат по окончании курса.</p>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials">
    <div class="container">
        <h2>Отзывы студентов</h2>
        <div data-aos="zoom-in-right" data-aos-duration="2000" class="testimonial">
            <p>"Отличные курсы! Много практики и интересных заданий."</p>
            <h4>Иван Иванов</h4>
        </div>
        <div data-aos="zoom-in-left" data-aos-duration="2000" class="testimonial">
            <p>"Учителя очень отзывчивые и профессиональные."</p>
            <h4>Мария Петрова</h4>
        </div>
    </div>
</section>

<!-- Our Instructors -->
<section class="our-instructors">
    <div class="container">
        <h2>Наши преподаватели</h2>
        <div data-aos="zoom-in-right" data-aos-duration="3000" class="instructor">
            <img src="https://via.placeholder.com/150" alt="Instructor 1">
            <h3>Дмитрий Смирнов</h3>
            <p>Эксперт по Python</p>
        </div>
        <div data-aos="zoom-in-left" data-aos-duration="3000" class="instructor">
            <img src="https://via.placeholder.com/150" alt="Instructor 2">
            <h3>Анна Кузнецова</h3>
            <p>Специалист по веб-разработке</p>
        </div>
    </div>
</section>

<!-- Blog -->
<section class="blog">
    <div class="container">
        <h2>Блог</h2>
        <div data-aos="flip-left"
             data-aos-easing="ease-out-cubic"
             data-aos-duration="2000"
             class="blog-post">
            <img src="https://via.placeholder.com/300" alt="Blog 1">
            <h3>Как стать веб-разработчиком</h3>
            <a href="{{ route('blog.show', ['slug' => 'kak-stat-veb-razrabotchikom']) }}" class="btn-secondary">Читать больше</a>
        </div>
        <div data-aos="flip-left"
             data-aos-easing="ease-out-cubic"
             data-aos-duration="2000"
             class="blog-post">
            <img src="https://via.placeholder.com/300" alt="Blog 2">
            <h3>10 советов для программистов</h3>
            <a href="{{ route('blog.show', ['slug' => '10-sovetov-dlya-programmistov']) }}" class="btn-secondary">Читать больше</a>
        </div>
        <div data-aos="flip-left"
             data-aos-easing="ease-out-cubic"
             data-aos-duration="2000"
             class="blog-post">
            <img src="https://via.placeholder.com/300" alt="Blog 3">
            <h3>Обзор языков программирования</h3>
            <a href="{{ route('blog.show', ['slug' => 'obzor-yazykov-programmirovaniya']) }}" class="btn-secondary">Читать больше</a>
        </div>
    </div>
</section>

<!-- Footer -->
@include('includes.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/Animation.js') }}"></script>
</body>
</html>
