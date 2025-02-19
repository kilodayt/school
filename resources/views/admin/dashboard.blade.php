@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Админ-панель</h1>

        <h3>Курсы</h3>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary mb-3">Добавить курс</a>
        <ul class="list-group">
            @foreach($courses as $course)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $course->title }}</span>
                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                </li>
            @endforeach
        </ul>

        <hr>

        <h3>Пользователи</h3>
        <ul class="list-group">
            @foreach($users as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $user->name }} ({{ $user->email }})</span>
                    <span>{{ $user->role }}</span>

                    <div class="d-flex">
                        <!-- Форма смены роли -->
                        <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" class="me-2">
                            @csrf
                            @method('POST')
                            <select name="role" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>

                        <!-- Форма удаления пользователя -->
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Удалить пользователя {{ $user->name }}?')">
                                Удалить
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        <hr>

        <h1>Добавить нового пользователя</h1>

        <!-- Форма добавления пользователя -->
        <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
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
        <form action="{{ route('admin.users.assignCourse') }}" method="POST">
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
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Назначить курс</button>
        </form>
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
            fetch('{{ route('admin.users.store') }}', {
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
                .then(response => response.json())
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

@endsection
