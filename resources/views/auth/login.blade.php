<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Авторизация</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<div class="wrapper">
    <form method="post" action="{{ route('login') }}">
        @csrf
        <h1>Авторизация</h1>
        <div class="input-box">
            <input type="text"
                   id="email"
                   name="email"
                   placeholder="Email"
                   required>
            <i class='bx bx-user' ></i>
        </div>
        <div class="input-box">
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="Пароль"
                   required>
            <i class='bx bx-lock' id="togglePassword"></i>
        </div>
        <div class="remember-forgot">
            <a href="/reset">Забыли пароль?</a>
        </div>
        <button type="submit" class="button">Авторизоваться</button>
    </form>
</div>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('bx-show');
    });
</script>
</body>
</html>
