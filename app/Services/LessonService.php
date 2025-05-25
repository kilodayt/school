<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonDetail;
use App\Models\Progress;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LessonService
{
    /**
     * Получить список уроков для курса и информацию о прогрессе пользователя.
     *
     * @param  int  $courseId
     * @param  int  $userId
     * @return array
     *
     * @throws ModelNotFoundException
     */
    public function getLessonsWithCompletion(int $courseId, int $userId): array
    {
        // Загружаем курс с уроками
        $course  = Course::with('lessons')->findOrFail($courseId);
        $lessons = $course->lessons;

        // Вычисляем выполненные уроки по полям course_id + lesson_id
        $completedLessons = Progress::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereIn('lesson_id', $lessons->pluck('lesson_id'))
            ->pluck('lesson_id')
            ->toArray();

        return [
            'course'                => $course,
            'lessons'               => $lessons,
            'completedLessons'      => $completedLessons,
            'totalLessons'          => $lessons->count(),
            'completedLessonsCount' => count($completedLessons),
        ];
    }


    /**
     * Создать новый урок и LessonDetail в рамках курса.
     *
     * @param  int   $courseId
     * @param  array $data
     * @return array
     *
     * @throws ModelNotFoundException
     */
    public function createLesson(int $courseId, array $data): array
    {
        // Проверяем существование курса
        $course = Course::findOrFail($courseId);

        // Проверяем лимит уроков
        if ($course->lessons->count() >= 20) {
            return [
                'error'   => true,
                'message' => 'Нельзя создать больше 20 уроков.',
            ];
        }

        // Создаём урок
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'lesson_id' => $data['lesson_id'],
            'title'     => $data['title'],
            'content'   => $data['content'],
        ]);

        // Создаём детали урока
        LessonDetail::create([
            'lesson_id' => $data['lesson_id'],
            'course_id' => $course->id,
            'theory_1'  => $data['theory_1'],
            'theory_2'  => $data['theory_2'],
            'theory_3'  => $data['theory_3'],
            'exessize'  => $data['exessize'],
        ]);

        return [
            'error'   => false,
            'message' => 'Урок создан успешно!',
        ];
    }

    /**
     * Показать информацию о конкретном уроке, включая теорию и прогресс пользователя.
     *
     * @param  int  $courseId
     * @param  int  $lessonId
     * @param  int  $userId
     * @return array
     *
     * @throws ModelNotFoundException
     */
    public function showLesson(int $courseId, int $lessonId, int $userId): array
    {
        // Проверка корректности номера урока
        if ($lessonId < 1 || $lessonId > 20) {
            throw new ModelNotFoundException('Урок не найден');
        }

        // Ключ кэша БЕЗ userId
        $cacheKey = "show_lesson_course_{$courseId}_lesson_{$lessonId}";

        // Кэшируем блок данных на 10 минут (600 секунд)
        $cachedData = Cache::remember($cacheKey, 600, function () use ($courseId, $lessonId) {
            // Загружаем курс вместе со всеми уроками
            $course = Course::with('lessons')->findOrFail($courseId);

            // Ищем конкретный урок
            $lesson = Lesson::where('course_id', $courseId)
                ->where('lesson_id', $lessonId)
                ->firstOrFail();

            // Загружаем детали урока
            $lessonDetails = LessonDetail::where('lesson_id', $lessonId)
                ->where('course_id', $courseId)
                ->first();

            // Форматируем текст (теория, задания)
            $theory1Text   = $this->formatText($lessonDetails->theory_1 ?? '');
            $theory2Text   = $this->formatText($lessonDetails->theory_2 ?? '');
            $theory3Text   = $this->formatText($lessonDetails->theory_3 ?? '');
            $exessizeText  = $this->formatText($lessonDetails->exessize ?? '');

            // Возвращаем данные, общие для всех пользователей
            return [
                'course'        => $course,
                'lesson'        => $lesson,
                'lessonDetails' => $lessonDetails,
                'lessons'       => $course->lessons,
                'theory1Text'   => $theory1Text,
                'theory2Text'   => $theory2Text,
                'theory3Text'   => $theory3Text,
                'exessizeText'  => $exessizeText,
            ];
        });

        // --- Блок, зависящий от пользователя (прогресс) ---
        // Его НЕ кэшируем, т.к. у каждого пользователя — свои данные

        $lessons = $cachedData['lessons'];
        $completedLessons = Progress::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereIn('lesson_id', $lessons->pluck('lesson_id'))
            ->pluck('lesson_id')
            ->toArray();


        return [
            'course'                => $cachedData['course'],
            'lesson'                => $cachedData['lesson'],
            'lessonDetails'         => $cachedData['lessonDetails'],
            'lessons'               => $cachedData['lessons'],
            'theory1Text'           => $cachedData['theory1Text'],
            'theory2Text'           => $cachedData['theory2Text'],
            'theory3Text'           => $cachedData['theory3Text'],
            'exessizeText'          => $cachedData['exessizeText'],
            'completedLessons'      => $completedLessons,
            'totalLessons'          => $lessons->count(),
            'completedLessonsCount' => count($completedLessons),
        ];
    }

    /**
     * Вывод формы для редактирования урока.
     *
     * @param  int   $course_id
     * @param  int   $lesson_id
     * @return Lesson
     */

    public function editForm(int $course_id, int $lesson_id)
    {
        return Lesson::where('course_id', $course_id)
            ->where('lesson_id', $lesson_id)
            ->firstOrFail();
    }

    /**
     * Обновить существующий урок.
     * @param array $data
     * @param  int   $course_id
     * @param  int   $lesson_id
     * @return void
     *
     * @throws ModelNotFoundException
     */
    public function updateLesson(array $data, int $course_id,  int $lesson_id)
    {
        $lesson = Lesson::where('course_id', $course_id)
            ->where('lesson_id', $lesson_id)
            ->firstOrFail();
        $lesson->update($data);
    }

    /**
     * Удалить урок по его ID.
     *
     * @param  int  $lessonId
     * @return bool
     *
     * @throws ModelNotFoundException
     */
    public function deleteLesson(int $lessonId): bool
    {
        $lesson = Lesson::findOrFail($lessonId);
        return (bool)$lesson->delete();
    }

    /**
     * Получить или создать детали урока для последующего редактирования.
     *
     * @param  int  $courseId
     * @param  int  $lessonId
     * @return LessonDetail
     */
    public function getLessonDetails(int $courseId, int $lessonId): LessonDetail
    {
        return LessonDetail::firstOrCreate([
            'lesson_id' => $lessonId,
            'course_id' => $courseId,
        ]);
    }

    /**
     * Обновить теоретические материалы (LessonDetail).
     *
     * @param  int   $courseId
     * @param  int   $lessonId
     * @param  array $data
     * @return LessonDetail
     */
    public function updateLessonDetails(int $courseId, int $lessonId, array $data): LessonDetail
    {
        return LessonDetail::updateOrCreate(
            ['lesson_id' => $lessonId, 'course_id' => $courseId],
            [
                'theory_1' => $data['theory_1'] ?? '',
                'theory_2' => $data['theory_2'] ?? '',
                'theory_3' => $data['theory_3'] ?? '',
                'exessize' => $data['exessize'] ?? '',
            ]
        );
    }

    /**
     * Приватный метод для форматирования текста (здесь аналогичный функционал,
     * что и в функции formatText() в контроллере).
     *
     * @param  string  $text
     * @return string
     */
    private function formatText(string $text): string
    {
        // Защита от XSS (e) и преобразование переводов строк (nl2br)
        return nl2br(e($text));
    }
}
