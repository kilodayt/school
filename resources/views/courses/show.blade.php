<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Добавьте это в <head> вашего шаблона -->


    <title>{{ $course->description }}</title>
    @include('includes.head')
    <link rel="stylesheet" href="{{ asset('css/course.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast-message.css') }}">
</head>
<body>

@include('includes.header')

<section class="featured-courses">
    <div class="container">

        <!-- Изображение курса -->
        <img src="{{ $course->image }}" alt="{{ $course->name }}" class="course-image">

        <h2 class="course-h2">Уроки курса</h2>

        <!-- Карусель уроков -->
        <div id="lessonsCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($course->lessons->chunk(1) as $chunk)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="row">
                            @foreach($chunk as $lesson)
                                <div class="col-md-4">
                                    <div class="course-li">
                                        <h3 class="course-h3">{{ $lesson->title }}</h3>
                                        <p class="course-p">{!! nl2br(e($lesson->content)) !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#lessonsCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#lessonsCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        @auth
            <!-- Если пользователь авторизован, ссылка ведет к урокам -->
            <a href="{{ route('lessons.index', ['course_id' => $course->id]) }}" class="btn-secondary">Изучать</a>
        @else
            <!-- Если пользователь не авторизован, показываем всплывающее сообщение -->
            <a href="javascript:void(0);" class="btn-secondary" onclick="showAuthAlert()">Изучать</a>
        @endauth

        <!-- Всплывающее сообщение -->
        <div id="authToast" class="toast-message">
            <p>Пожалуйста, авторизуйтесь, чтобы продолжить изучение курса.</p>
        </div>

        <!-- JavaScript -->
        <script>
            function showAuthAlert() {
                var toastEl = document.getElementById('authToast');
                toastEl.classList.add('show'); // Показываем Toast

                // Автоматическое скрытие через 3 секунды
                setTimeout(function() {
                    toastEl.classList.remove('show');
                }, 3000);
            }
        </script>

    </div>
</section>

@include('includes.footer')

<!-- Подключение Bootstrap JS и зависимостей -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
