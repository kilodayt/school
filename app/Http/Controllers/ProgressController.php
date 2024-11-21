<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Progress;

class ProgressController extends Controller
{
    public function updateProgress(Request $request)
    {
        try {
            // Получаем текущего пользователя
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['error' => 'Пользователь не авторизован'], 403);
            }

            // Получаем lesson_id из запроса
            $lessonId = $request->input('lesson_id');
            if (!$lessonId) {
                return response()->json(['error' => 'lesson_id не указан'], 400);
            }

            // Проверяем, существует ли запись для текущего урока
            $progress = Progress::where('user_id', $userId)
                ->where('lesson_id', $lessonId)
                ->first();

            // Если записи нет, создаем новую
            if (!$progress) {
                Progress::create([
                    'user_id' => $userId,
                    'lesson_id' => $lessonId,
                    'status' => 'completed',
                ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            // Ловим исключение и возвращаем ошибку
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
