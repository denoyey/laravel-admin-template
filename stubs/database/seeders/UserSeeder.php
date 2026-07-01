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

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($accessAdminPermission);

        $itRole = Role::firstOrCreate(['name' => 'it']);
        $itRole->givePermissionTo($accessAdminPermission);

        $adminEmail = env('DEFAULT_ADMIN_EMAIL');
        $adminPassword = env('DEFAULT_ADMIN_PASSWORD');

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'username' => 'Admin KSA',
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ]
        );
        $admin->assignRole($adminRole);

        $itEmail = env('DEFAULT_IT_EMAIL');
        $itPassword = env('DEFAULT_IT_PASSWORD');

        $it = User::firstOrCreate(
            ['email' => $itEmail],
            [
                'username' => 'IT Support',
                'password' => Hash::make($itPassword),
                'role' => 'it',
            ]
        );
        $it->assignRole($itRole);

        $iexassEmail = env('DEFAULT_IEXASS_EMAIL');
        $iexassPassword = env('DEFAULT_IEXASS_PASSWORD');

        $iexass = User::firstOrCreate(
            ['email' => $iexassEmail],
            [
                'username' => 'Iexass',
                'password' => Hash::make($iexassPassword),
                'role' => 'super_admin',
            ]
        );
        $iexass->assignRole($superAdminRole);
    }
}
