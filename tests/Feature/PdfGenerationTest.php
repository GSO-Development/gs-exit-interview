<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ExitSurvey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PdfGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_exit_survey_pdf_can_be_downloaded_without_errors(): void
    {
        Permission::create(['name' => 'download dossier', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->givePermissionTo('download dossier');

        $company = Company::factory()->create([
            'name' => 'George Steuart Health',
        ]);

        $survey = ExitSurvey::factory()->create([
            'company_id' => $company->id,
            'employee_name' => 'John Doe',
            'designation' => 'Executive',
            'department' => 'Operations',
        ]);

        $response = $this->actingAs($user)->get(route('admin.surveys.pdf', $survey));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
