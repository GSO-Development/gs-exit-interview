<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\SettingsController;
use App\Models\ExitResponse;
use App\Models\ExitSurvey;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PublicSurveyController extends Controller
{
    public function show(string $token)
    {
        $survey = ExitSurvey::where('token', $token)->with('company')->firstOrFail();

        if (! $survey->isAccessible()) {
            return view('survey.unavailable', ['survey' => $survey]);
        }

        // If survey has an access code and employee hasn't entered it, show passcode gate
        if (! $this->hasVerifiedPasscode($survey)) {
            return view('survey.gate', ['survey' => $survey]);
        }

        // The questionnaire is never rendered until the declaration has been accepted
        if (! $survey->declaration_accepted) {
            return view('survey.declaration', ['survey' => $survey]);
        }

        // Generate HMAC key for secure form submission
        $surveyAuthKey = $survey->access_code
            ? hash_hmac('sha256', $survey->token.':'.$survey->access_code, (string) config('app.key'))
            : '';

        $termsAndConditions = Setting::get(
            'exit_survey_terms_and_conditions',
            SettingsController::defaultTermsAndConditions()
        );

        return view('survey.show', [
            'survey' => $survey,
            'surveyAuthKey' => $surveyAuthKey,
            'termsAndConditions' => $termsAndConditions,
        ]);
    }

    /**
     * Record the employee's acceptance of the exit interview declaration.
     *
     * Re-validates the link, expiry and passcode session exactly as the passcode step does.
     */
    public function acceptDeclaration(string $token, Request $request): JsonResponse|RedirectResponse
    {
        $survey = ExitSurvey::where('token', $token)->firstOrFail();

        if (! $survey->isAccessible()) {
            return $this->declarationFailure($request, $token, 'This survey link has expired or is no longer valid.', 403);
        }

        if (! $this->hasVerifiedPasscode($survey)) {
            return $this->declarationFailure($request, $token, 'Your session has expired. Please re-enter your access passcode.', 401);
        }

        if (! $request->boolean('declaration')) {
            return $this->declarationFailure($request, $token, 'Please confirm that you have read and agree with the declaration.', 422);
        }

        if (! $survey->declaration_accepted) {
            $survey->update([
                'declaration_accepted' => true,
                'declaration_accepted_at' => now(),
                'declaration_accepted_ip' => $request->ip(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['redirect' => route('survey.show', $token)]);
        }

        return redirect()->route('survey.show', $token);
    }

    public function authenticate(string $token, Request $request): RedirectResponse
    {
        $survey = ExitSurvey::where('token', $token)->firstOrFail();

        if (! $survey->isAccessible()) {
            return redirect()->route('survey.show', $token);
        }

        $request->validate([
            'passcode' => ['required', 'string'],
        ]);

        $entered = strtoupper(trim((string) $request->input('passcode')));
        $actual = strtoupper(trim((string) $survey->access_code));

        // Allow matching with or without 'GS-' prefix or dashes
        $cleanEntered = str_replace(['GS-', 'GS', ' ', '-'], '', $entered);
        $cleanActual = str_replace(['GS-', 'GS', ' ', '-'], '', $actual);

        if ($entered === $actual || $cleanEntered === $cleanActual) {
            session(['survey_authed_'.$survey->id => true]);

            return redirect()->route('survey.show', $token);
        }

        return back()->withErrors(['passcode' => 'The passcode you entered is incorrect. Please check your invitation email.'])->withInput();
    }

    public function submit(string $token, Request $request): RedirectResponse
    {
        $survey = ExitSurvey::where('token', $token)->firstOrFail();

        if (! $survey->isAccessible()) {
            abort(403, 'This survey link is no longer valid.');
        }

        $expectedKey = $survey->access_code
            ? hash_hmac('sha256', $survey->token.':'.$survey->access_code, (string) config('app.key'))
            : '';
        $submittedKey = (string) $request->input('survey_auth_key');
        $hasValidKey = $expectedKey !== '' && hash_equals($expectedKey, $submittedKey);

        if ($survey->access_code && ! $hasValidKey && ! session('survey_authed_'.$survey->id)) {
            return redirect()->route('survey.show', $token);
        }

        // A valid form key proves passcode knowledge, so keep the session authorised
        if ($hasValidKey) {
            session(['survey_authed_'.$survey->id => true]);
        }

        if (! $survey->declaration_accepted) {
            return redirect()->route('survey.show', $token)
                ->withErrors(['declaration' => 'You must accept the declaration before submitting the exit interview.']);
        }

        $validated = $request->validate([
            // Oct 2025 Form fields - All mandatory except other_comments
            'area_ratings' => ['required', 'array', 'size:20'],
            'area_ratings.*' => ['required', 'integer', 'min:1', 'max:5'],
            'main_reason_to_leave' => ['required', 'string', 'max:255'],
            'overseas_sub_option' => ['nullable', 'required_if:main_reason_to_leave,Going oversees:', 'string', 'max:100'],
            'other_reasons_to_leave' => ['required', 'array', 'min:1'],
            'liked_most_aspects' => ['required', 'array', 'min:1'],
            'enjoyed_most' => ['nullable', 'string', 'max:3000'],
            'prevented_resignation' => ['nullable', 'string', 'max:3000'],
            'recommend_company' => ['required', 'string', 'in:Yes,No'],
            'open_to_reapply' => ['required', 'string', 'in:Yes,No'],
            'team_member_signature' => ['nullable', 'string', 'max:255'],
            'team_member_signed_date' => ['nullable', 'date'],
            'terms_agreed' => ['accepted'],

            // Optional comments
            'other_comments' => ['nullable', 'string', 'max:3000'],

            // Core Survey fields
            'next_career_step' => ['nullable', 'string', 'max:100'],
            'resignation_factors' => ['nullable', 'array'],
            'resignation_factors.*' => ['string'],
            'primary_resignation_reason' => ['nullable', 'string', 'max:255'],
            'resignation_elaboration' => ['nullable', 'string', 'max:2000'],

            'rating_job_clarity' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_workload' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_training' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_tools' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_teamwork' => ['nullable', 'integer', 'min:1', 'max:5'],

            'rating_supervisor_feedback' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_supervisor_recognition' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_supervisor_fairness' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_supervisor_openness' => ['nullable', 'integer', 'min:1', 'max:5'],
            'supervisor_comments' => ['nullable', 'string', 'max:2000'],

            'rating_salary' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_increments' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_benefits' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_work_life_balance' => ['nullable', 'integer', 'min:1', 'max:5'],

            'rating_company_culture' => ['nullable', 'integer', 'min:1', 'max:5'],
            'would_recommend' => ['nullable', 'string', 'max:20'],
            'would_return' => ['nullable', 'string', 'max:20'],

            'enjoyed_most' => ['nullable', 'string', 'max:2000'],
            'improvement_suggestions' => ['nullable', 'string', 'max:2000'],
            'declaration' => ['nullable'],
        ]);

        unset($validated['declaration']);

        // Bidirectional sync between Oct 2025 form and standard analytical fields
        if (empty($validated['primary_resignation_reason']) && ! empty($validated['main_reason_to_leave'])) {
            $validated['primary_resignation_reason'] = $validated['main_reason_to_leave'];
        }
        if (empty($validated['main_reason_to_leave']) && ! empty($validated['primary_resignation_reason'])) {
            $validated['main_reason_to_leave'] = $validated['primary_resignation_reason'];
        }

        if (empty($validated['would_recommend']) && ! empty($validated['recommend_company'])) {
            $validated['would_recommend'] = strtolower($validated['recommend_company']) === 'yes' ? 'yes' : 'no';
        }
        if (empty($validated['recommend_company']) && ! empty($validated['would_recommend'])) {
            $validated['recommend_company'] = strtolower($validated['would_recommend']) === 'yes' ? 'Yes' : 'No';
        }

        if (empty($validated['would_return']) && ! empty($validated['open_to_reapply'])) {
            $validated['would_return'] = strtolower($validated['open_to_reapply']) === 'yes' ? 'yes' : 'no';
        }
        if (empty($validated['open_to_reapply']) && ! empty($validated['would_return'])) {
            $validated['open_to_reapply'] = strtolower($validated['would_return']) === 'yes' ? 'Yes' : 'No';
        }

        // Sync individual ratings from area_ratings if present
        if (! empty($validated['area_ratings']) && is_array($validated['area_ratings'])) {
            $ar = $validated['area_ratings'];
            $validated['rating_training'] = $validated['rating_training'] ?? ($ar[2] ?? null);
            $validated['rating_job_clarity'] = $validated['rating_job_clarity'] ?? ($ar[4] ?? null);
            $validated['rating_workload'] = $validated['rating_workload'] ?? ($ar[5] ?? null);
            $validated['rating_tools'] = $validated['rating_tools'] ?? ($ar[6] ?? null);
            $validated['rating_salary'] = $validated['rating_salary'] ?? ($ar[7] ?? null);
            $validated['rating_benefits'] = $validated['rating_benefits'] ?? ($ar[8] ?? null);
            $validated['rating_supervisor_feedback'] = $validated['rating_supervisor_feedback'] ?? ($ar[9] ?? null);
            $validated['rating_supervisor_openness'] = $validated['rating_supervisor_openness'] ?? ($ar[10] ?? null);
            $validated['rating_teamwork'] = $validated['rating_teamwork'] ?? ($ar[12] ?? null);
            $validated['rating_supervisor_recognition'] = $validated['rating_supervisor_recognition'] ?? ($ar[15] ?? null);
            $validated['rating_work_life_balance'] = $validated['rating_work_life_balance'] ?? ($ar[17] ?? null);
            $validated['rating_company_culture'] = $validated['rating_company_culture'] ?? ($ar[19] ?? null);
        }

        // Automatically assign signature from full name and date from current filling date
        if (empty($validated['team_member_signature'])) {
            $validated['team_member_signature'] = $survey->employee_name;
        }
        if (empty($validated['team_member_signed_date'])) {
            $validated['team_member_signed_date'] = now()->toDateString();
        }

        ExitResponse::create([
            'exit_survey_id' => $survey->id,
            ...$validated,
        ]);

        $survey->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        session()->forget('survey_authed_'.$survey->id);

        return redirect()->route('survey.success');
    }

    /** Whether the current session has passed the passcode step for this survey. */
    private function hasVerifiedPasscode(ExitSurvey $survey): bool
    {
        return ! $survey->access_code || (bool) session('survey_authed_'.$survey->id);
    }

    /** Build a failed declaration response as JSON or as a redirect with errors. */
    private function declarationFailure(Request $request, string $token, string $message, int $status): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], $status);
        }

        return redirect()->route('survey.show', $token)->withErrors(['declaration' => $message]);
    }
}
