<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'excerpt',
        'content',
        'image',
    ];


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
    public function isAuthor()
    {
        return $this->user->id === Auth::user()->id;
    }

   
}
