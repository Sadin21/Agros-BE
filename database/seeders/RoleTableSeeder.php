<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    protected $permissionList = [
        'Super Admin' => [
            'QUERY',
            'REGISTER',
            'UPDATE',
            'DELETE',
        ],
        'Costumer' => [
            'QUERY',
            'SELF-UPDATE'
        ],
    ];

    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Costumer',
        ];

        foreach ($roles as $name) {
            $role = Role::create(['name' => $name]);
            $role->syncPermissions($this->permissionList[$name]);
        }
    }
}
