<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleAndPermissionSeeder extends Seeder
{
    public function __construct(private Permission $permission, private Role $role, private User $user){}
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'role-list',
                'module' => 'role'
            ],
            [
                'name' => 'role-create',
                'module' => 'role'
            ],
            [
                'name' => 'role-edit',
                'module' => 'role'
            ],
            [
                'name' => 'role-delete',
                'module' => 'role'
            ],
            [
                'name' => 'user-list',
                'module' => 'user'
            ],
            [
                'name' => 'user-create',
                'module' => 'user'
            ],
            [
                'name' => 'user-edit',
                'module' => 'user'
            ],
            [
                'name' => 'user-delete',
                'module' => 'user'
            ]
         ];

         foreach ($permissions as $permission) {
            $this->permission->create([
                'name' => $permission['name'],
                'module' => $permission['module']
            ]);
         }

         $role = $this->role->create(['name' => 'admin']);
         $role->givePermissionTo($this->permission->all());
         $this->user->find(1)->assignRole('admin');         
    }
}
