@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Добавить новый курс</h1>

        <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Язык программирования</label>
                <input type="text" name="language" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Название курса</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Описание курса</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Изображение курса</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-success">Создать курс</button>
        </form>

        <a href="{{ route('admin.courses') }}" class="btn btn-secondary mt-3">Назад</a>
    </div>
@endsection
