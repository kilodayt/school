<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;


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

// Курсы
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

// Уроки
Route::get('/course/{course_id}/lessons', [LessonController::class, 'index'])->name('lessons.index')->middleware('auth');
Route::get('/course/{course_id}/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show')->middleware('auth');

// ЛК пользоватля
Route::get('/profile', function () {
    return view('user/profile');
})->name('user.profile');

Route::get('/user_courses', function () {
    return view('user/courses');
})->name('user.courses');

Auth::routes();

