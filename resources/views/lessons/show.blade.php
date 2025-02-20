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
            <p>{!! nl2br(strip_tags($theory3Text)) !!}</p>
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
    // Сохранение значения с указанием времени жизни (ttl) в миллисекундах
    function setWithExpiry(key, value, ttl) {
        const now = Date.now();
        const item = {
            value: value,
            expiry: now + ttl,
        };
        localStorage.setItem(key, JSON.stringify(item));
    }

    // Получение значения из localStorage, если не просрочено
    function getWithExpiry(key) {
        const itemStr = localStorage.getItem(key);
        if (!itemStr) {
            return null;
        }
        const item = JSON.parse(itemStr);
        const now = Date.now();

        // Если данные устарели, удаляем ключ и возвращаем null
        if (now > item.expiry) {
            localStorage.removeItem(key);
            return null;
        }
        return item.value;
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Переключение секций
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.toggle('active', section.id === targetId);
                });
            });
        });

        // Инициализация CodeMirror
        const editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
            mode: "python",
            lineNumbers: true,
            theme: "default"
        });

        // Вставьте сюда реальный userId и lessonId
        const userId = {{ auth()->id() }};
        const lessonId = {{ $lesson->id }};

        // Ключ для локального хранилища
        const localStorageKey = `codeEditorLesson_${lessonId}_user_${userId}`;

        // Проверяем, есть ли сохранённый код (не просроченный)
        const savedCode = getWithExpiry(localStorageKey);
        if (savedCode) {
            editor.setValue(savedCode);
        }

        // Сохраняем код в localStorage на 10 минут (600000 мс)
        // каждую 1 секунду после последнего изменения
        const SAVE_INTERVAL = 1000; // 1 секунда
        let saveTimeoutId = null;

        editor.on('change', () => {
            if (saveTimeoutId) {
                clearTimeout(saveTimeoutId);
            }
            saveTimeoutId = setTimeout(() => {
                const currentCode = editor.getValue();
                // 10 минут = 10 * 60 * 1000 = 600000 мс
                setWithExpiry(localStorageKey, currentCode, 10 * 60 * 1000);
            }, SAVE_INTERVAL);
        });

        // Обработчик запуска кода
        document.getElementById('runButton').addEventListener('click', () => {
            const code = editor.getValue();
            const outputElement = document.getElementById('output');

            fetch('/run-python', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code, lesson_id: lessonId })
            })
                .then(response => response.json())
                .then(data => {
                    const lines = (data.output || data.error || '').split('\n');
                    outputElement.textContent = lines.slice(0, 20).join('\n');

                    if (lines.length > 20) {
                        const showMoreButton = document.createElement('button');
                        showMoreButton.textContent = 'Показать больше';
                        showMoreButton.onclick = () => {
                            outputElement.textContent = lines.join('\n');
                            showMoreButton.remove();
                        };
                        outputElement.appendChild(showMoreButton);
                    }

                    if (data.message) {
                        const resultMessage = document.createElement('div');
                        resultMessage.textContent = data.message;
                        resultMessage.style.color = data.isCorrect ? 'green' : 'red';
                        outputElement.appendChild(resultMessage);

                        if (data.isCorrect) {
                            fetch('/update-progress', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ lesson_id: lessonId })
                            }).catch(console.error);
                        }
                    }
                })
                .catch(error => {
                    outputElement.textContent = 'Ошибка выполнения: ' + error.message;
                });
        });
    });
</script>
</body>
</html>
