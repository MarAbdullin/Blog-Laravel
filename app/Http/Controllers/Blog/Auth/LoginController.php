<?php

namespace App\Http\Controllers\Blog\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    
    public function show()
    {
        return view('auth.login');
    }

    
    public function auth(AuthRequest $request)
    {
        $user = $request->only('email', 'password');

        if(Auth::attempt($user, $request->has('remember'))){
            return redirect()
                ->route('user.index')
                ->with('success', 'Вы вошли в личный кабинет');
        }

        return redirect()
            ->route('auth.login')
            ->withErrors('Неверный логин или пароль');
        
    }

    public function logout()
    {
        Auth::logout();

        return redirect()
            ->route('auth.login')
            ->with('Вы вышли из личного кабинета');
    }


}
