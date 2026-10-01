<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\ExitResponse;
use App\Models\ExitSurvey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TemplateController extends Controller
{
    /**
     * Display the official Exit Interview Form template verbatim from the 23rd Oct 2025 document.
     */
    public function index(): View
    {
        $companies = Company::where('is_active', true)->orderBy('name')->get();

        $areas = [
            1 => 'Welcome and orientation',
            2 => 'Training to perform the job',
            3 => 'Opportunities for development and career advancement',
            4 => 'Type/scope of work that was expected to do',
            5 => 'Satisfaction with the workload assigned',
            6 => 'Resources made available to perform the job',
            7 => 'Basic salary offered',
            8 => 'Benefits offered',
            9 => 'Direction and guidance from the immediate supervisor',
            10 => 'Approachability of the immediate supervisor',
            11 => 'My ideas and concerns were given a good hearing',
            12 => 'Teamwork and collaboration within the department',
            13 => 'Teamwork and collaboration with other departments',
            14 => 'Open communication between management and the team',
            15 => 'Rewards and recognition for my work performance',
            16 => 'Ability to take leave/ off for my personal commitments',
            17 => 'Having work-life balance',
            18 => 'Feeling respected and valued as a unique person',
            19 => 'Overall work environment and existence of ‘Api Culture’',
            20 => 'Approachability and supportiveness of Group HR',
        ];

        $mainReasons = [
            1 => [
                'type' => 'single',
                'label' => 'Joining a local company for a better position/ salary',
            ],
            2 => [
                'type' => 'overseas',
                'label' => 'Going oversees:',
                'sub_options' => ['Overseas job', 'Higher studies', 'Migration'],
            ],
            3 => [
                'type' => 'single',
                'label' => 'Shifting to another industry/career',
            ],
            4 => [
                'type' => 'single',
                'label' => 'Personal health related reasons',
            ],
            5 => [
                'type' => 'single',
                'label' => 'Family or personal commitments/reasons',
            ],
            6 => [
                'type' => 'single',
                'label' => 'Distance to workplace/ Relocation',
            ],
        ];

        $otherReasons = [
            1 => 'Inadequate salary/benefits',
            2 => 'Limited opportunities for growth and career advancement',
            3 => 'Inadequate work-life balance',
            4 => 'Challenging relationship with immediate supervisor',
            5 => 'Insufficient strategic focus from company management',
            6 => 'Unfavourable/unsupportive work culture',
        ];

        $likedMost = [
            1 => 'Nature of work / job responsibilities',
            2 => 'Supportive team and colleagues',
            3 => 'Supervisor’s guidance and leadership',
            4 => 'Learning and development opportunities',
            5 => 'Career growth and exposure',
            6 => 'Compensation and benefits',
            7 => 'Flexibility / work-life balance',
            8 => 'Team engagement activities',
            9 => 'Recognition and appreciation',
            10 => 'Workplace facilities and environment',
            11 => 'Company’s reputation and stability',
            12 => 'Organisational culture and values',
        ];

        return view('admin.templates.index', compact('companies', 'areas', 'mainReasons', 'otherReasons', 'likedMost'));
    }

    /**
     * Store a newly completed exit interview form directly into the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Team Member Details (Mandatory)
            'employee_name' => ['required', 'string', 'max:255'],
            'employee_email' => ['required', 'email', 'max:255'],
            'employee_id' => ['required', 'string', 'max:100'],
            'company_id' => ['required', 'exists:companies,id'],
            'designation' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'date_joined' => ['required', 'date'],
            'date_of_resignation' => ['required', 'date'],

            // 20 Areas Ratings (All 20 Mandatory)
            'area_ratings' => ['required', 'array', 'size:20'],
            'area_ratings.*' => ['required', 'integer', 'min:1', 'max:5'],

            // Reasons to leave (Mandatory)
            'main_reason_to_leave' => ['required', 'string', 'max:255'],
            'overseas_sub_option' => ['nullable', 'string', 'max:100'],
            'other_reasons_to_leave' => ['required', 'array', 'min:1'],
            'other_reasons_to_leave.*' => ['string'],

            // Work Experience Liked Most (Mandatory)
            'liked_most_aspects' => ['required', 'array', 'min:1'],
            'liked_most_aspects.*' => ['string'],

            // Qualitative Feedback (Mandatory)
            'enjoyed_most' => ['required', 'string', 'max:3000'],
            'prevented_resignation' => ['required', 'string', 'max:3000'],
            'recommend_company' => ['required', 'in:Yes,No'],
            'open_to_reapply' => ['required', 'in:Yes,No'],
            'other_comments' => ['required', 'string', 'max:3000'],

            // Team Member Sign-off (Mandatory)
            'team_member_signature' => ['required', 'string', 'max:255'],
            'team_member_signed_date' => ['required', 'date'],

            // Review Signatures (Optional)
            'reviewed_director_hr' => ['nullable', 'string', 'max:255'],
            'reviewed_director_hr_date' => ['nullable', 'date'],
            'reviewed_company_head' => ['nullable', 'string', 'max:255'],
            'reviewed_company_head_date' => ['nullable', 'date'],
            'reviewed_group_chairman' => ['nullable', 'string', 'max:255'],
            'reviewed_group_chairman_date' => ['nullable', 'date'],
        ]);

        // 1. Create ExitSurvey record
        $survey = ExitSurvey::create([
            'token' => Str::random(64),
            'employee_name' => $validated['employee_name'],
            'employee_email' => $validated['employee_email'],
            'employee_id' => $validated['employee_id'],
            'designation' => $validated['designation'],
            'department' => $validated['department'],
            'section_division' => $validated['department'],
            'reporting_manager' => $validated['supervisor_name'],
            'supervisor_name' => $validated['supervisor_name'],
            'company_id' => $validated['company_id'],
            'sent_by' => auth()->id(),
            'date_joined' => $validated['date_joined'],
            'date_of_resignation' => $validated['date_of_resignation'],
            'last_working_date' => $validated['date_of_resignation'],
            'expires_at' => now()->addDays(30),
            'status' => 'system_generated',
            'submission_source' => 'system_generated',
            'submitted_at' => now(),
        ]);

        // 2. Map ratings to analytical fields
        $ar = $validated['area_ratings'];

        // 3. Create ExitResponse record
        ExitResponse::create([
            'exit_survey_id' => $survey->id,
            'area_ratings' => $ar,
            'main_reason_to_leave' => $validated['main_reason_to_leave'],
            'overseas_sub_option' => $validated['overseas_sub_option'] ?? null,
            'other_reasons_to_leave' => $validated['other_reasons_to_leave'],
            'liked_most_aspects' => $validated['liked_most_aspects'],
            'enjoyed_most' => $validated['enjoyed_most'],
            'prevented_resignation' => $validated['prevented_resignation'],
            'recommend_company' => $validated['recommend_company'],
            'open_to_reapply' => $validated['open_to_reapply'],
            'other_comments' => $validated['other_comments'],
            'team_member_signature' => $validated['team_member_signature'],
            'team_member_signed_date' => $validated['team_member_signed_date'],
            'reviewed_director_hr' => $validated['reviewed_director_hr'] ?? null,
            'reviewed_director_hr_date' => $validated['reviewed_director_hr_date'] ?? null,
            'reviewed_company_head' => $validated['reviewed_company_head'] ?? null,
            'reviewed_company_head_date' => $validated['reviewed_company_head_date'] ?? null,
            'reviewed_group_chairman' => $validated['reviewed_group_chairman'] ?? null,
            'reviewed_group_chairman_date' => $validated['reviewed_group_chairman_date'] ?? null,
            // Analytical equivalents
            'primary_resignation_reason' => $validated['main_reason_to_leave'],
            'resignation_factors' => $validated['other_reasons_to_leave'],
            'resignation_elaboration' => $validated['prevented_resignation'],
            'improvement_suggestions' => $validated['other_comments'],
            'would_recommend' => strtolower($validated['recommend_company']) === 'yes' ? 'yes' : 'no',
            'would_return' => strtolower($validated['open_to_reapply']) === 'yes' ? 'yes' : 'no',
            'rating_training' => $ar[2] ?? null,
            'rating_job_clarity' => $ar[4] ?? null,
            'rating_workload' => $ar[5] ?? null,
            'rating_tools' => $ar[6] ?? null,
            'rating_salary' => $ar[7] ?? null,
            'rating_benefits' => $ar[8] ?? null,
            'rating_supervisor_feedback' => $ar[9] ?? null,
            'rating_supervisor_openness' => $ar[10] ?? null,
            'rating_teamwork' => $ar[12] ?? null,
            'rating_supervisor_recognition' => $ar[15] ?? null,
            'rating_work_life_balance' => $ar[17] ?? null,
            'rating_company_culture' => $ar[19] ?? null,
        ]);

        return redirect()->route('admin.templates.index')
            ->with('success', "Exit interview form for '{$survey->employee_name}' ({$survey->company->name}) has been successfully recorded in the database with status 'System Generated'.");
    }

    /**
     * Download the original docx document.
     */
    public function downloadDocx(): BinaryFileResponse
    {
        $filePath = base_path('Exit Interview Form - 23rd Oct 2025.docx');

        return response()->download($filePath, 'Exit Interview Form - 23rd Oct 2025.docx');
    }
}
