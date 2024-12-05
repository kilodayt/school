<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
    <title>Уроки</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="stylesheet" href="{{ asset('css/lessons/lessons-show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lessons/lessons-mobile.css') }}">
</head>

<body>

@include('includes.header')

<div class="progress-bar">
    <h3>Прогресс: {{ $completedLessonsCount }} из {{ $totalLessons }} уроков завершено</h3>
    <div class="progress">
        <div class="progress-completed" style="width: {{ ($completedLessonsCount / $totalLessons) * 100 }}%;"></div>
    </div>
</div>

<div class="lessons-container">
    <!-- Список уроков -->
    <div class="lessons-list">
        <h3>Уроки</h3>
        @foreach($lessons as $lessonItem)
            <div class="lesson-item {{ in_array($lessonItem->id, $completedLessons) ? 'completed' : '' }}">
                <a href="{{ route('lessons.show', ['course_id' => $course->id, 'id' => $lessonItem->lesson_id]) }}">
                    {{ request()->is('lessons/'.$lessonItem->lesson_id) ? 'active' : '' }}
                    {{ $lessonItem->title }}
                </a>
            </div>
        @endforeach
    </div>
</div>

@include('includes.footer')

</body>

</html>
