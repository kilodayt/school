<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PythonCompilerController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\BlogController;

// Главная
Route::get('/', function () {
    return view('welcome');
});

// Авторизация
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::group(['middleware' => 'store.previous.url'], function () {
    Auth::routes(); // Все стандартные маршруты аутентификации
});

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
Route::get('/course/{course_id}/lessons', [LessonController::class, 'index'])->name('lessons.index')->middleware('auth');
Route::get('/course/{course_id}/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show')->middleware('auth');


// ЛК пользоватля
Route::get('/user', function () {
    return view('user/profile');
})->name('user.user');
Route::get('/user/{id}/courses', [UserController::class, 'showCourses'])->name('user.courses');

// Выполнение кода и проверка выходных данных
Route::post('/run-python', [PythonCompilerController::class, 'execute'])->name('run-python');

// Обновление прогресса
Route::post('/update-progress', [ProgressController::class, 'updateProgress'])->name('updateProgress');



Auth::routes();

