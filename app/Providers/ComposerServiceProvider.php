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
        View::composer(['layout.part.categories', 'admin.post.part.categories'], function($view) {
            static $items = null;
            if (is_null($items)) {
                $items = Category::all();
            }
            $view->with(['items' => $items]);
        });
        
        View::composer('layout.part.popular-tags', function($view) {
            $view->with(['items' => Tag::popular()]);
        });

        View::composer('admin.post.part.all-tags', function($view){
            $view->with(['items' => Tag::all()]);
        });
    }
}
