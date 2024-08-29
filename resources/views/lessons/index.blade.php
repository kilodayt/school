<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
    <title>Уроки</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="stylesheet" href="{{ asset('css/lessons/lessons-index.css') }}">
</head>

<body>

@include('includes.header')

<div class="lessons-container">
    <div class="lessons-list">
        <h3>Уроки</h3>
        @foreach($lessons as $lesson)
            <div class="lesson-item">
                <a href="{{ route('lessons.show', ['course_id' => $course->id, 'id' => $lesson->lesson_id]) }}">
                    {{ request()->is('lessons/'.$lesson->lesson_id) ? 'active' : '' }}
                    {{ $lesson->title }}
                </a>
            </div>
        @endforeach
    </div>
</div>

@include('includes.footer')

</body>

</html>
