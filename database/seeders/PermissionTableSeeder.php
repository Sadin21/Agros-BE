<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'QUERY',
            'REGISTER',
            'UPDATE',
            'SELF-UPDATE',
            'DELETE',
            // 'USER.LOGIN',
            // 'USER.LOGOUT',
            // 'USER.REFRESH',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
