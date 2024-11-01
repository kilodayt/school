<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
    <title>{{ $lesson->title }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="stylesheet" href="{{ asset('css/lessons/lessons-show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lessons/lessons-mobile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <script src="{{ asset('js/highlight.js') }}"></script>
    <script src="{{ asset('js/autocomplete.js') }}"></script>
</head>

<body>

@include('includes.header')

<div class="lessons-container">
    <!-- Список уроков -->
    <div class="lessons-list">
        <h3>Уроки</h3>
        @foreach($lessons as $lessonItem)
            <div class="lesson-item">
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

            <!-- Поле для ввода Python-кода -->
            <textarea id="codeEditor" oninput="highlightCode()" rows="10" cols="60" placeholder="Введите ваш Python код..." style="width: 100%; resize: none;"></textarea>
            <pre id="highlightedCode" style="background: #f8f8f8; border: 1px solid #ddd; padding: 10px; margin-top: 10px; overflow: auto;"></pre>
            <br>
            <button id="runButton">Запустить код</button>

            <!-- Вывод результата -->
            <h3>Результат:</h3>
            <pre id="output" style="background: #f8f8f8; border: 1px solid #ddd; padding: 10px;"></pre>
        </div>
    </div>
</div>

@include('includes.footer')

<script>
    // JavaScript для управления видимостью секций контента
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
</script>

<!-- JavaScript для отправки кода на сервер -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#runButton').on('click', function () {
        var code = $('#codeEditor').val();

        $.ajax({
            url: '{{ route("run-python") }}',
            type: 'POST',
            data: {
                code: code,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#output').text(response.output);
            },
            error: function (xhr) {
                $('#output').text(xhr.responseJSON.output);
            }
        });
    });

    document.getElementById('runButton').addEventListener('click', function() {
        const code = document.getElementById('codeEditor').value;

        // Отправляем код на сервер для выполнения
        fetch('/run-python', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Не забудьте включить CSRF-токен
            },
            body: JSON.stringify({ code: code })
        })
            .then(response => response.json())
            .then(data => {
                // Обновляем поле вывода результатом выполнения кода
                document.getElementById('output').textContent = data.output || data.error;
            })
            .catch(error => {
                document.getElementById('output').textContent = 'Ошибка выполнения: ' + error.message;
            });
    });

    document.getElementById('codeEditor').addEventListener('keydown', function(event) {
        if (event.key === 'Tab') {
            event.preventDefault(); // Отменяем стандартное действие (фокус на следующем элементе)

            // Вставляем символ табуляции
            const start = this.selectionStart;
            const end = this.selectionEnd;

            // Устанавливаем новое значение текста с символом табуляции
            this.value = this.value.substring(0, start) + '\t' + this.value.substring(end);

            // Устанавливаем курсор в правильное положение
            this.selectionStart = this.selectionEnd = start + 1;

            highlightCode(); // Обновляем подсветку кода
        }
    });

    function highlightCode() {
        const code = $('#codeEditor').val();
        const highlighted = code
            .replace(/(for|in|def|class|if|else|elif|while|try|except|with|import|as|from|return|print|range|and|or|not)/g, '<span style="color: blue;">$1</span>')
            .replace(/"(.*?)"/g, '<span style="color: green;">"$1"</span>')
            .replace(/'(.*?)'/g, '<span style="color: green;">\'$1\'</span>');

        $('#highlightedCode').html(highlighted.replace(/</g, '&lt;').replace(/>/g, '&gt;'));
    }

    highlightCode();
</script>

</body>

</html>
