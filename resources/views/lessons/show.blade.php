<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
    <title>{{ $lesson->title }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="stylesheet" href="{{ asset('css/lessons/lessons-show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lessons/lessons-mobile.css') }}">
</head>

<body>

@include('includes.header')

<div class="lessons-container">
    <!-- Список уроков -->
    <div class="lessons-list">
        <h3>Уроки</h3>
        @foreach($lessons as $lessonItem)
            <div class="lesson-item">
                <a href="{{ route('lessons.show', ['course_id' => $course->id, 'id' => $lessonItem->lesson_id]) }}">
                    {{ request()->is('lessons/'.$lessonItem->lesson_id) ? 'active' : '' }}
                    {{ $lessonItem->title }}
                </a>
            </div>
        @endforeach
    </div>

    <!-- Контент выбранного урока -->
    <div class="lesson-content">
        <h2>{{ $lesson->title }}</h2>

        <!-- Навигация по разделам контента -->
        <div class="nav-links">
            <a href="#theory1">Теория 1</a>
            <a href="#theory2">Теория 2</a>
            <a href="#theory3">Теория 3</a>
            <a href="#exercise">Упражнение</a>
        </div>

        <!-- Секции контента -->
        <div id="theory1" class="content-section">
            <h3>Теория 1</h3>
            <p>{!! $theory1Text !!}</p>
        </div>
        <div id="theory2" class="content-section">
            <h3>Теория 2</h3>
            <p>{!! $theory2Text !!}</p>
        </div>
        <div id="theory3" class="content-section">
            <h3>Теория 3</h3>
            <p>{!! $theory3Text !!}</p>
        </div>
        <div id="exercise" class="content-section">
            <h3>Упражнение</h3>
            <p>{!! $exessizeText !!}</p>
        </div>
    </div>
</div>

@include('includes.footer')

<script>
    // JavaScript для управления видимостью секций контента
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.nav-links a');
        const sections = document.querySelectorAll('.content-section');

        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);

                sections.forEach(section => {
                    if (section.id === targetId) {
                        section.classList.add('active');
                    } else {
                        section.classList.remove('active');
                    }
                });
            });
        });
    });
</script>

</body>

</html>
