<?php

namespace App\Services;

use App\Models\Course;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Models\LessonDetail;

class CourseService
{
    /**
     * Получение списка всех курсов, сгруппированных по языку программирования
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllCourses()
    {
        return Course::all()->groupBy('language');
    }

    /**
     * Получение одного курса по его ID вместе с уроками
     *
     * @param  int  $id
     * @return Course
     */
    public function getCourseById($id)
    {
        return Course::with('lessons')->findOrFail($id);
    }

    public function hasCourse($id)
    {
        $userId = Auth::id();
        return UserCourse::where('user_id', $userId)
            ->where('course_id', $id)
            ->exists();
    }

    /**
     * Создание курса
     *
     * @param array $data
     * @return Course
     */
    public function createCourse(array $data): Course
    {
        // Сохранение изображения, если загружено
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->saveImage($data['image']);
        }

        // Создание и сохранение курса
        return Course::create($data);
    }

    /**
     * Обновление курса
     *
     * @param Course $course
     * @param array $data
     * @return Course
     */
    public function updateCourse(Course $course, array $data): Course
    {
        $course->update($data);
        return $course;
    }

    /**
     * Удаление курса
     *
     * @param Course $courseId
     * @return void
     */
    public function deleteCourse(int $courseId): void
    {
        // Находим курс, вместе с уроками и деталями уроков
        $course = Course::with('lessons.details')->findOrFail($courseId);

        // Удаляем связанные детали уроков
        LessonDetail::where('course_id', $courseId)->delete();

        foreach ($course->lessons as $lesson) {
            $lesson->progress()->delete();
        }

        foreach ($course->lessons as $lesson) {
            $lesson->delete();
        }

        // Удаляем сам курс
        $course->delete();
    }


    /**
     * Сохранение изображения
     *
     * @param UploadedFile $image
     * @return string
     */
    private function saveImage(UploadedFile $image): string
    {
        return $image->store('courses', 'public');
    }
}
