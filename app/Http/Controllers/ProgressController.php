<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Progress;


class ProgressController extends Controller
{
    /** Обновление прогресса пользователя */
    public function updateProgress(Request $request): JsonResponse
    {
        try {
            // Валидация входных данных
            $data = $request->validate([
                'course_id' => 'required|integer|exists:courses,id',
                'lesson_id' => 'required|integer',
                'status'    => 'sometimes|string|in:pending,in_progress,completed',
            ]);

            $userId   = Auth::id();
            $courseId = $data['course_id'];
            $lessonId = $data['lesson_id'];
            $status   = $data['status'] ?? 'completed';

            // Ищем или создаём запись прогресса по составному ключу
            $progress = Progress::firstOrNew([
                'course_id' => $courseId,
                'user_id'   => $userId,
                'lesson_id' => $lessonId,
            ]);

            // Обновляем статус и сохраняем (поставит updated_at автоматически)
            $progress->status = $status;
            $progress->save();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Не удалось обновить прогресс.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
