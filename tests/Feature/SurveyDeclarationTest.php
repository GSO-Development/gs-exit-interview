<?php

namespace Tests\Feature;

use App\Models\ExitResponse;
use App\Models\ExitSurvey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyDeclarationTest extends TestCase
{
    use RefreshDatabase;

    public function test_wrong_passcode_grants_no_access_to_declaration_or_questionnaire(): void
    {
        $survey = ExitSurvey::factory()->create();

        $this->post(route('survey.auth', $survey->token), ['passcode' => 'WRONG-CODE'])
            ->assertSessionHasErrors('passcode');

        $this->get(route('survey.show', $survey->token))->assertViewIs('survey.gate');

        $this->postJson(route('survey.declaration', $survey->token), ['declaration' => true])
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Your session has expired. Please re-enter your access passcode.');

        $this->assertFalse($survey->fresh()->declaration_accepted);
    }

    public function test_correct_passcode_shows_declaration_and_withholds_questionnaire(): void
    {
        $survey = ExitSurvey::factory()->create();

        $this->post(route('survey.auth', $survey->token), ['passcode' => $survey->access_code])
            ->assertRedirect(route('survey.show', $survey->token));

        $this->get(route('survey.show', $survey->token))
            ->assertViewIs('survey.declaration')
            ->assertSee('Team Member Exit Interview Declaration')
            ->assertDontSee('name="survey_auth_key"', false);
    }

    public function test_accepting_declaration_records_acceptance_and_unlocks_questionnaire(): void
    {
        $survey = ExitSurvey::factory()->create();
        $this->withSession(['survey_authed_'.$survey->id => true]);

        $this->postJson(route('survey.declaration', $survey->token), ['declaration' => true])
            ->assertOk()
            ->assertJsonPath('redirect', route('survey.show', $survey->token));

        $survey->refresh();
        $this->assertTrue($survey->declaration_accepted);
        $this->assertNotNull($survey->declaration_accepted_at);
        $this->assertSame('127.0.0.1', $survey->declaration_accepted_ip);

        $this->get(route('survey.show', $survey->token))->assertViewIs('survey.show');
    }

    public function test_declaration_requires_the_confirmation_checkbox(): void
    {
        $survey = ExitSurvey::factory()->create();
        $this->withSession(['survey_authed_'.$survey->id => true]);

        $this->postJson(route('survey.declaration', $survey->token), [])
            ->assertUnprocessable();

        $this->assertFalse($survey->fresh()->declaration_accepted);
    }

    public function test_questionnaire_submission_is_rejected_without_declaration(): void
    {
        $survey = ExitSurvey::factory()->create();
        $this->withSession(['survey_authed_'.$survey->id => true]);

        $this->post(route('survey.submit', $survey->token), $this->validSubmission())
            ->assertRedirect(route('survey.show', $survey->token))
            ->assertSessionHasErrors(['declaration' => 'You must accept the declaration before submitting the exit interview.']);

        $this->assertDatabaseMissing('exit_responses', ['exit_survey_id' => $survey->id]);
        $this->assertSame('pending', $survey->fresh()->status);
    }

    public function test_questionnaire_submission_succeeds_after_declaration(): void
    {
        $survey = ExitSurvey::factory()->declarationAccepted()->create();
        $this->withSession(['survey_authed_'.$survey->id => true]);

        $this->post(route('survey.submit', $survey->token), $this->validSubmission())
            ->assertRedirect(route('survey.success'));

        $this->assertDatabaseHas('exit_responses', ['exit_survey_id' => $survey->id]);
        $this->assertSame('submitted', $survey->fresh()->status);
    }

    public function test_refresh_after_acceptance_goes_straight_to_questionnaire(): void
    {
        $survey = ExitSurvey::factory()->declarationAccepted()->create();
        $this->withSession(['survey_authed_'.$survey->id => true]);

        $this->get(route('survey.show', $survey->token))
            ->assertViewIs('survey.show')
            ->assertSee('checked', false);
        $this->get(route('survey.show', $survey->token))->assertViewIs('survey.show');
    }

    public function test_refresh_before_acceptance_keeps_showing_declaration(): void
    {
        $survey = ExitSurvey::factory()->create();
        $this->withSession(['survey_authed_'.$survey->id => true]);

        $this->get(route('survey.show', $survey->token))->assertViewIs('survey.declaration');
        $this->get(route('survey.show', $survey->token))->assertViewIs('survey.declaration');
    }

    public function test_expired_link_is_rejected_at_every_step(): void
    {
        $survey = ExitSurvey::factory()->expired()->create();

        $this->get(route('survey.show', $survey->token))->assertViewIs('survey.unavailable');

        $this->post(route('survey.auth', $survey->token), ['passcode' => $survey->access_code])
            ->assertRedirect(route('survey.show', $survey->token))
            ->assertSessionMissing('survey_authed_'.$survey->id);

        $this->withSession(['survey_authed_'.$survey->id => true]);

        $this->get(route('survey.show', $survey->token))->assertViewIs('survey.unavailable');

        $this->postJson(route('survey.declaration', $survey->token), ['declaration' => true])
            ->assertForbidden()
            ->assertJsonPath('message', 'This survey link has expired or is no longer valid.');

        $this->post(route('survey.submit', $survey->token), $this->validSubmission())
            ->assertForbidden();

        $this->assertFalse($survey->fresh()->declaration_accepted);
        $this->assertDatabaseMissing('exit_responses', ['exit_survey_id' => $survey->id]);
    }

    public function test_survey_submission_auto_populates_signature_and_signed_date_when_omitted(): void
    {
        $survey = ExitSurvey::factory()->create([
            'employee_name' => 'Alice Wonder',
            'declaration_accepted' => true,
            'declaration_accepted_at' => now(),
            'declaration_accepted_ip' => '127.0.0.1',
        ]);

        $payload = $this->validSubmission();
        unset($payload['team_member_signature'], $payload['team_member_signed_date']);

        $response = $this->withSession(['survey_authed_'.$survey->id => true])
            ->post(route('survey.submit', $survey->token), $payload);

        $response->assertRedirect(route('survey.success'));

        $this->assertDatabaseHas('exit_responses', [
            'exit_survey_id' => $survey->id,
            'team_member_signature' => 'Alice Wonder',
        ]);

        $exitResponse = ExitResponse::where('exit_survey_id', $survey->id)->first();
        $this->assertNotNull($exitResponse);
        $this->assertSame('Alice Wonder', $exitResponse->team_member_signature);
        $this->assertSame(now()->toDateString(), $exitResponse->team_member_signed_date->toDateString());
    }

    /**
     * A questionnaire payload that passes all submission validation rules.
     *
     * @return array<string, mixed>
     */
    private function validSubmission(): array
    {
        return [
            'area_ratings' => array_fill(0, 20, 4),
            'main_reason_to_leave' => 'Career growth',
            'other_reasons_to_leave' => ['Salary'],
            'liked_most_aspects' => ['Team'],
            'prevented_resignation' => 'Better growth opportunities.',
            'recommend_company' => 'Yes',
            'open_to_reapply' => 'Yes',
            'team_member_signature' => 'Jane Doe',
            'team_member_signed_date' => now()->toDateString(),
            'terms_agreed' => '1',
        ];
    }
}
