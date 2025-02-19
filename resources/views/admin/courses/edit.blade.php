@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактирование курса</h1>

        <form method="POST" action="{{ route('admin.courses.update', $course) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Название курса</label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control">{{ old('description', $course->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>

        <h3 class="mt-4">Уроки</h3>

        <!-- 🔥 Проверяем, сколько уроков уже есть -->
        @if($course->lessons->count() < 20)
            <a href="{{ route('admin.lessons.create', ['course_id' => $course->id]) }}" class="btn btn-primary mb-3">
                Добавить новый урок
            </a>
        @endif

        <ul class="list-group">
            @foreach($course->lessons as $lesson)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $lesson->title }}</span>
                    <a href="{{ route('admin.lessons.edit', ['course_id' => $course->id, 'lesson_id' => $lesson->lesson_id]) }}" class="btn btn-warning">
                        Редактировать
                    </a>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">Назад</a>
    </div>
@endsection
