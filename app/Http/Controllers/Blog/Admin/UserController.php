<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('perm:manage-users')->only('index');
        $this->middleware('perm:edit-user')->only(['edit', 'update']);
    }
    
    public function index()
    {
        $users = User::paginate(8);

        return view('admin.user.index', compact('users'));
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show(User $user)
    {
        //
    }

    //Показывает форму для редактирования пользователя
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    
    public function update(UserRequest $request, User $user)
    {
        if($request->change_password) $user->update($request->all());
        else $user->update($request->except('password'));

        return redirect()->route('admin.user.index')->with(['success' => 'Пользователь успешно обновлен']);
    }

    
    public function destroy(User $user)
    {
        //
    }
}
