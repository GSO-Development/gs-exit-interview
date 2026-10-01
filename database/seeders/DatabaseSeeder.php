<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CompanySeeder::class,
        ]);

        // Default global settings
        Setting::set('default_token_validity_days', 14);

        // Create default Super Admin (no company restriction — null = all)
        $superAdmin = User::firstOrCreate(
            ['email' => 'manoj@georgesteuart.lk'],
            [
                'name' => 'Manoj Pilimatalawwe',
                'password' => Hash::make('password'),
                'company_id' => null,
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Also add admin@gsoptimize.lk
        $admin = User::firstOrCreate(
            ['email' => 'admin@gsoptimize.lk'],
            [
                'name' => 'Group HR Director',
                'password' => Hash::make('password'),
                'company_id' => null,
            ]
        );
        $admin->assignRole('super_admin');

        // Create a test subsidiary HR manager — assign to the first company (GS Solutions)
        $firstCompany = Company::first();
        $hrManager = User::firstOrCreate(
            ['email' => 'hr@gsoptimize.lk'],
            [
                'name' => 'Subsidiary HR Manager',
                'password' => Hash::make('password'),
                'company_id' => $firstCompany?->id,
            ]
        );
        $hrManager->assignRole('subsidiary_hr_manager');
    }
}
