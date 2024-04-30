<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Permission,Role};

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
            ],
            [
                'name' => 'company-list',
                'module' => 'company'
            ],
            [
                'name' => 'company-create',
                'module' => 'company'
            ],
            [
                'name' => 'company-edit',
                'module' => 'company'
            ],
            [
                'name' => 'company-delete',
                'module' => 'company'
            ],
            [
                'name' => 'report-list',
                'module' => 'report'
            ],
            [
                'name' => 'report-create',
                'module' => 'report'
            ],
            [
                'name' => 'report-edit',
                'module' => 'report'
            ],
            [
                'name' => 'report-delete',
                'module' => 'report'
            ],
            [
                'name' => 'payment-list',
                'module' => 'payment'
            ],
            [
                'name' => 'payment-create',
                'module' => 'payment'
            ],
            [
                'name' => 'payment-edit',
                'module' => 'payment'
            ],
            [
                'name' => 'payment-delete',
                'module' => 'payment'
            ],
            [
                'name' => 'note-list',
                'module' => 'note'
            ],
            [
                'name' => 'note-create',
                'module' => 'note'
            ],
            [
                'name' => 'note-edit',
                'module' => 'note'
            ],
            [
                'name' => 'note-delete',
                'module' => 'note'
            ],
            [
                'name' => 'location-list',
                'module' => 'location'
            ],
            [
                'name' => 'location-create',
                'module' => 'location'
            ],
            [
                'name' => 'location-edit',
                'module' => 'location'
            ],
            [
                'name' => 'location-delete',
                'module' => 'location'
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
