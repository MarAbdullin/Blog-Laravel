<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\Models\User;

class Permission extends Model
{
    use HasFactory;
 
    // отношение многие ко многим прав и ролей
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission')->withTimestamps();
    }

    // отношение многие ко многим прав и юзеров
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permission')->withTimestamps();
    }

    
}
