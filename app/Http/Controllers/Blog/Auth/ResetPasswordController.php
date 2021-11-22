<?php

namespace App\Http\Controllers\Blog\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class ResetPasswordController extends Controller
{
    public function form($token)
    {
        return view('auth.reset', compact('token'));
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
           $request->only('email', 'password', 'password_confirmation', 'token'),
           
           function($user, $password) use($request){

                $user->forceFill(['password' => $password]);
                    
                $user->save();
                
                event(new PasswordReset($user));
           }
        );

        return $status === Password::PASSWORD_RESET
                        ? redirect()->route('auth.login')->with(['success' => __($status)])
                        : back()->withErrors(['email' => __($status)]);

    }
}
