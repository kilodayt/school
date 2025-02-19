@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Добавить новый урок</h1>

        <form method="POST" action="{{ route('admin.lessons.store', ['course_id' => $course->id]) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">ID урока</label>
                <input type="number" name="lesson_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Название урока</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Содержание урока</label>
                <textarea name="content" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Теория 1</label>
                <textarea name="theory_1" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Теория 2</label>
                <textarea name="theory_2" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Теория 3</label>
                <textarea name="theory_3" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Упражнение</label>
                <textarea name="exessize" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Создать урок</button>
        </form>

        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-secondary mt-3">Назад к курсу</a>
    </div>
@endsection
