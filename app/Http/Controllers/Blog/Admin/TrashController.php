<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ImageSaver;
use App\Models\Post;

class TrashController extends Controller
{
    
    public function __construct() {
        $this->middleware('perm:manage-posts')->only('index');
        $this->middleware('perm:delete-post')->only(['restore', 'destroy']);
    }

    //отображение удаленных постов
    public function index()
    {
        $posts = Post::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate();

        return view('admin.trash.index', compact('posts'));
    }

    //восстановление удаленного поста
    public function restore($id)
    {
        $id = (int) $id;

        Post::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('admin.trash.index')->with(['success' => 'Пост блога успешно восстановлен']);
    }

    //удаление поста из БД
    public function destroy($id, ImageSaver $imageSaver)
    {
        $id = (int) $id;

        $post = Post::withTrashed()->findOrFail($id);

        $imageSaver->remove($post); // удаляем основное изображение поста

        $post->forceDelete();
        
        return redirect()->route('admin.trash.index')->with('success', 'Пост блога удален навсегда');


    }

}
