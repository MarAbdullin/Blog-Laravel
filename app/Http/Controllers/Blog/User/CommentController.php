<?php

namespace App\Http\Controllers\Blog\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
<<<<<<< HEAD
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
=======
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    
    //Список всех комментариев пользователя
    public function index() {
        $comments = Comment::whereUserId(Auth::user()->id)
            ->orderByDesc('created_at')
            ->paginate();
        return view('user.comment.index', compact('comments'));
    }

    
    // Просмотр комментария к посту блога
    public function show(Comment $comment) {
>>>>>>> b3
        // можно просматривать только свои комментарии
        if ( ! $comment->isAuthor()) {
            abort(404);
        }
<<<<<<< HEAD

=======
>>>>>>> b3
        // сигнализирует о том, что это режим пред.просмотра
        session()->flash('preview', 'yes');
        // это тот пост блога, к которому оставлен комментарий
        $post = $comment->post;
        // все опубликованные комментарии других пользователей
        $others = $post->comments()->whereNotNull('published_by');
        // и не опубликованные комментарии этого пользователя
        $comments = $post->comments()
<<<<<<< HEAD
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
=======
            ->whereUserId(auth()->user()->id)
            ->whereNull('published_by')
            ->union($others)
            ->orderBy('created_at')
            ->paginate();
        // используем шаблон предварительного просмотра поста
        return view('user.post.show', compact('post', 'comments'));
    }

    
    // Показывает форму редактирования комментария
    public function edit(Comment $comment) {
>>>>>>> b3
        // проверяем права пользователя на это действие
        if ( ! $this->can($comment)) {
            abort(404);
        }
<<<<<<< HEAD

=======
>>>>>>> b3
        // нужно сохранить flash-переменную, которая сигнализирует о том,
        // что кнопка редактирования была нажата в режиме пред.просмотра
        session()->keep('preview');
        return view('user.comment.edit', compact('comment'));
    }

    //Обновляет комментарий в базе данных
<<<<<<< HEAD
    public function update(CommentRequest $request, Comment $comment)
    {
=======
    public function update(CommentRequest $request, Comment $comment) {
>>>>>>> b3
        // проверяем права пользователя на это действие
        if ( ! $this->can($comment)) {
            abort(404);
        }
<<<<<<< HEAD

        $comment->update($request->all());

        return $this->redirectAfterUpdateUser($comment);
    }

    //Удаляет комментарий из базы данных
    public function destroy(Comment $comment)
    {
=======
        $comment->update($request->all());
        return $this->redirectAfterUpdate($comment);
    }

    //Удаляет комментарий из базы данных
    public function destroy(Comment $comment) {
>>>>>>> b3
        // проверяем права пользователя на это действие
        if ( ! $this->can($comment)) {
            abort(404);
        }
<<<<<<< HEAD

        $comment->delete();

        $redirect = back();

        if(session('preview')){
            $redirect = $redirect->withFragment('comment-list');
        }

        return $redirect->with('success', 'Комментарий успешно удален');
    }

    //Выполняет редирект после обновления
    private function redirectAfterUpdateUser(Comment $comment)
    {
=======
        $comment->delete();
        // кнопка удаления может быть нажата в режиме пред.просмотра
        // или в личном кабинете пользователя, поэтому редирект разный
        $redirect = back();
        if (session('preview')) {
            $redirect = $redirect->withFragment('comment-list');
        }
        return $redirect->with('success', 'Комментарий успешно удален');
    }

    // Выполняет редирект после обновления
    private function redirectAfterUpdate(Comment $comment) {
>>>>>>> b3
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
<<<<<<< HEAD
    
    //Проверяет, что пользователь может редактировать или удалять комментарий поста
=======

    /**
     * Проверяет, что пользователь может редактировать
     * или удалять пост блога
     */
>>>>>>> b3
    private function can(Comment $comment) {
        return $comment->isAuthor() && !$comment->isVisible();
    }
}
