<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blog\Auth\RegisterController;
use App\Http\Controllers\Blog\Auth\LoginController;
use App\Http\Controllers\Blog\User\IndexController;
use App\Http\Controllers\Blog\Auth\ForgotPasswordController;
use App\Http\Controllers\Blog\Auth\ResetPasswordController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Blog\Admin\PostController;
use App\Http\Controllers\Blog\Admin\CategoryController;
use App\Http\Controllers\Blog\Admin\TagController;
use App\Http\Controllers\Blog\Admin\UserController;
use App\Http\Controllers\Blog\Admin\RoleController;
use App\Http\Controllers\Blog\Admin\CommentController;
use App\Http\Controllers\Blog\Admin\AdminController;
use App\Http\Controllers\Blog\Admin\TrashController;
use App\Http\Controllers\Blog\User\PostController as UserPost;
use App\Http\Controllers\Blog\User\CommentController as UserComment;

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

// DB::listen(function($query) {
//     var_dump($query->sql, $query->bindings);
// });

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
Route::group([ 'as' => 'user.', 'prefix' => 'user', 'middleware' => ['auth'] ], function () {
    
    // главная страница личного кабинета
    Route::get('index', [IndexController::class ,'__invoke'])->name('index');

    //CRUD-операции над постами пользователя
    Route::resource('post', UserPost::class);

    //CRUD-операции над комментариями пользователя
    Route::resource('comment', UserComment::class, ['except' => ['create', 'store']]);


});

// группа маршрутов вывода постов
Route::group([ "as" => 'blog.', 'prefix' =>'/', 'middleware' => ['auth'] ], function(){
    
    // главная страница
    Route::get('', [BlogController::class, 'index'])->name('index');

    //страница с постами категории
    Route::get('category/{category:slug}', [BlogController::class, 'category'])->name('category');

    //страница с поставами одного тега
    Route::get('tag/{tag:slug}', [BlogController::class, 'tag'])->name('tag');

    //страница с постами одного автора
    Route::get('author/{user}', [BlogController::class, 'author'])->name('author');

    //страница просмотра поста
    Route::get('post/{post:slug}', [BlogController::class, 'post'])->name('post');

    //добавление комментария к посту
    Route::post('post/{post}/comment', [BlogController::class, 'comment'])->name('comment');

    //поиск поста по сайту
    Route::get('search', [BlogController::class, 'search'])->name('search');
});

//Панель управления: CRUD-операции над постами, категориями, тегами 
Route::group( [
    'as' => 'admin.',
    'prefix' => 'admin',  
    'middleware' => ['auth'],
], function(){
    //Главная страница панели управления
    Route::get('index', [AdminController::class, 'index'])->name('index');

    //CRUD-операции над постами блога
    Route::resource('post', PostController::class, ['except' => ['create', 'store']]);

    // доп.маршрут для показа постов категории
    Route::get('post/category/{category}', [PostController::class, 'category'])->name('post.category');
    
    // доп.маршрут, чтобы разрешить публикацию поста
    Route::put('post/enable/{post}', [PostController::class, 'enable'])->name('post.enable');
    
    // доп.маршрут, чтобы запретить публикацию поста
    Route::put('post/disable/{post}', [PostController::class, 'disable'])->name('post.disable');
  
    //CRUD-операции над категориями блога
    Route::resource('category', CategoryController::class, ['except' => ['show']]);

    //CRUD-операции над тегами блога
    Route::resource('tag', TagController::class, ['except' => ['show']]);

    //Просмотр и редактирование пользователей
    Route::resource('user', UserController::class, ['except' =>['creste', 'store', 'show', 'destroy']]);

    //CRUD-операции над ролями
    Route::resource('role', RoleController::class, ['except' => ['show']]);

    //CRUD-операции над комментариями
    Route::resource('comment', CommentController::class, ['except' => ['creste', 'store']]);
    
    //доп.маршрут, чтобы разрешить публикацию комментария
    Route::put('comment/enable/{comment}', [CommentController::class, 'enable'])->name('comment.enable');

    // доп.маршрут, чтобы запретить публикацию комментария
    Route::put('comment/disable/{comment}', [CommentController::class, 'disable'])->name('comment.disable');

    //удаление восстановление постов
    //просмотр удаленных постов
    Route::get('trash/index', [TrashController::class, 'index'])->name('trash.index');

    //восстановление поста
    Route::get('trash/restore/{id}', [TrashController::class, 'restore'])->name('trash.restore');

    //удаление поста
    Route::delete('trash/destroy/{id}', [TrashController::class, 'destroy'])->name('trash.destroy');

});



