<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Tag;
use App\Models\Category;


class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer([
            'layout.part.categories', // меню в левой колонке в публичной части
            'admin.post.part.categories', // выбор категории поста при редактировании
            'admin.part.parents', // выбор родителя категории при редактировании
            'admin.part.all-ctgs', // все категории в административной части
        ], function($view) {

                static $items = null;
                if (is_null($items)) {
                    $items = Category::all();
                }
                $view->with(['items' => $items]);
            });
        
        View::composer('layout.part.popular-tags', function($view) {
            $view->with(['items' => Tag::popular()]);
        });

        View::composer('admin.part.all-tags', function($view){
            $view->with(['items' => Tag::all()]);
        });
    }
}
