<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.head')
    <link rel="stylesheet" href="{{ asset('css/pages/contacts.css') }}">
    <title>Контакты</title>
</head>

<body>

@include('includes.header')

<!-- Contact Info -->
<section class="contact-info">
    <div class="container">
        <h2 class="section-title" data-aos="fade-down" data-aos-duration="1000">Наши контакты</h2>
        <div class="info-list">
            <div class="info-item" data-aos="fade-right" data-aos-duration="1000">
                <div class="icon">📍</div>
                <h3>Адрес офиса</h3>
                <p>г. Ваш город, ул. Программирования, д. 1</p>
            </div>
            <div class="info-item" data-aos="fade-up" data-aos-duration="1000">
                <div class="icon">📞</div>
                <h3>Телефон</h3>
                <p>+7 (123) 456-78-90</p>
            </div>
            <div class="info-item" data-aos="fade-left" data-aos-duration="1000">
                <div class="icon">✉️</div>
                <h3>Электронная почта</h3>
                <p><a href="mailto:info@school.local">info@school.local</a></p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section id="contact-form">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up" data-aos-duration="1000">Отправьте нам сообщение</h2>

        @if(session('success'))
            <div class="alert success" data-aos="fade-down">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error" data-aos="fade-down">{{ session('error') }}</div>
        @endif

        <form action="#" method="POST" class="form" data-aos="fade-up" data-aos-duration="1200">
            @csrf

            <div class="form-group">
                <label for="name">Ваше имя</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Ваш Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="subject">Тема сообщения</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required>
                @error('subject')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="message">Сообщение</label>
                <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                @error('message')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-action">
                <button type="submit" class="btn-submit">Отправить</button>
            </div>
        </form>
    </div>
</section>

@include('includes.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"
        integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/Animation.js') }}"></script>
</body>

</html>
