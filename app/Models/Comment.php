<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\User;


class Comment extends Model
{
    use HasFactory;

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

    

    
}
