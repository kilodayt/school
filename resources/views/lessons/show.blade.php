<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
    <title>{{ $lesson->title }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="stylesheet" href="{{ asset('css/lessons/lessons-show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lessons/lessons-mobile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
</head>

<style>

    .CodeMirror pre.CodeMirror-line, .CodeMirror pre.CodeMirror-line-like {
        padding: 0 40px;
    }

</style>

<body>

@include('includes.header')

<div class="progress-bar">
    <h3>Прогресс: {{ $completedLessonsCount }} из {{ $totalLessons }} уроков завершено</h3>
    <div class="progress">
        <div class="progress-completed" style="width: {{ ($completedLessonsCount / $totalLessons) * 100 }}%;"></div>
    </div>
</div>
<div class="lessons-container">
    <!-- Список уроков -->
    <div class="lessons-list">
        <h3>Уроки</h3>
        @foreach($lessons as $lessonItem)
            <div class="lesson-item {{ in_array($lessonItem->id, $completedLessons) ? 'completed' : '' }}">
                <a href="{{ route('lessons.show', ['course_id' => $course->id, 'id' => $lessonItem->lesson_id]) }}">
                    {{ request()->is('lessons/'.$lessonItem->lesson_id) ? 'active' : '' }}
                    {{ $lessonItem->title }}
                </a>
            </div>
        @endforeach
    </div>
        <!-- Контент выбранного урока -->
    <div class="lesson-content">
        <h2>{{ $lesson->title }}</h2>
            <!-- Навигация по разделам контента -->
        <div class="nav-links">
            <a href="#theory1">Теория 1</a>
            <a href="#theory2">Теория 2</a>
            <a href="#theory3">Теория 3</a>
            <a href="#exercise">Упражнение</a>
        </div>
            <!-- Секции контента -->
        <div id="theory1" class="content-section">
            <h3>Теория 1</h3>
            <p>{!! $theory1Text !!}</p>
        </div>
        <div id="theory2" class="content-section">
            <h3>Теория 2</h3>
            <p>{!! $theory2Text !!}</p>
        </div>
        <div id="theory3" class="content-section">
            <h3>Теория 3</h3>
            <p>{!! $theory3Text !!}</p>
        </div>
        <div id="exercise" class="content-section">
            <h3>Упражнение</h3>
            <p>{!! $exessizeText !!}</p>
            <h1>Python Компилятор</h1>
                <!-- CodeMirror Editor -->
            <div class="editor-container">
                <textarea id="codeEditor" rows="10" cols="60" placeholder="Введите ваш Python код..."></textarea>
            </div>

            <br>
            <button id="runButton" data-lesson-id="{{ $lesson->id }}">Запустить код</button>

            <!-- Вывод результата -->
            <h3>Результат:</h3>
            <pre id="output" style="background: #f8f8f8; border: 1px solid #ddd; padding: 20px; position: relative;"></pre>
        </div>
    </div>
</div>
@include('includes.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/python/python.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.nav-links a');
        const sections = document.querySelectorAll('.content-section');

        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);

                sections.forEach(section => {
                    if (section.id === targetId) {
                        section.classList.add('active');
                    } else {
                        section.classList.remove('active');
                    }
                });
            });
        });
    });

    // Initialize CodeMirror editor with Python mode
    const editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
        mode: "python",
        lineNumbers: true,
        theme: "default"
    });

    // Run code on button click
    document.getElementById('runButton').addEventListener('click', function() {
        const code = editor.getValue();
        const lesson_id = {{ $lesson->id }};


        fetch('/run-python', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code, lesson_id: lesson_id }) // Передаем lesson_id в запросе
        })
            .then(response => response.json())
            .then(data => {
                const outputElement = document.getElementById('output');
                const lines = (data.output || data.error || '').split('\n');

                // Проверяем, есть ли длинный вывод
                if (lines.length > 20) {
                    outputElement.textContent = lines.slice(0, 19).join('\n');
                    const showMoreButton = document.createElement('button');
                    showMoreButton.textContent = 'Показать больше';
                    showMoreButton.onclick = () => {
                        outputElement.textContent = lines.join('\n');
                        showMoreButton.remove();
                    };
                    outputElement.appendChild(showMoreButton);
                } else {
                    outputElement.textContent = lines.join('\n');
                }

                // Отображаем сообщение о правильности выполнения задания
                const resultMessage = document.createElement('div');
                resultMessage.textContent = data.message || '';
                resultMessage.style.color = data.isCorrect ? 'green' : 'red'; // Зеленый цвет для правильного ответа, красный для ошибки
                if (data.isCorrect) {
                    fetch('/update-progress', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ lesson_id: lesson_id })
                    })
                        .then(response => response.json())
                        .then(progressData => {
                            if (progressData.status === 'success') {
                                console.log('Прогресс обновлен успешно');
                            } else if (progressData.error) {
                                console.error('Ошибка при обновлении прогресса:', progressData.error);
                            } else {
                                console.error('Неизвестная ошибка при обновлении прогресса');
                            }
                        })
                        .catch(error => {
                            console.error('Ошибка при обновлении прогресса:', error);
                        });
                }
                outputElement.appendChild(resultMessage);
            })
            .catch(error => {
                document.getElementById('output').textContent = 'Ошибка выполнения: ' + error.message;
            });

    });

</script>

</body>
</html>
