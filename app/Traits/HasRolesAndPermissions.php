<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRolesAndPermissions 
{
     // отношение многие ко многим юзеров и ролей
     public function roles()
     {
         return $this->belongsToMany(Role::class, 'user_role')->withTimestamps();
     }
 
     // отношение многие ко многим юзеров и прав
     public function permissions()
     {
         return $this->belongsToMany(Permission::class, 'user_permission')->withTimestamps();
     }

     //имеет ли юзер роль $role
    public function hasRole($role)
    {
        return $this->roles->contains('slug', $role);
    }

    //имеет ли пользователь право $permission
    public function hasPerm($permission)
    {
        return $this->permissions->contains('slug', $permission);
    }

    //имеет ли юзер право $permission через одну из своих ролей
    public function hasPermViaRoles($permission)
    {
        foreach($this->roles as $role){
            if($role->permissions->contains('slug', $permission)){
                return true;
            }
            return false;
        }

    }

    //имеет ли юзер право $permission либо напрямую либо через одну из своих полей
     public function hasPermAnyWay($permission)
     {
         return $this->hasPermViaRoles($permission) || $this->hasPerm($permission);
     }

    //Имеет текущий юзер все права из $permissions либо напрямую, либо через одну из своих ролей
    public function hasAllPerms(...$permissions)
    {
        foreach($permissions as $permission){
            $condition = $this->hasPermViaRoles($permission) || $this->hasPerm($permission);

            if(!$condition){
                return false;
            }
        }
        return true;
    }

    //Имеет текущий юзер любое право из $permissions либо напрямую, либо через одну из своих ролей
    public function hasAnyPerms(...$permissions)
    {
        foreach($permissions as $permission){
            if($this->hasPermViaRoles($permission) || $this->hasPerm($permission)){
                return true;
            }
        }
        return false;
    }

    //Возвращает массив всех прав текущего юзера
    public function getAllPerms() 
    {
        return $this->permissions()->pluck('slug')->toarray();
    }

    //Возвращает массив всех прав текущего юзера, которые у него есть через его роли
    public function getAllPermsViaRoles() 
    {
        $permissions = [];

        foreach($this->roles as $role){
            foreach($role->permissions as $permission){
                $permissions[] = $permission->slug;
            }
        }
        return array_values(array_unique($permissions));
    }

    //Возвращает массив всех прав текущего юзера, либо напрямую, либо через одну из своих ролей
    public function getAllPermsAnyWay() {
        $perms = array_merge(
            $this->getAllPerms(),
            $this->getAllPermsViaRoles()
        );
        return array_values(array_unique($perms));
    }

    //Возвращает массив всех ролей текущего юзера
    public function getAllRoles()
    {
        return $this->roles()->pluck('slug')->toArray();
    }

    //Добавить текущему юзеру права $permissions (в дополнение к тем, что уже были назначены ранее)
    public function assignPermissions(...$permissions)
    {
        $permissions = Permission::whereIn('slug', $permission)->get();

        if($permissions->count() === 0){
            return $this;
        }

        $this->permissions()->syncWithoutDetaching($permissions);
        return $this;
    }

    //Отнять у текущего юзера права $permissions (из числа тех, что были назначены ранее)
    public function unassignPermissions(...$permissions)
    {
        $permissions = Permission::whereIn('slug', $permissions)->get();

        if($permissions->count() === 0){
            return $this;
        }

        $this->permissions()->detach($permissions);
        return $this;
    }

    //Назначить текущему юзера права $permissions (отнять при этом все ранее назначенные права)
    public function refreshPermissions(...$permissions)
    {
        $permissions = Permission::whereIn('slug', $permission)->get();

        if($permissions->count() === 0){
            return $this;
        }

        $this->permissions()->sync($permissions);
        return $this;
    }

    //Добавить текущему юзеру роли $roles (в дополнение к тем, что уже были назначены)
    public function assignRoles(...$roles)
    {
        $roles = Role::whereIn('slug', $roles)->get();

        if($roles->count() === 0){
            return $this;
        }

        $this->roles()->syncWithoutDetaching($roles);
        return $this;
    }

    //Отнять у текущего юзера роли $roles (из числа тех, что были назначены ранее)
    public function unassignRoles(...$roles)
    {
        $roles = Role::whereIn('slug', $roles)->get();

        if($roles->count() === 0){
            return $this;
        }

        $this->roles()->detach($roles);
        return $this;
    }

    //Назначить текущему юзеру роли $roles (отнять при этом все ранее назначенные роли)
    public function refreshRoles(...$roles)
    {
        $roles = Role::whereIn('slug', $roles)->get();

        if($roles->count() === 0){
            return $this;
        }

        $this->roles()->sync($roles);
        return $this;
    }
        


}