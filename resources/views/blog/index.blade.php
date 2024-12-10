<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="{{ asset('css/blog/blog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blog/blog-mobile.css') }}">
    @include('includes.head')
    <title>О нас</title>
</head>

<body class="card_body">
@include('includes.header')
<div class="blog-container">
    <h1 class="my-4">Блог</h1>

    @if($posts->count())
        @foreach ($posts as $post)
            <div class="card mb-4">
                @if ($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}">
                @endif
                <div class="card-body">
                    <h2 class="card-title">{{ $post->title }}</h2>
                    <p class="card-text">{{ Str::limit(strip_tags($post->content), 150, '...') }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-primary">Читать далее</a>
                </div>
            </div>
        @endforeach

        {{ $posts->links() }}
    @else
        <p>Нет доступных постов.</p>
    @endif
</div>

@include('includes.footer')
</body>

</html>
