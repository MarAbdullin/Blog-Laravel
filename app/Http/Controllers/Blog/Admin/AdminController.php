<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $posts = Post::whereNull('published_by')->orderBy('created_at')->limit(5)->get();
        $comments = Comment::whereNull('published_by')->orderBy('created_at')->limit(5)->get();

        return view('admin.index', compact('posts', 'comments'));
    }
}
