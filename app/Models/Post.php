<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;


class Post extends Model
{
    use HasFactory;  use SoftDeletes, SoftCascadeTrait;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'excerpt',
        'content',
        'image',
    ];

    protected $dates = ['deleted_at'];

    protected $softCascade = ['comments'];

    //Поиск постов блога по заданным словам
    static function search($search)
    {
        // обрезаем поисковый запрос
        $search = iconv_substr($search, 0, 64);
        // удаляем все, кроме букв и цифр
        $search = preg_replace('#[^0-9a-zA-ZА-Яа-яёЁ]#u', ' ', $search);
        // сжимаем двойные пробелы
        $search = preg_replace('#\s+#u', ' ', $search);
        $search = trim($search);
        if (empty($search)) {
            return $post = Post::whereNull('id'); // возвращаем пустой результат
        }

        $relevance = "IF (`posts`.`name` LIKE '%" . $search . "%', 4, 0)";
        $relevance .= " + IF (`posts`.`content` LIKE '%" . $search . "%', 2, 0)";
        $relevance .= " + IF (`users`.`name` LIKE '%" . $search . "%', 1, 0)";
      

        $post = Post::distinct()->join('users', 'users.id', '=', 'posts.user_id')
            ->select('posts.*', DB::raw($relevance . ' as relevance'))
            ->where('posts.name', 'like', '%' . $search . '%')
            ->orWhere('posts.content', 'like', '%' . $search . '%')
            ->orWhere('users.name', 'like', '%' . $search . '%');
       
        $post->orderBy('relevance', 'desc');
        
        return $post;
       


    }


    /*
      Связь модели Post с моделью Tag, позволяет получить
      все теги поста
     */
    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /*
      Связь модели Post с моделью Category, позволяет получить
      родительскую категорию поста
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }

    /*
      Связь модели Post с моделью User, позволяет получить
      автора поста
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function editor() {
        return $this->belongsTo(User::class, 'published_by');
    }

    /*
      Связь модели Post с моделью Comment, позволяет получить
      комментарии поста
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Разрешить публикацию поста блог
    public function enable()
    {
        $this->published_by = Auth::user()->id;
        $this->update();
    }

    // Запретить публикацию поста блога
    public function disable()
    {
        $this->published_by = null;
        $this->update();
    }

    //Возвращает true, если публикация разрешена
    public function isVisible()
    {
        return ! is_null($this->published_by);
    }

    //Возвращает true, если пользователь является автором
    public function isAuthor() {
       return $this->user->id === Auth::user()->id;
    }

   
}
