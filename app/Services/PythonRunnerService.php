<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Symfony\Component\Process\Process;

class PythonRunnerService
{
    protected array $lessonChecks = [
        1 => ['required' => ['print'], 'forbidden' => []],
        2 => ['required' => ['for', 'range'], 'forbidden' => ['while']],
        // ...
    ];

    public function runCode(string $code, int $lessonId): JsonResponse
    {
        // 1. Проверка dangerous-кода
        if ($this->hasDangerousFunctions($code)) {
            return response()->json(['error' => 'Использование опасных функций запрещено.'], 400);
        }

        // 2. Проверка, что $lessonId есть в $lessonChecks
        if (!isset($this->lessonChecks[$lessonId])) {
            return response()->json(['error' => 'Неверный идентификатор задания.'], 400);
        }

        // 3. Создаём временный файл
        $fileName = 'temp_code_' . uniqid() . '.py';
        $filePath = storage_path('app/'.$fileName);
        file_put_contents($filePath, $code);

        try {
            // 4. Проверка синтаксиса
            $syntaxCheckResult = $this->checkSyntax($filePath);
            if ($syntaxCheckResult !== "No syntax errors.") {
                return response()->json(['output' => $syntaxCheckResult], 400);
            }

            // 5. Проверка условий
            $checkResult = $this->checkCodeContent($code, $this->lessonChecks[$lessonId]);
            if (!$checkResult['isCorrect']) {
                return response()->json([
                    'output'   => implode("", $checkResult['errors']),
                    'isCorrect'=> false,
                    'message'  => 'Код не соответствует условиям задания. Проверьте ошибки.',
                ], 400);
            }

            // 6. Запуск Python
            $pythonPath = base_path('Python38/python.exe');
            $process = new Process([$pythonPath, $filePath]);
            $process->run();

            if (!$process->isSuccessful()) {
                $errorOutput = preg_replace('/File "(.*?)"/', 'File "main.py"', $process->getErrorOutput());
                return response()->json(['output' => $errorOutput], 400);
            }

            $output = trim($process->getOutput());
            return response()->json([
                'output' => $output,
                'isCorrect' => true,
                'message' => 'Задание выполнено правильно!',
            ]);
        } finally {
            // 7. Удаляем файл
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    private function checkSyntax(string $filePath): string
    {
        $command = escapeshellcmd("python -m py_compile $filePath");
        $output = shell_exec($command);

        if (str_contains($output, 'Error')) {
            return "Syntax error found!";
        }

        return "No syntax errors.";
    }

    private function checkCodeContent(string $code, array $conditions): array
    {
        $errors = [];
        $isCorrect = true;

        // Проверка required
        foreach ($conditions['required'] as $keyword) {
            if (!str_contains($code, $keyword)) {
                $errors[] = "Ключевое слово '$keyword' не найдено.\n";
                $isCorrect = false;
            }
        }
        // Проверка forbidden
        foreach ($conditions['forbidden'] as $keyword) {
            if (str_contains($code, $keyword)) {
                $errors[] = "Ключевое слово '$keyword' запрещено.\n";
                $isCorrect = false;
            }
        }

        return ['isCorrect' => $isCorrect, 'errors' => $errors];
    }

    private function hasDangerousFunctions(string $code): bool
    {
        // Список опасных слов
        $dangerousKeywords = [
            'os.system', 'subprocess', 'eval', 'exec', 'open', '__import__',
            'webbrowser', 'compile', 'globals', 'locals', 'delattr', 'setattr',
            'getattr', 'vars', 'dir', 'os.popen', 'shutil', 'sys.modules',
            'socket', 'ctypes', 'multiprocessing', 'threading', 'pickle',
            'marshal', 'base64', 'tempfile', 'jsonpickle',
        ];
        $dangerousModules = ['os', 'sys', 'subprocess', 'shutil', 'socket', 'ctypes', 'pickle', 'multiprocessing'];

        foreach ($dangerousKeywords as $keyword) {
            if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/', $code)) {
                return true;
            }
        }
        foreach ($dangerousModules as $module) {
            // Проверяем import
            $importRegex = '/\b(import|from)\s+' . preg_quote($module, '/') . '\b/';
            if (preg_match($importRegex, $code)) {
                return true;
            }
        }
        // Запрет на абсолютные пути и eval-обход
        if (preg_match('/[\'"]\/.*?[\'"]/', $code)) {
            return true;
        }
        if (preg_match('/e.*v.*a.*l/', $code)) {
            return true;
        }
        return false;
    }
}
