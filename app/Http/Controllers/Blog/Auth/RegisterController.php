<?php

namespace App\Http\Controllers\Blog\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{   

    public function show()
    {
        return view('auth.register');
    }

    public function create(RegisterRequest $request)
    {
        if($request->isMethod('post')){
            
            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            Auth::login($user);
            
            return redirect()
                ->route('user.index')
                ->with('success', 'Вы вошли в личный кабинет');
        }


        
        return redirect()
        ->route('auth.register')
        ->with('success', 'У вас не получилось зарегистрироваться');
    }
}
