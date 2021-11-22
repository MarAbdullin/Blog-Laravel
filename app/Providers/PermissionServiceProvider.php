<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class PermissionServiceProvider extends ServiceProvider
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
        // директива blade (проверка имеетли юзер это право)
        Blade::if('perm', function($perm){
            return Auth::check() && Auth::user()->hasPermAnyWay($perm);
        });
       

        // директива blade (имеет ли юзер эти права)
        Blade::if('allperm', function($perms){
            return Auth::check() && Auth::user()->hasAllPerms($perms);
        });
     

        // директива blade (имеет ли юзер одно из правил)
        Blade::if('anyperm', function($perms){
            return Auth::check() && Auth::user()->hasAnyPerms($perms);
        });
       

    }
}
