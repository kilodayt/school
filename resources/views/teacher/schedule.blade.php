<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить пользователя</title>
    @include('includes.head')
    <link rel="stylesheet" href="{{ asset('css/teacher/table.css') }}">
</head>
<body>
@include('includes.header')
<table class="table">
    <thead>
    <tr>
        <th>Дата</th>
        <th>Время</th>
        <th>Курс</th>
        <th>Место</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    @foreach($schedules as $schedule)
        <tr>
            <td>{{ $schedule->date }}</td>
            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
            <td>{{ $schedule->course->description }}</td>
            <td>{{ $schedule->location }}</td>
            <td>
                <form action="{{ route('schedule.destroy', $schedule->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Удалить</button>
                </form>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
<a href="{{ route('schedule.create') }}" class="btn-secondary1">Добавить</a>
@include('includes.footer')
</body>
</html>
