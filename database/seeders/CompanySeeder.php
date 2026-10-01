<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['name' => 'GS Solutions', 'code' => 'GSS', 'headcount' => 180],
            ['name' => 'GS Health', 'code' => 'GSH', 'headcount' => 240],
            ['name' => 'GS Tea', 'code' => 'GST', 'headcount' => 310],
            ['name' => 'GS Plantations', 'code' => 'GSP', 'headcount' => 450],
            ['name' => 'GS Logistics', 'code' => 'GSL', 'headcount' => 125],
            ['name' => 'GS Finance', 'code' => 'GSF', 'headcount' => 90],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(['code' => $company['code']], $company);
        }
    }
}
