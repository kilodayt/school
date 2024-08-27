<!doctype html>
<html lang="en">

<head>
    @include('includes.head')
    <title>Наши курсы</title>
</head>

<title>Наши курсы</title>
<body>

@include('includes.header')

<section class="featured-courses">
    <div class="container">
        <h2>Наши курсы</h2>
        <div class="courses-list">
            @foreach($courses as $course)
                <div class="course">
                    <img src="{{ $course->image }}" alt="{{ $course->title }}">
                    <h3>{{ $course->title }}</h3>
                    <p>{{ $course->description }}</p>
                    <a href="{{ route('courses.show', ['id' => $course->id]) }}" class="btn-secondary">Узнать больше</a>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('includes.footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/Animation.js') }}"></script>
</body>
</html>

