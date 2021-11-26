<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Traits\HasRolesAndPermissions;
use App\Models\Post;
use App\Models\Comment;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // при добавлении значения в поле password, значение хешируется
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /*
       Связь модели User с моделью Post, позволяет получить все
       посты пользователя
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /*
       Связь модели User с моделью Comment, позволяет получить все
       комментарии пользователя
    */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /*
       Связь модели User с моделью Post, позволяет получить все
       посты опубликованные пользователем
    */
    public function publishedPosts()
    {
        return $this->hasMany(Post::class, 'published_by');
    }

    /*
       Связь модели User с моделью Comment, позволяет получить все
       комментарии опубликованные пользователем
    */
    public function publishedComments()
    {
        return $this->hasMany(Comment::class, 'published_by');
    }
    
   
}
