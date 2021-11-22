<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;


class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Role::all() as $role) {
            if ($role->slug == 'root') { // для роли супер-админа все права
                foreach (Permission::all() as $perm) {
                    $role->permissions()->attach($perm->id);
                }
            }
            if ($role->slug == 'admin') { // для роли администратора поменьше
                $slugs = [
                    'create-post', 'edit-post', 'publish-post', 'delete-post',
                    'create-comment', 'edit-comment', 'publish-comment', 'delete-comment'
                ];
                foreach ($slugs as $slug) {
                    $perm = Permission::where('slug', $slug)->first();
                    $role->permissions()->attach($perm->id);
                }
            }
            if ($role->slug == 'user') { // для обычного пользователя совсем чуть-чуть
                $slugs = ['create-post', 'create-comment'];
                foreach ($slugs as $slug) {
                    $perm = Permission::where('slug', $slug)->first();
                    $role->permissions()->attach($perm->id);
                }
            }
        }
    }
}
