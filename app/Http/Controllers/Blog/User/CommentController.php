<?php

namespace App\Http\Controllers\Blog\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    // Список всех комментариев пользователя
    public function index()
    {
        $comments = Comment::whereUserId(Auth::user()->id)->orderByDesc('created_at')->paginate();
        return view('user.comment.index', compact('comments'));
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

    //Просмотр комментария к посту блога
    public function show(Comment $comment)
    {
        // можно просматривать только свои комментарии
        if ( ! $comment->isAuthor()) {
            abort(404);
        }

        // сигнализирует о том, что это режим пред.просмотра
        session()->flash('preview', 'yes');
        // это тот пост блога, к которому оставлен комментарий
        $post = $comment->post;
        // все опубликованные комментарии других пользователей
        $others = $post->comments()->whereNotNull('published_by');
        // и не опубликованные комментарии этого пользователя
        $comments = $post->comments()
                ->whereUserId(Auth::user()->id)
                ->whereNull('published_by')
                ->union($others)
                ->orderBy('created_at')
                ->paginate();
        
        // используем шаблон предварительного просмотра поста
        return view('user.post.show', compact('post', 'comments'));
        
    }

    //Показывает форму редактирования комментария
    public function edit(Comment $comment)
    {
        // проверяем права пользователя на это действие
        if ( ! $this->can($comment)) {
            abort(404);
        }

        // нужно сохранить flash-переменную, которая сигнализирует о том,
        // что кнопка редактирования была нажата в режиме пред.просмотра
        session()->keep('preview');
        return view('user.comment.edit', compact('comment'));
    }

    //Обновляет комментарий в базе данных
    public function update(CommentRequest $request, Comment $comment)
    {
        // проверяем права пользователя на это действие
        if ( ! $this->can($comment)) {
            abort(404);
        }

        $comment->update($request->all());

        return $this->redirectAfterUpdate($comment);
    }

    //Удаляет комментарий из базы данных
    public function destroy(Comment $comment)
    {
        // проверяем права пользователя на это действие
        if ( ! $this->can($comment)) {
            abort(404);
        }

        $comment->delete();

        $redirect = back();

        if(session('preview')){
            $redirect = $redirect->withFragment('comment-list');
        }

        return $redirect->with('success', 'Комментарий успешно удален');
    }

    //Выполняет редирект после обновления
    private function redirectAfterUpdate(Comment $comment)
    {
        // кнопка редактирования может быть нажата в режиме пред.просмотра
        // или в личном кабинете пользователя, поэтому и редирект разный
        $redirect = redirect();
        if (session('preview')) {
            $redirect = $redirect->route(
                'user.comment.show',
                ['comment' => $comment->id, 'page' => $comment->userPageNumber()]
            )->withFragment('comment-list');
        } else {
            $redirect = $redirect->route('user.comment.index');
        }
        return $redirect->with('success', 'Комментарий был успешно исправлен');
    }
    
    //Проверяет, что пользователь может редактировать или удалять комментарий поста
    private function can(Comment $comment) {
        return $comment->isAuthor() && !$comment->isVisible();
    }
}
