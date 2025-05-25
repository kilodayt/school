@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать теоретический материал и проверки</h1>

        <form method="POST" action="{{ route('admin.lessons.details.update', [
            'course_id' => $lesson->course_id,
            'lesson_id' => $lesson->lesson_id
        ]) }}">
            @csrf
            @method('PUT')

            {{-- Теория --}}
            <div class="mb-3">
                <label class="form-label">Теория 1</label>
                <textarea name="theory_1" class="form-control" rows="4">{{ old('theory_1', $lessonDetails->theory_1) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Теория 2</label>
                <textarea name="theory_2" class="form-control" rows="4">{{ old('theory_2', $lessonDetails->theory_2) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Теория 3</label>
                <textarea name="theory_3" class="form-control" rows="4">{{ old('theory_3', $lessonDetails->theory_3) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Упражнение</label>
                <textarea name="exessize" class="form-control" rows="4">{{ old('exessize', $lessonDetails->exessize) }}</textarea>
            </div>

            <hr>

            {{-- Проверки required/forbidden --}}
            <h3>Проверки кода</h3>

            @php
                // Декодируем JSON в строку через запятую
                $requiredList  = old('required')  ?? implode(',', $lessonCheck->required ?? []);
                $forbiddenList = old('forbidden') ?? implode(',', $lessonCheck->forbidden ?? []);
            @endphp

            <div class="mb-3">
                <label class="form-label">Обязательные ключевые слова (<code>required</code>)</label>
                <input
                    type="text"
                    name="required"
                    class="form-control"
                    value="{{ $requiredList }}"
                    placeholder="Введите через запятую: print,if,elif"
                >
                <small class="form-text text-muted">
                    Список слов или символов, которые должны присутствовать в коде.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Запрещённые ключевые слова (<code>forbidden</code>)</label>
                <input
                    type="text"
                    name="forbidden"
                    class="form-control"
                    value="{{ $forbiddenList }}"
                    placeholder="Введите через запятую: system,exec"
                >
                <small class="form-text text-muted">
                    Список слов или символов, которые не должны присутствовать в коде.
                </small>
            </div>

            <button type="submit" class="btn btn-success">Сохранить изменения</button>
            <a href="{{ route('admin.lessons.edit', ['course_id'=>$lesson->course_id,'lesson_id'=>$lesson->lesson_id]) }}"
               class="btn btn-secondary ms-2">
                Отмена
            </a>
        </form>
    </div>
@endsection
