<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(User::all() as $user) {
            foreach(Role::all() as $role) {
                if ($user->id == 1 && $role->slug == 'root') { // один супер-админ
                    $user->roles()->attach($role->id);
                }
                if (in_array($user->id, [2,3]) && $role->slug == 'admin') { // два админа
                    $user->roles()->attach($role->id);
                }
                if ($user->id > 3 && $role->slug == 'user') { // обычные пользователи
                    $user->roles()->attach($role->id);
                }
            }
        }
    }
}
