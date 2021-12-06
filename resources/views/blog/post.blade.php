@extends('layout.site', ['title' => $post->name])

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h1>{{ $post->name }}</h1>
        </div>
        @php
        if($post->image) $src = asset('storage/post/image/'.$post->image);
        else $src =' https://via.placeholder.com/1000x300';
        @endphp
        <div class="card-body">
            <img src="{{$src}}" alt="" class="img-fluid">
            <div class="mt-4">{!! $post->content !!}</div>
        </div>
        <div class="card-footer">
                Автор:
                <a href="{{ route('blog.author', ['user' => $post->user->id]) }}">
                    {{ $post->user->name }} {{ $post->user->surname }}
                </a>
                <br>
                Дата: {{ $post->created_at }}
        </div>
        @if ($post->tags->count())
            <div class="card-footer">
                Теги:
                @foreach($post->tags as $tag)
                    @php $comma = $loop->last ? '' : ' • ' @endphp
                    <a href="{{ route('blog.tag', ['tag' => $tag->slug]) }}">
                        {{ $tag->name }}</a>
                    {{ $comma }}
                @endforeach
            </div>
        @endif
    </div>
    @isset($comments)
        @include('admin.post.comments', ['comments' => $comments])
    @endisset
@endsection