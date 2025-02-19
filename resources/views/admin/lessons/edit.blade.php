@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать урок</h1>

        <form method="POST"
              action="{{ route('admin.lessons.update', ['course_id' => $lesson->course_id, 'lesson_id' => $lesson->lesson_id]) }}">
            @csrf

            <input type="hidden" name="course_id" value="{{ $lesson->course_id }}">
            <input type="hidden" name="lesson_id" value="{{ $lesson->lesson_id }}">

            <div class="mb-3">
                <label class="form-label">Название урока</label>
                <input type="text" name="title"
                       value="{{ old('title', $lesson->title) }}"
                       class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Содержание урока</label>
                <textarea name="content" class="form-control" required>{{ old('content', $lesson->content) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">
                Сохранить изменения
            </button>
        </form>

        <div class="mt-3">
            <a href="{{ route('admin.lessons.details.edit', ['course_id' => $lesson->course_id, 'lesson_id' => $lesson->lesson_id]) }}"
               class="btn btn-warning">
                Редактировать детали
            </a>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.courses.edit', $lesson->course_id) }}"
               class="btn btn-secondary">
                Назад к курсу
            </a>
        </div>
    </div>
@endsection
