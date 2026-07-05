<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\Acl\PermissionGeneratorService;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        PermissionGeneratorService::syncToDatabase();

        $accessAdminPermission = Permission::firstOrCreate(['name' => 'access_admin_panel']);

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminRole->syncPermissions(Permission::all());

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'username' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );
        
        $superAdmin->assignRole($superAdminRole);
    }
}
