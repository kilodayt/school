<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Progress;

class ProgressController extends Controller
{
    public function updateProgress(Request $request)
    {
        $userId = Auth::id();
        $lessonId = $request->input('lesson_id');

        // Проверяем, существует ли уже запись для этого урока
        $progress = Progress::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();

        // Если записи нет, создаем новую
        if (!$progress) {
            Progress::create([
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'status' => 'completed', // например, статус выполнения
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}
