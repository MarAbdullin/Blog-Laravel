<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    /*
        Возвращает 10 самых популярных тегов, то есть тегов, которые
        связаны с наибольшим количеством постов
    */ 
    public static function popular() 
    {
        return self::withCount('posts')->orderByDesc('posts_count')->limit(10)->get();
    }
}
