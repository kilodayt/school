<!doctype html>
<html lang="en">

<head>
    @include('includes.head')
    <link rel="stylesheet" href="{{ asset('css/blog/blog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blog/blog-mobile.css') }}">
    <title>О нас</title>
</head>

<body>
@include('includes.header')

<div class="blog-container">
    <h1 class="my-4">{{ $post->title }}</h1>

    @if ($post->image)
        <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid mb-4" alt="{{ $post->title }}">
    @endif

    <div class="content">
        {!! $post->content !!}
    </div>

    <a href="{{ route('blog.index') }}" class="btn btn-secondary mt-4">Вернуться к списку постов</a>
</div>

@include('includes.footer')
</body>

</html>
