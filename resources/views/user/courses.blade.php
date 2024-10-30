<!doctype html>
<html lang="en">

<head>
    @include('includes.head')
    <title>Курсы</title>
</head>

<body>
@include('includes.header')
<h1>Courses of {{ $user->name }}</h1>

@if ($courses->isEmpty())
    <p>No courses available for this user.</p>
@else
    <ul>
        @foreach ($courses as $course)
            <li>{{ $course->description}}</li>
        @endforeach
    </ul>
@endif

@include('includes.footer')
</body>

</html>
