<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    //права на доступ к действиям контроллера
    public function __construct() {
        $this->middleware('perm:manage-roles')->only('index');
        $this->middleware('perm:create-role')->only(['create', 'store']);
        $this->middleware('perm:edit-role')->only(['edit', 'update']);
        $this->middleware('perm:delete-role')->only('destroy');
    }
    
    //Показывает список всех ролей пользователя
    public function index()
    {
        $roles = Role::paginate(8);

        return view('admin.role.index', compact('roles'));
    }

    //Показывает форму для создания роли
    public function create()
    {
        $allperms = Permission::all();
        return view('admin.role.create', compact('allperms'));
    }

    //Сохраняет новую роль в базу данных
    public function store(RoleRequest $request)
    {
        $role = Role::create($request->all());

        $role->permissions()->attach($request->perms ?? []);

        return redirect()->route('admin.role.index')->with(['success' => 'Новая роль успешно создана']);
    }

   
    public function show(Role $role)
    {
        //
    }

    //Показывает форму для редактирования роли
    public function edit(Role $role)
    {
        $allperms = Permission::all();
        return view('admin.role.edit', compact('role', 'allperms'));
    }

    //Обновляет роль в базе данных
    public function update(RoleRequest $request, Role $role)
    {
        if($role->id === 1){
            return redirect()->route('admin.role.index')->withErrors('Эту роль нельзя редактировать');
        }

        $role->update($request->all());

        $role->permissions()->sync($request->perms ?? []);

        return redirect()->route('admin.role.index')->with(['success' => 'Роль была успешно отредактирована']);


    }

    //Удаляет роль из базы данных
    public function destroy(Role $role)
    {
        if($role->id === 1){
            return redirect()->route('admin.role.index')->withErrors('Эту роль нельзя редактировать');
        }

        $role->delete();

        return redirect()->route('admin.role.index')->with(['success' => 'Роль была успешно удалена']);
    }
}