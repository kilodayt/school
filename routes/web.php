<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PythonCompilerController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ScheduleController;

// Главная
Route::get('/', function () {
    return view('welcome');
});

// Авторизация
Auth::routes(); // Все маршруты аутентификации

// О нас
Route::get('/about', function () {
    return view('about');
});

// Контакты
Route::get('/contacts', function () {
    return view('contacts');
});

// Блог
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Курсы
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

// Уроки
Route::middleware('auth')->group(function () {
    Route::get('/course/{course_id}/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/course/{course_id}/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/run-python', [PythonCompilerController::class, 'execute'])->name('run-python');
});

// ЛК пользоватля
Route::get('/user', function () {
    return view('user/profile');
})->name('user.user');
Route::get('/user/{id}/courses', [UserController::class, 'showCourses'])->name('user.courses');

// Обновление прогресса
Route::post('/update-progress', [ProgressController::class, 'updateProgress'])->name('updateProgress');

// Администрирование сайта
Route::get('/add-user', [UserController::class, 'showForm'])->name('users.add')->middleware('role:admin');;
Route::post('/add-user', [UserController::class, 'storeUser'])->name('users.store')->middleware('role:admin');;
Route::post('/assign-course', [UserController::class, 'assignCourse'])->name('users.assignCourse')->middleware('role:admin');;

// routes/web.php
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('teacher.schedule');
    Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
});

