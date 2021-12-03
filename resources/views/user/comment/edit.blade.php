@extends('layout.user', ['title' => 'Редактирование комментария'])

@section('content')
    <h1>Редактирование комментария</h1>
    <form method="post"
<<<<<<< HEAD
          action="{{ route('user.comment.update', ['comment' => $comment->id]) }}">
=======
          action="{{ route('admin.comment.update', ['comment' => $comment->id]) }}">
>>>>>>> b3
        @csrf
        @method('PUT')
        <div class="form-group">
            <textarea class="form-control" name="content"
                      placeholder="Текст комментария" maxlength="500"
                      rows="5">{{ old('content') ?? $comment->content }}</textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection