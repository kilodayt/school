<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить пользователя</title>
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
</head>
<body>
<div class="container">
    <h1>Добавить занятие</h1>

    <form action="{{ route('schedule.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="date">Дата</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="start_time">Время начала</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end_time">Время окончания</label>
            <input type="time" name="end_time" id="end_time" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="course_id">Курс</label>
            <select name="course_id" id="course_id" class="form-control" required>
                <option value="" disabled selected>Выберите курс</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->description }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="location">Место проведения</label>
            <input type="text" name="location" id="location" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>

        <a href="{{ route('teacher.schedule') }}" class="btn btn-primary">Вернуться к расписанию</a>

    </form>
</div>

</body>
</html>
