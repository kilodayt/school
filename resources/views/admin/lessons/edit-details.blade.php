@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать теоретический материал</h1>

        <form method="POST" action="{{ route('admin.lessons.details.update', ['course_id' => $lesson->course_id, 'lesson_id' => $lesson->lesson_id]) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Теория 1</label>
                <textarea name="theory_1" class="form-control">{{ old('theory_1', $lessonDetails->theory_1) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Теория 2</label>
                <textarea name="theory_2" class="form-control">{{ old('theory_2', $lessonDetails->theory_2) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Теория 3</label>
                <textarea name="theory_3" class="form-control">{{ old('theory_3', $lessonDetails->theory_3) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Упражнение</label>
                <textarea name="exessize" class="form-control">{{ old('exessize', $lessonDetails->exessize) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>

        <a href="{{ route('admin.lessons.edit', ['course_id' => $lesson->course_id, 'lesson_id' => $lesson->lesson_id]) }}" class="btn btn-secondary mt-3">
            Назад к уроку
        </a>
    </div>
@endsection
