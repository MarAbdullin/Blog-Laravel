<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //права на доступ к действиям контроллера
    public function __construct() {
        $this->middleware('perm:manage-comments')->only(['index', 'show']);
        $this->middleware('perm:edit-comment')->only('update');
        $this->middleware('perm:publish-comment')->only(['enable', 'disable']);
        $this->middleware('perm:delete-comment')->only('destroy');
    }

    //Показывает список всех комментариев
    public function index()
    {
        // $comment = Comment::where('id', 1)->first();
        // $post = $comment->post;
        // $comments = $post->comments;
        // dd($comments);
        $comments = Comment::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.comment.index', compact('comments'));
        
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
        // сигнализирует о том, что это режим пред.просмотра
        session()->flash('preview', 'yes');
        // это тот пост блога, к которому оставлен комментарий
        $post = $comment->post;
        // коллекция всех комментариев к этому посту блога
        $comments = $post->comments()->orderBy('created_at')->paginate();
        
        return view('admin.post.show', compact('post', 'comments'));
    }

    
    public function edit(Comment $comment)
    {   // нужно сохранить flash-переменную, которая сигнализирует о том,
        // что кнопка редактирования была нажата в режиме пред.просмотра
        session()->keep('preview');

        return view('admin.comment.edit', compact('comment'));
    }

    //Обновляет комментарий в базе данных
    public function update(CommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());
        return $this->redirectAfterUpdate($comment);
    }

    public function enable(Comment $comment)
    {
        $comment->enable();

        $redirect = back();
        if(session('preview')){
            $rediret = $redirect->withFragment('comment-list');
        }

        return $redirect->with(['success' => 'Комментарий был опубликован']);

    }

    public function disable(Comment $comment)
    {
        $comment->disable();

        $redirect = back();
        if(session('preview')){
            $rediret = $redirect->withFragment('comment-list');
        }

        return $redirect->with(['success' => 'Комментарий был снят с публикации']);
    }

    public function destroy(Comment $comment)
    {
        $comment->forceDelete();

        $redirect = back();
        if(session('preview')){
            $rediret = $redirect->withFragment('comment-list');
        }

        return $redirect->with(['success' => 'Комментарий был удален']);
    }

    //Выполянет редирект после обновления
    public function redirectAfterUpdate(Comment $comment)
    {
        $redirect = redirect();
        // кнопка редактирования может быть нажата в режиме пред.просмотра
        // или в панели управления блогом, поэтому и редирект будет разный
        if(session('preview')){
            $redirect = $redirect->route(
                'admin.comment.show',
                ['comment' => $comment->id, 'page' => $comment->adminPageNumber()])
                ->withFragment('comment-list');
        }
        else{
            $redirect = $redirect->route('admin.comment.index');
        }

        return $redirect->with(['success' => 'Комментарий был успешно исправлен']);
    }   
}
