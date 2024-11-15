<!doctype html>
<html lang="en">

<head>
    @include('includes.head')
    <link rel="stylesheet" href="{{ asset('css/course/course.css') }}">
    <link rel="stylesheet" href="{{ asset('css/course/course-mobile.css') }}">
    <title>Курсы</title>
</head>

<style>
    .username {
        text-align: center;
        margin-top: 40px;
    }

    .btn-secondary {
        display: block;
        margin: 10px auto;
        text-align: center;
    }
</style>

<body>
@include('includes.header')
<h1 class="username">{{ $user->name }}</h1>
@if ($courses->isEmpty())
    <p>У вас пока что нет курсов.</p>
@else
    <section class="featured-courses">
        <div class="container">
            <h2>Твои курсы</h2>
            <div class="courses-list">
                @foreach($courses as $course)
                    <div class="course">
                        <img src="{{ $course->image }}" alt="{{ $course->title }}">
                        <h3>{{ $course->title }}</h3>
                        <p>{{ $course->description }}</p>
                        <a href="{{ route('lessons.index', ['course_id' => $course->id]) }}" class="btn-secondary">Изучать</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@include('includes.footer')
</body>

</html>

