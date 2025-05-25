<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Symfony\Component\Process\Process;
use App\Models\LessonCheck;

class PythonRunnerService
{
    /**
     * @param  string  $code
     * @param  string  $language  // 'python', 'cpp' или 'php'
     * @param  int     $courseId
     * @param  int     $lessonId
     * @return JsonResponse
     */
    public function runCode(string $code, string $language, int $courseId, int $lessonId): JsonResponse
    {
        // 1. Проверяем, что у нас есть урок с нужной проверкой
        $lessonCheck = LessonCheck::where('course_id', $courseId)
            ->where('lesson_id', $lessonId)
            ->first();
        if (! $lessonCheck) {
            return response()->json(['error' => 'Неверный идентификатор курса или урока.'], 400);
        }

        $conditions = [
            'required'  => $lessonCheck->required  ?? [],
            'forbidden' => $lessonCheck->forbidden ?? [],
        ];

        // 2. Запрещённый код (общий для всех языков)
        if ($this->hasDangerousFunctions($code)) {
            return response()->json(['error' => 'Использование опасных функций запрещено.'], 400);
        }

        // 3. Подготовка временных путей
        $ext      = $this->extensionByLang($language);       // 'py', 'cpp', 'php'
        $baseName = 'temp_' . $language . '_' . uniqid();
        $srcPath  = storage_path("app/{$baseName}.{$ext}");
        file_put_contents($srcPath, $code);

        // Для C++ нам ещё нужен путь для бинарника
        $binPath  = $language === 'cpp'
            ? storage_path("app/{$baseName}.out")
            : null;

        try {
            // 4. Синтаксическая проверка
            $syntaxError = $this->checkSyntax($language, $srcPath, $binPath);
            if ($syntaxError !== null) {
                return response()->json(['output' => $syntaxError], 400);
            }

            // 5. Проверка required/forbidden
            $check = $this->checkCodeContent($code, $conditions);
            if (! $check['isCorrect']) {
                return response()->json([
                    'output'    => implode("", $check['errors']),
                    'isCorrect' => false,
                    'message'   => 'Код не соответствует условиям задания.',
                ], 400);
            }

            // 6. Запуск кода
            $process = $this->makeRunProcess($language, $srcPath, $binPath);
            $process->run();

            if (! $process->isSuccessful()) {
                $err = $process->getErrorOutput();
                return response()->json(['output' => $err], 400);
            }

            return response()->json([
                'output'    => trim($process->getOutput()),
                'isCorrect' => true,
                'message'   => 'Задание выполнено правильно!',
            ]);
        } finally {
            // 7. Убираем временные файлы
            if (file_exists($srcPath)) {
                unlink($srcPath);
            }
            if ($binPath && file_exists($binPath)) {
                unlink($binPath);
            }
        }
    }

    private function extensionByLang(string $lang): string
    {
        return match($lang) {
            'cpp'    => 'cpp',
            'php'    => 'php',
            'python'  => 'py',
            default => throw new \InvalidArgumentException("Unsupported language: {$lang}"),
        };
    }

    /**
     * Проверяет синтаксис и в случае C++ – компилирует бинарник.
     * Возвращает строку с ошибкой или null, если всё ок.
     */
    private function checkSyntax(string $lang, string $src, ?string $bin): ?string
    {
        switch ($lang) {
            case 'cpp':
                // Убедимся, что папка tmp существует и writable
                $tmpDir = storage_path('app/tmp');
                if (! is_dir($tmpDir)) {
                    mkdir($tmpDir, 0777, true);
                }

                // Синтаксическая проверка с -pipe
                $proc = new Process([
                    'g++', '-std=c++17', '-fsyntax-only', '-pipe', $src
                ]);
                $proc->setEnv(['TMP' => $tmpDir, 'TEMP' => $tmpDir]);
                $proc->setTimeout(10);
                $proc->run();
                if (! $proc->isSuccessful()) {
                    return $proc->getErrorOutput();
                }

                // Полная компиляция в бинарник
                $proc2 = new Process([
                    'g++', '-std=c++17', '-pipe', $src, '-o', $bin
                ]);
                $proc2->setEnv(['TMP' => $tmpDir, 'TEMP' => $tmpDir]);
                $proc2->setTimeout(30);
                $proc2->run();
                if (! $proc2->isSuccessful()) {
                    return $proc2->getErrorOutput();
                }

                return null;

            case 'php':
                // -l (lint) проверяет синтаксис
                $proc = new Process(['php', '-l', $src]);
                $proc->setTimeout(10);
                $proc->run();
                // при успешном lint выводится «No syntax errors detected»
                if (str_contains($proc->getOutput(), 'Errors parsing')) {
                    return $proc->getOutput();
                }
                return null;

            case 'python':
                $proc = new Process([$this->getPythonBinary(), '-m', 'py_compile', $src]);
                $proc->setTimeout(10);
                $proc->run();

                if (! $proc->isSuccessful()) {
                    $err  = $proc->getErrorOutput();
                    $lines = explode("\n", $err);

                    $lineNo = null;
                    // Ищем в трассировке именно ваш файл
                    foreach ($lines as $i => $line) {
                        if (preg_match('/^  File "(' . preg_quote($src, '/') . ')", line (\d+)/', $line, $m)) {
                            $lineNo = $m[2];
                            break;
                        }
                    }

                    // Ищем в любом месте сообщение SyntaxError
                    $msg = null;
                    foreach ($lines as $line) {
                        if (preg_match('/SyntaxError:\s*(.+)$/', $line, $m2)) {
                            $msg = $m2[1];
                            break;
                        }
                    }

                    if ($lineNo && $msg) {
                        return "Ошибка в строке {$lineNo}: {$msg}";
                    }

                    // fallback: если не нашли нужные данные, отдадим первый попавшийся SyntaxError или весь текст
                    if ($msg) {
                        return "SyntaxError: {$msg}";
                    }

                    return trim($err);
                }

                return null;
            default:
                return null;
        }
    }


    private function getPythonBinary(): string
    {
        return base_path('Python38/python.exe');
    }

    /**
     * Возвращает Process для запуска кода.
     */
    private function makeRunProcess(string $lang, string $src, ?string $bin): Process
    {
        return match ($lang) {
            'python' => new Process([$this->getPythonBinary(), $src]),
            'cpp'    => new Process([$bin]),
            'php'    => new Process(['php',     $src]),
        };
    }

    private function checkCodeContent(string $code, array $conditions): array
    {
        $errors    = [];
        $isCorrect = true;
        foreach ($conditions['required'] as $kw) {
            if (! str_contains($code, $kw)) {
                $errors[]  = "Ключевое слово '{$kw}' не найдено.\n";
                $isCorrect = false;
            }
        }
        foreach ($conditions['forbidden'] as $kw) {
            if (str_contains($code, $kw)) {
                $errors[]  = "Ключевое слово '{$kw}' запрещено.\n";
                $isCorrect = false;
            }
        }
        return ['isCorrect' => $isCorrect, 'errors' => $errors];
    }

    private function hasDangerousFunctions(string $code): bool
    {
        $dangerous = [
            // Python
            'os.system', 'subprocess', 'eval', 'exec',
            // PHP
            'shell_exec', 'exec(', 'system(',
            // общее
            'import ', 'require', 'file_put_contents',
        ];
        foreach ($dangerous as $pat) {
            if (str_contains($code, $pat)) {
                return true;
            }
        }
        return false;
    }
}
