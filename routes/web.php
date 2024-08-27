<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});


Route::get('/about', function () {
    return view('about');
});

Route::get('/contacts', function () {
    return view('contacts');
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{id}/learn', [CourseController::class, 'learn'])->name('courses.learn');
Route::get('/course/{course_id}/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/course/{course_id}/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');
Auth::routes();

