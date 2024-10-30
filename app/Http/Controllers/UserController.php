<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Показать курсы пользователя.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showCourses($id)
    {
        // Находим пользователя по его ID
        $user = User::findOrFail($id);

        // Получаем курсы пользователя
        $courses = $user->courses;

        // Возвращаем представление с данными
        return view('user.courses', ['user' => $user, 'courses' => $courses]);
    }
}
