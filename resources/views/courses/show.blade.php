<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $course->description }}</title>
    @include('includes.head')
    <link rel="stylesheet" href="{{ asset('css/toast-message.css') }}">
    <link rel="stylesheet" href="{{ asset('css/course/course_show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/course/course_show-mobile.css') }}">
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
                        <div class="row" style="margin-left: -50px; margin-right: -50px;">
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
            @if ($hasCourse)
                <!-- Если у пользователя есть доступ к курсу -->
                <a href="{{ route('lessons.index', ['course_id' => $course->id]) }}" class="btn-secondary">Изучать</a>
            @else
                <!-- Если у пользователя нет доступа к курсу -->
                <a href="javascript:void(0);" class="btn-secondary" onclick="showAuthAlert('accessToast')">Изучать</a>
            @endif
        @else
            <!-- Если пользователь не авторизован -->
            <a href="javascript:void(0);" class="btn-secondary" onclick="showAuthAlert('authToast')">Изучать</a>
        @endauth


        <!-- Всплывающие сообщения -->
        <div id="authToast" class="toast-message">
            <p>Пожалуйста, авторизуйтесь, чтобы продолжить изучение курса.</p>
        </div>

        <div id="accessToast" class="toast-message">
            <p>Пожалуйста, зарегестрируйтесь на этот курс для изучения.</p>
        </div>

        <!-- JavaScript -->
        <script>
            function showAuthAlert(message) {
                var toastEl = document.getElementById(message);
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
