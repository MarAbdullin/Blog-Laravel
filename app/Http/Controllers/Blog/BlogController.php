<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;

class BlogController extends Controller
{
    //главная страница блога (список всех опубликованных постов)
    public function index()
    {
        $posts = Post::whereNotNull('published_by')->with(['user', 'tags'])->orderBy('created_at', 'desc')->paginate(5);

        return view('blog.index', compact('posts'));
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

}
