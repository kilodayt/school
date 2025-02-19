<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        $users = User::all();
        $lessons = Lesson::all();

        return view('admin.dashboard', compact('courses', 'users', 'lessons'));
    }
}

