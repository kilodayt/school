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
use App\Http\Controllers\AdminController;

use App\Models\Course;

// Главная
Route::get('/', function () {
    $courses = Course::orderBy('id')->take(2)->get();
    return view('/welcome', compact('courses'));
});

// Авторизация
Auth::routes();

// О нас
Route::view('/about', 'about')->name('about');

// Контакты
Route::view('/contacts', 'contacts')->name('contacts');

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

    // ЛК пользователя
    Route::view('/user', 'user/profile')->name('user.user');
    Route::get('/user/{id}/courses', [UserController::class, 'showCourses'])->name('user.courses');

    // Обновление прогресса
    Route::post('/update-progress', [ProgressController::class, 'updateProgress'])->name('updateProgress');
});


// Администрирование сайта
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Управление пользователями
    Route::get('/admin/users/create', [UserController::class, 'showForm'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/admin/users/assign-course', [UserController::class, 'assignCourse'])->name('admin.users.assignCourse');

    // Админ-панель
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // Управление ролями и удаление пользователей
    Route::post('/admin/users/{user}/update-role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Управление курсами
    Route::get('/admin/courses', [CourseController::class, 'index'])->name('admin.courses');
    Route::get('/admin/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/admin/courses', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::get('/admin/courses/{course}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
    Route::put('/admin/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');

    Route::delete('/admin/courses/{course}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');

    // Управление уроками
    Route::get('/admin/courses/{course_id}/lessons/create', [LessonController::class, 'create'])->name('admin.lessons.create');
    Route::post('/admin/courses/{course_id}/lessons', [LessonController::class, 'store'])->name('admin.lessons.store');
    Route::get('/admin/courses/{course_id}/lessons/{lesson_id}/edit', [LessonController::class, 'edit'])->name('admin.lessons.edit');
    Route::post('/admin/courses/{course_id}/lessons/{lesson_id}', [LessonController::class, 'update'])->name('admin.lessons.update');
    Route::delete('/admin/courses/{course_id}/lessons/{lesson_id}', [LessonController::class, 'destroy'])->name('admin.lessons.destroy');

    // Управление теоретическим материалом уроков
    Route::get('/admin/courses/{course_id}/lessons/{lesson_id}/details', [LessonController::class, 'editLessonDetails'])
        ->name('admin.lessons.details.edit');
    Route::put('/admin/courses/{course_id}/lessons/{lesson_id}/details', [LessonController::class, 'updateLessonDetails'])
        ->name('admin.lessons.details.update');
});


// Управление расписанием
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('teacher.schedule');
    Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
});
