<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lesson->title }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
    <style>
        /* General Styles */
        body {
            font-family: "Montserrat", sans-serif;
            margin: 0;
            padding: 0;
            color: #f0f0f0;
            background-color: rgba(0, 0, 51, 0.8);
            background-attachment: fixed;
        }

        h3, h2 {
            color: #1a202c;
        }

        .container {
            display: flex;
            max-width: 1200px;
            margin: 50px auto; /* Центрируем контейнер и добавляем отступы */
            padding: 20px;
            background-color: rgba(0, 0, 51, 0.7); /* Slightly more transparent for better readability */
            border-radius: 10px;
        }

        .lessons-list {
            width: 25%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            overflow-y: auto; /* Добавляем прокрутку, если содержимое списка превышает высоту */
        }

        .lesson-item {
            display: block;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .lesson-item:hover {
            background-color: #e0e0e0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .lesson-item a {
            text-decoration: none;
            color: #333;
            display: block;
        }

        .lesson-item a:hover {
            color: #007bff;
        }

        .active {
            font-weight: bold;
        }

        .lesson-content {
            width: 75%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .nav-links {
            margin-bottom: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            margin-right: 15px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

@include('includes.header')

<div class="container">
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
            <p>{{ $lessonDetails->theory_1 }}</p>
        </div>
        <div id="theory2" class="content-section">
            <h3>Теория 2</h3>
            <p>{{ $lessonDetails->theory_2 }}</p>
        </div>
        <div id="theory3" class="content-section">
            <h3>Теория 3</h3>
            <p>{{ $lessonDetails->theory_3 }}</p>
        </div>
        <div id="exercise" class="content-section">
            <h3>Упражнение</h3>
            <p>{{ $lessonDetails->exessize }}</p>
        </div>
    </div>
</div>

@include('includes.head')
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
