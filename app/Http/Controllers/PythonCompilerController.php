<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExecutePythonRequest;
use App\Services\PythonRunnerService;
use Illuminate\Http\JsonResponse;

class PythonCompilerController extends Controller
{
    public function execute(ExecutePythonRequest $request, PythonRunnerService $runner): JsonResponse
    {
        try {
            $code = $request->input('code');
            $lessonId = (int) $request->input('lesson_id');

            // Запуск кода через сервис
            return $runner->runCode($code, $lessonId);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
