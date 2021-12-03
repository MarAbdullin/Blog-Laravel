<?php

namespace App\Http\Controllers\Blog\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware('perm:create-post')->only(['create', 'store']);
    }

    
    //Список всех постов пользователя
    public function index() {
        $posts = Post::whereUserId(Auth::user()->id)->orderByDesc('created_at')->paginate();
        return view('user.post.index', compact('posts'));
    }

    
    //Показывает форму создания поста   
    public function create() {
        return view('user.post.create');
    }


    //Сохраняет новый пост в базу данных
    public function store(PostRequest $request) {
        $request->merge(['user_id' => Auth::user()->id]);
        $post = Post::create($request->all());
        $post->tags()->attach($request->tags);
        return redirect()
            ->route('user.post.show', ['post' => $post->id])
            ->with('success', 'Новый пост успешно создан');
    }


    //Страница пред.просмотра поста блога
    public function show(Post $post) {
        // можно просматривать только свои посты
        if ( ! $post->isAuthor()) {
            abort(404);
        }
        // сигнализирует о том, что это режим пред.просмотра
        session()->flash('preview', 'yes');
        // все опубликованные комментарии других пользователей
        $others = $post->comments()->whereNotNull('published_by');
        // и не опубликованные комментарии этого пользователя
        $comments = $post->comments()
            ->whereUserId(auth()->user()->id)
            ->whereNull('published_by')
            ->union($others)
            ->orderBy('created_at')
            ->paginate();
        return view('user.post.show', compact('post', 'comments'));
    }


    //Показывает форму редактирования поста
    public function edit(Post $post) {
        // редактировать можно только свои посты
        if ( ! $post->isAuthor()) {
            abort(404);
        }
        // редактировать можно не опубликованные
        if ($post->isVisible()) {
            abort(404);
        }
        // нужно сохранить flash-переменную, которая сигнализирует о том,
        // что кнопка редактирования была нажата в режиме пред.просмотра
        session()->keep('preview');
        return view('user.post.edit', compact('post'));
    }

    
    //Обновляет пост блога в базе данных
    public function update(PostRequest $request, Post $post) {
        // обновлять можно только свои посты
        if ( ! $post->isAuthor()) {
            abort(404);
        }
        // обновлять можно не опубликованные
        if ($post->isVisible()) {
            abort(404);
        }
        $post->update($request->all());
        $post->tags()->sync($request->tags);
        // кнопка редактирования может быть нажата в режиме пред.просмотра
        // или в личном кабинете пользователя, поэтому редирект разный
        $route = 'user.post.index';
        $param = [];
        if (session('preview')) {
            $route = 'user.post.show';
            $param = ['post' => $post->id];
        }
        return redirect()
            ->route($route, $param)
            ->with('success', 'Пост был успешно обновлен');
    }

    
    //Удаляет пост блога из базы данных
    public function destroy(Post $post) {
        // удалять можно только свои посты
        if ( ! $post->isAuthor()) {
            abort(404);
        }
        // удалять можно не опубликованные
        if ($post->isVisible()) {
            abort(404);
        }
        $post->delete();
        return redirect()
            ->route('user.post.index')
            ->with('success', 'Пост блога успешно удален');
    }
}
