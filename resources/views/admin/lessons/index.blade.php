@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Уроки курса "{{ $course->title }}"</h1>

        <a href="{{ route('admin.lessons.create', ['course_id' => $course->id]) }}" class="btn btn-primary mb-3">Добавить урок</a>

        <ul class="list-group">
            @foreach($course->lessons as $lesson)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $lesson->title }}</span>
                    <div>
                        <a href="{{ route('admin.lessons.edit', ['course_id' => $course->id, 'lesson_id' => $lesson->id]) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form method="POST" action="{{ route('admin.lessons.destroy', ['course_id' => $course->id, 'lesson_id' => $lesson->lesson_id]) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-secondary mt-3">Назад к курсу</a>
    </div>
@endsection
