<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view all surveys',
            'view own surveys',
            'send surveys',
            'send surveys any company',
            'download dossier',
            'manage links',
            'view analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $hrManager = Role::firstOrCreate(['name' => 'subsidiary_hr_manager']);
        $hrManager->givePermissionTo([
            'view own surveys',
            'send surveys',
            'download dossier',
            'manage links',
            'view analytics',
        ]);
    }
}
