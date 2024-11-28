<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить пользователя</title>
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
</head>
<body>
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(Auth::check() && Auth::user()->role === 'admin')
        <div id="popup" class="popup">
            <p id="popupMessage"></p>
            <button id="closePopup">Закрыть</button>
        </div>
        <div id="popupOverlay" class="popup-overlay"></div>
        <h1>Добавить нового пользователя</h1>
        <!-- Форма добавления пользователя -->
        <form id="addUserForm" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Подтвердите пароль</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                       required>
            </div>
            <div class="form-group">
                <label for="role">Роль</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="" disabled selected>Выберите роль</option>
                    <option value="user">User</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Добавить пользователя</button>
        </form>
            <hr>

            <h1>Назначить курс пользователю</h1>

            <!-- Форма назначения курса -->
            <form action="{{ route('users.assignCourse') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="user_id">Выберите пользователя</label>
                    <select name="user_id" id="user_id" class="form-control">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="course_id">Выберите курс</label>
                    <select name="course_id" id="course_id" class="form-control">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->description }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Назначить курс</button>
            </form>
        @else
            @php
                header('Location: ' . url('/')); // Редирект на главную страницу
                exit();
            @endphp
        @endif
</div>

<script>
    // Получаем элементы
    const form = document.getElementById('addUserForm');
    const popup = document.getElementById('popup');
    const popupOverlay = document.getElementById('popupOverlay');
    const popupMessage = document.getElementById('popupMessage');
    const closePopup = document.getElementById('closePopup');

    // Функция для отображения всплывающего окна
    function showPopup(message) {
        popupMessage.textContent = message;
        popup.classList.add('active');
        popupOverlay.classList.add('active');
    }

    // Закрытие всплывающего окна
    closePopup.addEventListener('click', () => {
        popup.classList.remove('active');
        popupOverlay.classList.remove('active');
    });

    // Проверка email на корректность
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Обработчик отправки формы
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Останавливаем отправку формы

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const passwordConfirmation = document.getElementById('password_confirmation').value.trim();
        const role = document.getElementById('role').value;

        // Проверки на пустые поля
        if (!name) {
            showPopup('Поле "Имя" не должно быть пустым.');
            return;
        }
        if (!email) {
            showPopup('Поле "Email" не должно быть пустым.');
            return;
        }
        if (!isValidEmail(email)) {
            showPopup('Введите корректный email.');
            return;
        }
        if (!password) {
            showPopup('Поле "Пароль" не должно быть пустым.');
            return;
        }
        if (password !== passwordConfirmation) {
            showPopup('Пароли не совпадают!');
            return;
        }
        if (!role) {
            showPopup('Выберите роль.');
            return;
        }

        // Отправка запроса на сервер
        fetch('{{ route('users.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                name: name,
                email: email,
                password: password,
                password_confirmation: passwordConfirmation,
                role: role
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка при добавлении пользователя.');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    showPopup(data.error); // Выводим сообщение об ошибке от сервера
                } else {
                    showPopup('Пользователь успешно добавлен!');
                    form.reset(); // Сбрасываем форму
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                showPopup('Произошла ошибка. Попробуйте снова.');
            });
    });
</script>
</body>
</html>
