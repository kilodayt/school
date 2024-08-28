<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Уроки</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
    @include('includes.head')
    <style>

        /* General Styles */
        h3 {
            color: #1a202c;
        }

        .container {
            width: 80%;
            margin-top: 50px;
            margin-bottom: 50px;
            padding: 20px;

        }

        .lessons-list {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .lesson-item {
            display: block;
            padding: 20px;
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
            font-weight: normal;
            display: block;
        }

        .lesson-item a:hover {
            color: #007bff;
            font-weight: bold;
        }
    </style>

</head>
<body>

@include('includes.header')

<div class="container">
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
