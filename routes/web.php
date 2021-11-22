<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blog\Auth\RegisterController;
use App\Http\Controllers\Blog\Auth\LoginController;
use App\Http\Controllers\Blog\User\IndexController;
use App\Http\Controllers\Blog\Auth\ForgotPasswordController;
use App\Http\Controllers\Blog\Auth\ResetPasswordController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Blog\Admin\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//  Группа  маршрутов аутентификации, регистрации, сброса пороля.

Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
    // отображение формы регистрации и её обработчик
    Route::get('register', [RegisterController::class, 'show'])->middleware('guest')->name('register');
     
    Route::post('register', [RegisterController::class, 'create'])->middleware('guest')->name('create');

    // отображение формы аутентификации её обработчик, выход
    Route::get('login', [LoginController::class, 'show'])->middleware('guest')->name('login');

    Route::post('login', [loginController::class, 'auth'])->middleware('guest')->name('auth');

    Route::get('logout', [LoginController::class ,'logout'])->middleware('auth')->name('logout');

    // отображение формы ввода почты и отправка ссылки на эту почту
    Route::get('forgot-password', [ForgotPasswordController::class, 'form'])->middleware('guest')->name('forgot-form');

    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->middleware('guest')->name('sendResetLink');

    // отображение ссылки на сброс пароля и его обработчик
    Route::get('reset-password/{token}/', [ResetPasswordController::class, 'form'])->middleware('guest')->name('reset-form');
    
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->middleware('guest')->name('reset-password');

});

// страница личного кабинета  
Route::group([ 'as' => 'user.', 'prefix' => 'user', 'namespace' => 'User', 'middleware' => ['auth'] ], function () {
    
    Route::get('index', [IndexController::class ,'__invoke'])->name('index');
});

// группа маршрутов вывода постов
Route::group([ "as" => 'blog.', 'prefix' =>'/', 'middleware' => ['auth'] ], function(){
    
    // главная страница
    Route::get('', [BlogController::class, 'index'])->name('home');

    //страница с постами категории
    Route::get('category/{category:slug}', [BlogController::class, 'category'])->name('category');

    //страница с поставами одного тега
    Route::get('tag/{tag:slug}', [BlogController::class, 'tag'])->name('tag');

    //страница с постами одного автора
    Route::get('author/{user}', [BlogController::class, 'author'])->name('author');

    //страница просмотра поста
    Route::get('post/{post:slug}', [BlogController::class, 'post'])->name('post');
});

//Панель управления: CRUD-операции над постами, категориями, тегами 
Route::group( [
    'as' => 'admin.',
    'prefix' => 'admin',  
    'middleware' => ['auth'],
], function(){

    //CRUD-операции над постами блога
    Route::resource('post', PostController::class, ['except' => ['create', 'store']]);

    // доп.маршрут для показа постов категории
    Route::get('post/category/{category}', [PostController::class, 'category'])->name('post.category');
    
    // доп.маршрут, чтобы разрешить публикацию поста
    Route::get('post/enable/{post}', [PostController::class, 'enable'])->name('post.enable');
    
    // доп.маршрут, чтобы запретить публикацию поста
    Route::get('post/disable/{post}', [PostController::class, 'disable'])->name('post.disable');
  

});



