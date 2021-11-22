<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Category extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /*
       Связь модели Category с моделью Category, позволяет получить все
       дочерние категории текущей категории
     */
    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /*
       Связь модели Category с моделью Category, позволяет получить
       родителя текущей категории
     */
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    //Возвращает список корневых категорий блога
    public static function roots()
    {
        return self::where('parent_id', 0)->get();
    }

    //Возвращает массив идентификаторов всех потомков категории
    public static function descendants($id)
    {
        $ids = [];

        $children = self::where('parent_id', $id)->get();

        foreach($children as $child){
            $ids[] = $child->id;

                // для каждого прямого потомка получаем его прямых потомков
                if ($child->children->count()) {
                    $ids = array_merge($ids, self::descendants($child->id));
            }
        }

        return $ids;

    }
}