<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

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
        $allroles = Role::all();
        $allperms = Permission::all();
        return view('admin.user.edit', compact('user', 'allroles', 'allperms'));
    }

    
    public function update(UserRequest $request, User $user)
    {
        if($request->change_password) $user->update($request->all());
        else $user->update($request->except('password'));

        if (Auth::user()->hasPermAnyWay('assign-permission')) {
            $user->roles()->sync($request->roles);
        }
        if (Auth::user()->hasPermAnyWay('assign-permission')) {
            $user->permissions()->sync($request->perms);
        }

        return redirect()->route('admin.user.index')->with(['success' => 'Пользователь успешно обновлен']);
    }

    
    public function destroy(User $user)
    {
        //
    }
}
