@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Управление курсами</h1>
        <a href="{{ route('users.add') }}" class="btn btn-primary">Добавить курс</a>

        <ul class="list-group mt-3">
            @foreach($courses as $course)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $course->title }}</span>
                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-warning">Редактировать</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
