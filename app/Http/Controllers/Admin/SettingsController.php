<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (! auth()->user()?->hasRole('super_admin')) {
                    abort(403, 'Unauthorized.');
                }

                return $next($request);
            }),
        ];
    }

    public function index()
    {
        $defaultDays = Setting::get('default_token_validity_days', 14);
        $termsAndConditions = Setting::get('exit_survey_terms_and_conditions', static::defaultTermsAndConditions());
        $hrManagers = User::role('subsidiary_hr_manager')->with('company')->get();
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        $allCompanies = Company::withCount('exitSurveys')->orderBy('name')->get();

        return view('admin.settings.index', compact('defaultDays', 'termsAndConditions', 'hrManagers', 'companies', 'allCompanies'));
    }

    /**
     * Store a newly created subsidiary company.
     */
    public function storeCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'code' => ['required', 'string', 'max:20', 'unique:companies,code'],
            'headcount' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        $company = Company::create($validated);

        return back()->with('success', "Company '{$company->name}' added successfully.");
    }

    /**
     * Update an existing subsidiary company.
     */
    public function updateCompany(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name,'.$company->id],
            'code' => ['required', 'string', 'max:20', 'unique:companies,code,'.$company->id],
            'headcount' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $company->update($validated);

        return back()->with('success', "Company '{$company->name}' updated successfully.");
    }

    /**
     * Delete an existing subsidiary company.
     */
    public function destroyCompany(Company $company): RedirectResponse
    {
        if ($company->exitSurveys()->exists()) {
            return back()->with('error', "Cannot delete '{$company->name}' because it has existing exit interview records. You can set its status to Inactive instead.");
        }

        $company->delete();

        return back()->with('success', 'Company deleted successfully.');
    }

    /**
     * Save global settings (token validity days & Terms and Conditions).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_token_validity_days' => ['nullable', 'integer', 'min:1', 'max:90'],
            'exit_survey_terms_and_conditions' => ['nullable', 'string', 'max:15000'],
        ]);

        if ($request->filled('default_token_validity_days')) {
            Setting::set('default_token_validity_days', $validated['default_token_validity_days']);
        }

        if ($request->has('exit_survey_terms_and_conditions')) {
            Setting::set('exit_survey_terms_and_conditions', (string) $request->input('exit_survey_terms_and_conditions'));
        }

        return back()->with('success', 'Settings saved successfully.');
    }

    /**
     * Default enterprise Terms and Conditions for exit interview survey.
     */
    public static function defaultTermsAndConditions(): string
    {
        return '1. Purpose & Confidentiality:
The purpose of this Exit Interview is to gather honest and constructive feedback regarding your employment experience with the George Steuart Group. All feedback provided will be treated with strict professional confidentiality by the Group Human Resources department and utilized strictly for the purpose of organizational improvement, workplace policy development, and culture enhancement.

2. Voluntary & Truthful Disclosure:
Your participation in this exit survey is an integral part of our standard offboarding protocol. You declare that the ratings, feedback, reasons, and statements provided represent your honest, personal, and truthful evaluation of your tenure.

3. Non-Retaliation Policy:
The George Steuart Group upholds a strict non-retaliation principle. Candid and critical feedback will not prejudice your employment references, offboarding clearance, final dues settlement, or any potential future re-employment consideration.

4. Data Protection & Processing:
The information submitted will be securely stored within the Group HR management information system in compliance with applicable data privacy guidelines and corporate governance policies.

By checking the agreement box and submitting this questionnaire, you confirm that you have read, understood, and consented to these terms and conditions.';
    }

    /**
     * Create a new subsidiary HR manager account.
     */
    public function storeHrManager(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'company_id' => ['required', 'exists:companies,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company_id' => $validated['company_id'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('subsidiary_hr_manager');

        return back()->with('success', "HR Manager account created for {$user->name}.");
    }

    /**
     * Update a subsidiary HR manager's assigned company.
     */
    public function updateHrManager(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
        ]);

        $user->update(['company_id' => $validated['company_id']]);

        return back()->with('success', "Company assignment updated for {$user->name}.");
    }

    /**
     * Delete a subsidiary HR manager account.
     */
    public function destroyHrManager(User $user): RedirectResponse
    {
        abort_if($user->hasRole('super_admin'), 403, 'Cannot delete a Super Admin account.');

        $user->delete();

        return back()->with('success', 'HR Manager account removed.');
    }
}
