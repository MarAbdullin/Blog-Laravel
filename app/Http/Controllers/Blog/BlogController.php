<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    //главная страница блога (список всех опубликованных постов)
    public function index()
    {
        $posts = Post::whereNotNull('published_by')->with(['user', 'tags'])->orderBy('created_at', 'desc')->paginate(5);

        return view('blog.index', compact('posts'));
    }

    //поиск поста по его названию, названию тега и имени автора
    public function search(Request $request)
    {    
        $search = $request->input('query');

        $posts = Post::search($search);
        $posts = $posts->paginate()->withQueryString();
     
        return view('blog.search', compact('posts', 'search'));

    }

    //страница поста с комментариями
    public function post(Post $post)
    {   
        $comments = $post->comments()->whereNotNull('published_by')->paginate(5);
        
        return view('blog.post', compact('post', 'comments'));
    }

    //cписок постов блога выбранной категории
    public function category(Category $category)
    {
        $descendants = array_merge(Category::descendants($category->id), [$category->id]);
        $posts = Post::whereIn('category_id', $descendants)
                    ->whereNotNull('published_by')
                    ->with(['user', 'tags'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

        return view('blog.category', compact('category', 'posts'));
    }

    //Список постов блога выбранного автора
    public function author(User $user)
    {
        $posts = $user->posts()->whereNotNull('published_by')->with(['user', 'tags'])->orderBy('created_at', 'desc')->paginate(5);

        return view('blog.author', compact('user', 'posts'));
    }

    //Список постов блога с выбранным тегом
    public function tag(Tag $tag)
    {
        $posts = $tag->posts()->whereNotNull('published_by')->with(['user', 'tags'])->orderBy('created_at', 'desc')->paginate(5);

        return view('blog.tag', compact('tag', 'posts'));
    }

    //Сохраняет новый комментарий в базу данных
    public function comment(CommentRequest $request)
    {
        $request->merge(['user_id' => Auth::user()->id]);

        $message = 'Комментарий добавлен, будет доступен после проверки';

        if (Auth::user()->hasPermAnyWay('publish-comment')){
            $request->merge(['published_by' => Auth::user()->id]);
            $message = 'Комментарий добавлен и уже доступен для просмотра';
        }

        $comment = Comment::create($request->all());

        $post = $comment->post;

        $page = $post->comments()->whereNotNull('published_by')->paginate()->lastPage();

        return redirect()
        ->route('blog.post', ['post' => $post->slug, 'page' => $page])
        ->withFragment('comment-list')
        ->with('success', $message);
    }



}
