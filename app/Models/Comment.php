<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;


class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'published_by',
        'content',
    ];

    protected $perPage = 5;

    /*
      Связь модели Comment с моделью Post, позволяет получить
      пост комментария
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /*
      Связь модели Comment с моделью User, позволяет получить
      пользователя комментария
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
      Связь модели Comment с моделью User, позволяет получить
      пользователя опубликовавшего комментарий
    */
    public function editor()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    // Разрешить публикацию комментария блог
    public function enable()
    {
        $this->published_by = Auth::user()->id;
        $this->update();
    }

    // Запретить публикацию комментария блог
    public function disable()
    {
        $this->published_by = null;
        $this->update();
    }

     /*
      Номер страницы пагинации, на которой расположен комментарий;
      учитываются все комментарии, в том числе не опубликованные
     */
    public function adminPageNumber()
    {
         $post = $this->post;
         $comments = $post->comments()->orderBy('created_at')->get();

        if($comments->count() == 0){
            return 1;  //если в коллекции нет комментариев 
        } 

        if($comments->count() <= $this->getPerPage()){
            return 1; // если в коллекции комментариев меньше чем количества элементов на странице
        } 

        foreach($comments as $index => $comment){
            if($this->id == $comment->id){
                break;
            }
        }
        return (int) ceil(($index+1) / $this->getPerPage()); // вернет номер страницы(индекс комментария в коллекции / количество элементов на странице)
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

    //Номер страницы пагинации, на которой расположен комментарий
    //все опубликованные + не опубликованные этого пользователя
    public function userPageNumber()
    {
        // все опубликованные комментарии других пользователей
        $others = $this->post->comments()->whereNotNull('published_by');
        // и не опубликованные комментарии этого пользователя
        $comments = $this->post->comments()
                ->whereUserId(Auth::user()->id)
                ->whereNull('published_by')
                ->union($others)
                ->orderBy('created_at')
                ->get();
        
        if($comments->count() == 0){
            return 1;
        }

        if($comments->count() <= $this->getPerPage()){
            return 1;
        }

        foreach($comments as $index => $comment){
            if($this->id == $comment->id){
                break;
            }
        }
        return (int) ceil(($index+1) / $this->getPerPage());
    }


    

    
}
