@extends('layout.site', ['title' => 'Главная страница'])

@section('content')
    <h1 class="mb-3">Все посты блога</h1>
    @foreach ($posts as $post)
        @include('layout.part.post', ['post' => $post])
    @endforeach
    {{ $posts->links() }}
@endsection