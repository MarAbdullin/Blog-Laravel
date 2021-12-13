@extends('layout.site', ['title' => 'Поиск по блогу'])

@section('content')
    <h1 class="mb-3">Поиск по блогу</h1>
    <p>Поисковый запрос: {{ $search ?? 'пусто' }}</p>
    @if ($posts->count())
        @foreach ($posts as $post)
            @if($post->published_by != null)
                @include('layout.part.post', ['post' => $post])
            @endif
        @endforeach
        {{ $posts->links() }}
    @else
        <p>По вашему запросу ничего не найдено</p>
    @endif
@endsection