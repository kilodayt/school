<!doctype html>
<html lang="en">

<head>
    @include('includes.head')
    <link rel="stylesheet" href="{{ asset('css/course/course.css') }}">
    <link rel="stylesheet" href="{{ asset('css/course/course-mobile.css') }}">
    <title>Наши курсы</title>
</head>

<title>Наши курсы</title>

<body>

@include('includes.header')

<section class="featured-courses">
    <div class="container">
        <h2>Наши курсы</h2>
        @foreach($courses as $language => $languageCourses)
            <div class="language-group">
                <h3>{{ $language }}</h3>
                <div class="courses-list">
                    @foreach($languageCourses as $course)
                        <div class="course">
                            <img src="{{ $course->image }}" alt="{{ $course->title }}">
                            <h3>{{ $course->title }}</h3>
                            <p>{{ $course->description }}</p>
                            <a href="{{ route('courses.show', ['id' => $course->id]) }}" class="btn-secondary">Узнать больше</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

@include('includes.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('js/Animation.js') }}"></script>

</body>
</html>

