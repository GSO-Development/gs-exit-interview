<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ExitSurveyInvitation;
use App\Mail\ExitSurveyPasscode;
use App\Models\Company;
use App\Models\ExitSurvey;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExitSurveyController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'completed');
        $companyIds = auth()->user()->accessibleCompanyIds();

        // 1. Online Completed Submissions
        $completedQuery = ExitSurvey::with(['company', 'response'])
            ->where('status', 'submitted')
            ->where(function ($q) {
                $q->whereNull('submission_source')
                    ->orWhere('submission_source', '!=', 'system_generated');
            })
            ->when($companyIds !== null, fn ($q) => $q->whereIn('company_id', $companyIds));

        // 2. System Generated Submissions (from Template Form)
        $systemQuery = ExitSurvey::with(['company', 'response'])
            ->where(function ($q) {
                $q->where('status', 'system_generated')
                    ->orWhere('submission_source', 'system_generated');
            })
            ->when($companyIds !== null, fn ($q) => $q->whereIn('company_id', $companyIds));

        // 3. Sent Invitations & Tokens (Only active pending surveys)
        $sentQuery = ExitSurvey::with('company')
            ->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->when($companyIds !== null, fn ($q) => $q->whereIn('company_id', $companyIds));

        // 4. Expired & Revoked Invitations
        $expiredQuery = ExitSurvey::with('company')
            ->where(function ($q) {
                $q->whereIn('status', ['expired', 'revoked'])
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'pending')
                            ->whereNotNull('expires_at')
                            ->where('expires_at', '<', now());
                    });
            })
            ->when($companyIds !== null, fn ($q) => $q->whereIn('company_id', $companyIds));

        // Search
        if ($search = $request->query('search')) {
            $completedQuery->where(
                fn ($q) => $q
                    ->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
            );
            $systemQuery->where(
                fn ($q) => $q
                    ->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
            );
            $sentQuery->where(
                fn ($q) => $q
                    ->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
            );
            $expiredQuery->where(
                fn ($q) => $q
                    ->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
            );
        }

        // Department filter
        if ($dept = $request->query('department')) {
            $completedQuery->where('department', $dept);
            $systemQuery->where('department', $dept);
            $sentQuery->where('department', $dept);
            $expiredQuery->where('department', $dept);
        }

        // ── CSV Export ──────────────────────────────────────────────────────
        if ($request->query('export') === 'csv') {
            if ($tab === 'system') {
                return $this->exportCsv(clone $systemQuery);
            }

            return $this->exportCsv(clone $completedQuery);
        }

        $completed = $completedQuery->latest('submitted_at')->paginate(15, pageName: 'cp')->withQueryString();
        $system = $systemQuery->latest('submitted_at')->paginate(15, pageName: 'sysp')->withQueryString();
        $sent = $sentQuery->latest()->paginate(15, pageName: 'sp')->withQueryString();
        $expired = $expiredQuery->latest('updated_at')->paginate(15, pageName: 'expp')->withQueryString();
        $departments = ExitSurvey::distinct()->pluck('department')->filter()->sort()->values();

        return view('admin.surveys.index', compact('completed', 'system', 'sent', 'expired', 'tab', 'departments'));
    }

    /**
     * Stream a CSV download of completed submissions.
     */
    private function exportCsv($query): StreamedResponse
    {
        $filename = 'exit-surveys-'.now()->format('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'Employee Name', 'EPF/Staff ID', 'Email', 'Company', 'Designation', 'Department',
                'Reporting Manager', 'Date Joined', 'Last Working Date', 'Tenure',
                'Overall Satisfaction', 'Primary Reason', 'Would Recommend', 'Would Return',
                'Job Rating Avg', 'Supervisor Rating Avg', 'Compensation Rating Avg',
                'Submitted At',
            ]);

            $query->with(['company', 'response'])->chunk(100, function ($surveys) use ($handle) {
                foreach ($surveys as $survey) {
                    $r = $survey->response;
                    fputcsv($handle, [
                        $survey->employee_name,
                        $survey->employee_id ?? '',
                        $survey->employee_email,
                        $survey->company->name ?? '',
                        $survey->designation,
                        $survey->department,
                        $survey->reporting_manager ?? '',
                        $survey->date_joined?->format('Y-m-d') ?? '',
                        $survey->last_working_date?->format('Y-m-d') ?? '',
                        $survey->tenureYears() ?? '',
                        $survey->overallSatisfaction() ?? '',
                        $r?->primary_resignation_reason ?? '',
                        $r?->would_recommend ?? '',
                        $r?->would_return ?? '',
                        $r?->jobRatingAverage() ?? '',
                        $r?->supervisorRatingAverage() ?? '',
                        $r?->compensationRatingAverage() ?? '',
                        $survey->submitted_at?->format('Y-m-d H:i') ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, $headers);
    }

    public function show(ExitSurvey $exitSurvey)
    {
        $exitSurvey->load(['company', 'response', 'sender']);

        return view('admin.surveys.show', ['survey' => $exitSurvey]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $user = auth()->user();
        $companyIds = $user->accessibleCompanyIds();

        $validated = $request->validate([
            'employee_name' => ['required', 'string', 'max:255'],
            'employee_email' => ['required', 'email', 'max:255'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'designation' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'reporting_manager' => ['nullable', 'string', 'max:255'],
            'company_id' => [
                'required',
                'exists:companies,id',
                // Subsidiary HR can only assign to their own company
                function ($attribute, $value, $fail) use ($companyIds) {
                    if ($companyIds !== null && ! in_array((int) $value, $companyIds)) {
                        $fail('You can only send surveys for your assigned subsidiary.');
                    }
                },
            ],
            'last_working_date' => ['nullable', 'date'],
            'date_joined' => ['nullable', 'date'],
            'token_validity_days' => ['nullable', 'integer', 'min:1', 'max:90'],
        ]);

        // Use per-invitation override, then global setting, then hardcoded default
        $defaultDays = (int) Setting::get('default_token_validity_days', 14);
        $validityDays = (int) ($validated['token_validity_days'] ?? $defaultDays);

        $accessCode = self::generateSecurePasscode(
            (string) ($validated['employee_name'] ?? ''),
            (string) ($validated['employee_email'] ?? ''),
            (string) ($validated['department'] ?? ''),
            (string) ($validated['date_joined'] ?? now()->toDateString())
        );

        $survey = ExitSurvey::create([
            ...$validated,
            'token' => Str::random(64), // Gap G fix: 64 chars as per spec
            'access_code' => $accessCode,
            'sent_by' => auth()->id(),
            'expires_at' => now()->addDays($validityDays),
            'status' => 'pending',
        ]);

        $survey->load('company');

        $mailSent = true;
        try {
            // 1. Send first email: Survey link invitation
            Mail::to($survey->employee_email)->send(new ExitSurveyInvitation($survey));
            // 2. Send second email: Confidential access passcode
            Mail::to($survey->employee_email)->send(new ExitSurveyPasscode($survey));
        } catch (\Throwable $e) {
            \Log::error('Failed to send exit survey emails: '.$e->getMessage());
            $mailSent = false;
        }

        $message = $mailSent
            ? "Exit survey link and access passcode sent to {$survey->employee_name} in 2 separate emails successfully."
            : "Exit survey created for {$survey->employee_name}. (Email delivery failed or delayed, link is active).";

        $generatedData = [
            'survey_url' => route('survey.show', $survey->token),
            'access_code' => $survey->access_code,
            'employee_name' => $survey->employee_name,
            'employee_email' => $survey->employee_email,
            'company_name' => $survey->company->name ?? 'George Steuart Group',
            'expires_at' => $survey->expires_at->format('F d, Y'),
            'validity_days' => $validityDays,
            'mail_sent' => $mailSent,
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'survey_generated' => $generatedData,
            ]);
        }

        return back()
            ->with('success', $message)
            ->with('survey_generated', $generatedData);
    }

    /**
     * Generate a cryptographically secure 9-character passcode derived from
     * employee details (username/name, email, date entered, department) and random entropy.
     */
    public static function generateSecurePasscode(string $name, string $email, string $department, ?string $date = null): string
    {
        $date = $date ?: now()->toDateString();
        $randomSalt = bin2hex(random_bytes(16));
        $seedHash = hash('sha256', "{$name}|{$email}|{$department}|{$date}|{$randomSalt}");

        // High-readability uppercase alphanumeric charset (32 characters - excludes ambiguous 0, O, 1, I)
        $charset = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charsetLen = strlen($charset);

        $passcode = '';
        for ($i = 0; $i < 9; $i++) {
            $hexChunk = substr($seedHash, $i * 4, 4);
            $randomEntropy = random_int(0, $charsetLen - 1);
            $index = (hexdec($hexChunk) + $randomEntropy) % $charsetLen;
            $passcode .= $charset[$index];
        }

        return $passcode;
    }

    public function resend(ExitSurvey $exitSurvey): RedirectResponse
    {
        Gate::authorize('manage links');

        $exitSurvey->load('company');
        try {
            Mail::to($exitSurvey->employee_email)->send(new ExitSurveyInvitation($exitSurvey));
            Mail::to($exitSurvey->employee_email)->send(new ExitSurveyPasscode($exitSurvey));

            return back()->with('success', "Survey link and passcode emails resent to {$exitSurvey->employee_email}.");
        } catch (\Throwable $e) {
            \Log::error('Failed to resend exit survey emails: '.$e->getMessage());

            return back()->with('warning', 'Survey link is active, but sending email failed: '.$e->getMessage());
        }
    }

    public function renew(ExitSurvey $exitSurvey): RedirectResponse
    {
        Gate::authorize('manage links');

        $days = (int) Setting::get('default_token_validity_days', 14);

        $accessCode = self::generateSecurePasscode(
            (string) $exitSurvey->employee_name,
            (string) $exitSurvey->employee_email,
            (string) ($exitSurvey->department ?? ''),
            (string) ($exitSurvey->date_joined?->toDateString() ?? now()->toDateString())
        );

        $exitSurvey->update([
            'status' => 'pending',
            'token' => Str::random(64), // Issue a fresh token on renew
            'access_code' => $accessCode,
            'expires_at' => now()->addDays($days),
        ]);

        $exitSurvey->load('company');
        try {
            Mail::to($exitSurvey->employee_email)->send(new ExitSurveyInvitation($exitSurvey));
            Mail::to($exitSurvey->employee_email)->send(new ExitSurveyPasscode($exitSurvey));

            return back()->with('success', "Token renewed and both emails re-sent to {$exitSurvey->employee_email}.");
        } catch (\Throwable $e) {
            \Log::error('Failed to send renewed exit survey emails: '.$e->getMessage());

            return back()->with('success', 'Token renewed successfully. (Email sending failed or delayed).');
        }
    }

    public function revoke(ExitSurvey $exitSurvey): RedirectResponse
    {
        Gate::authorize('manage links');

        $exitSurvey->update(['status' => 'revoked']);

        return back()->with('success', "Token for {$exitSurvey->employee_name} has been revoked.");
    }

    public function downloadPdf(ExitSurvey $exitSurvey): Response
    {
        Gate::authorize('download dossier');
        $exitSurvey->load(['company', 'response', 'sender']);

        $pdf = Pdf::loadView('admin.surveys.dossier-pdf', ['survey' => $exitSurvey])
            ->setPaper('a4', 'portrait');
        $filename = 'exit-interview-form-'.Str::slug($exitSurvey->employee_name).'.pdf';

        return $pdf->stream($filename);
    }

    public function downloadWord(ExitSurvey $exitSurvey): StreamedResponse
    {
        Gate::authorize('download dossier');
        $exitSurvey->load(['company', 'response', 'sender']);

        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection();

        $titleStyle = ['bold' => true, 'size' => 16, 'color' => '1e3a5f'];
        $boldStyle = ['bold' => true, 'size' => 11];
        $normalStyle = ['size' => 11];

        $section->addText('OFFICIAL EXECUTIVE EXIT INTERVIEW DOSSIER', $titleStyle, ['alignment' => Jc::CENTER]);
        $section->addText('George Steuart Group — Confidential HR Document', ['size' => 10, 'color' => '999999'], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);

        // Employment details
        $section->addText('EMPLOYMENT PARTICULARS', ['bold' => true, 'size' => 12, 'color' => '1e3a5f']);
        $infoLines = [
            'Employee Name' => $exitSurvey->employee_name,
            'Designation' => $exitSurvey->designation,
            'Department' => $exitSurvey->department,
            'Company' => $exitSurvey->company->name,
            'EPF / Staff ID' => $exitSurvey->employee_id ?? 'N/A',
            'Reporting Manager' => $exitSurvey->reporting_manager ?? 'N/A',
            'Date of Joining' => $exitSurvey->date_joined?->format('Y-m-d') ?? 'N/A',
            'Last Working Date' => $exitSurvey->last_working_date?->format('Y-m-d') ?? 'N/A',
            'Tenure' => $exitSurvey->tenureYears() ?? 'N/A',
            'Overall Satisfaction' => ($exitSurvey->overallSatisfaction() ?? 'N/A').' / 5.0',
            'Submitted On' => $exitSurvey->submitted_at?->format('Y-m-d H:i') ?? 'N/A',
        ];

        foreach ($infoLines as $label => $value) {
            $textRun = $section->addTextRun();
            $textRun->addText("{$label}: ", $boldStyle);
            $textRun->addText($value, $normalStyle);
        }

        if ($response = $exitSurvey->response) {
            $section->addTextBreak(1);
            $section->addText('RESIGNATION DETAILS', ['bold' => true, 'size' => 12, 'color' => '1e3a5f']);

            $textRun = $section->addTextRun();
            $textRun->addText('Next Career Step: ', $boldStyle);
            $textRun->addText($response->next_career_step ?? 'N/A', $normalStyle);

            $textRun = $section->addTextRun();
            $textRun->addText('Primary Reason: ', $boldStyle);
            $textRun->addText($response->primary_resignation_reason ?? 'N/A', $normalStyle);

            if ($response->resignation_factors) {
                $textRun = $section->addTextRun();
                $textRun->addText('Contributing Factors: ', $boldStyle);
                $textRun->addText(implode(', ', $response->resignation_factors), $normalStyle);
            }

            if ($response->resignation_elaboration) {
                $section->addText('"'.$response->resignation_elaboration.'"', ['italic' => true, 'size' => 11]);
            }

            $section->addTextBreak(1);
            $section->addText('RATINGS SUMMARY', ['bold' => true, 'size' => 12, 'color' => '1e3a5f']);

            $ratingGroups = [
                'Job Clarity' => $response->rating_job_clarity,
                'Workload Management' => $response->rating_workload,
                'Training Opportunities' => $response->rating_training,
                'Tools & Equipment' => $response->rating_tools,
                'Team Cooperation' => $response->rating_teamwork,
                'Supervisor Feedback' => $response->rating_supervisor_feedback,
                'Supervisor Recognition' => $response->rating_supervisor_recognition,
                'Supervisor Fairness' => $response->rating_supervisor_fairness,
                'Supervisor Openness' => $response->rating_supervisor_openness,
                'Salary Competitiveness' => $response->rating_salary,
                'Increments & Bonuses' => $response->rating_increments,
                'Medical & Benefits' => $response->rating_benefits,
                'Work-Life Balance' => $response->rating_work_life_balance,
                'Company Culture' => $response->rating_company_culture,
            ];

            foreach ($ratingGroups as $label => $rating) {
                $stars = str_repeat('★', (int) ($rating ?? 0)).str_repeat('☆', 5 - (int) ($rating ?? 0));
                $textRun = $section->addTextRun();
                $textRun->addText("{$label}: ", $boldStyle);
                $textRun->addText("{$stars} ({$rating}/5)", $normalStyle);
            }

            $section->addTextBreak(1);
            $section->addText('QUALITATIVE FEEDBACK', ['bold' => true, 'size' => 12, 'color' => '1e3a5f']);

            $section->addText('What employee enjoyed most:', $boldStyle);
            $section->addText($response->enjoyed_most ?? 'N/A', $normalStyle);
            $section->addTextBreak(1);
            $section->addText('Improvement suggestions for management:', $boldStyle);
            $section->addText($response->improvement_suggestions ?? 'N/A', $normalStyle);

            if ($response->supervisor_comments) {
                $section->addTextBreak(1);
                $section->addText('Supervision feedback:', $boldStyle);
                $section->addText($response->supervisor_comments, $normalStyle);
            }

            $section->addTextBreak(1);
            $section->addText('eNPS ADVOCACY', ['bold' => true, 'size' => 12, 'color' => '1e3a5f']);

            $textRun = $section->addTextRun();
            $textRun->addText('Would recommend organization: ', $boldStyle);
            $textRun->addText(ucfirst($response->would_recommend ?? 'N/A'), $normalStyle);

            $textRun = $section->addTextRun();
            $textRun->addText('Would return to organization: ', $boldStyle);
            $textRun->addText(ucfirst($response->would_return ?? 'N/A'), $normalStyle);
        }

        $filename = 'exit-dossier-'.Str::slug($exitSurvey->employee_name).'.docx';

        return response()->streamDownload(function () use ($phpWord) {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    /**
     * Check if a survey already exists for a given email or employee_id.
     */
    public function checkDuplicate(Request $request): JsonResponse
    {
        $type = $request->query('type');
        $value = trim((string) $request->query('value'));

        if (! $value || ! in_array($type, ['email', 'employee_id'])) {
            return response()->json(['found' => false]);
        }

        $query = ExitSurvey::with('company');

        if ($type === 'email') {
            $query->where('employee_email', $value);
        } else {
            $query->where('employee_id', $value);
        }

        $surveys = $query->latest()->get();

        if ($surveys->isEmpty()) {
            return response()->json(['found' => false]);
        }

        // 1. Check if previously submitted
        $submitted = $surveys->firstWhere('status', 'submitted');
        if ($submitted) {
            $date = $submitted->submitted_at ? $submitted->submitted_at->format('Y-m-d') : ($submitted->created_at ? $submitted->created_at->format('Y-m-d') : '');

            return response()->json([
                'found' => true,
                'status' => 'submitted',
                'message' => 'An exit interview was already completed and submitted for this '.($type === 'email' ? 'email' : 'EPF / Staff ID'),
                'details' => [
                    'employee_name' => $submitted->employee_name,
                    'company_name' => $submitted->company->name ?? 'N/A',
                    'date' => $date,
                    'department' => $submitted->department,
                ],
            ]);
        }

        // 2. Check if active pending invitation
        $pending = $surveys->first(function ($s) {
            return $s->status === 'pending' && ($s->expires_at === null || $s->expires_at->isFuture());
        });

        if ($pending) {
            $date = $pending->created_at ? $pending->created_at->format('Y-m-d') : '';
            $expires = $pending->expires_at ? $pending->expires_at->diffForHumans() : 'Active';

            return response()->json([
                'found' => true,
                'status' => 'pending',
                'message' => 'An active invitation token was already sent to this '.($type === 'email' ? 'email' : 'EPF / Staff ID'),
                'details' => [
                    'employee_name' => $pending->employee_name,
                    'company_name' => $pending->company->name ?? 'N/A',
                    'date' => $date,
                    'expires_at' => $expires,
                    'department' => $pending->department,
                ],
            ]);
        }

        // 3. Expired or Revoked invitation
        $previous = $surveys->first();
        $date = $previous->updated_at ? $previous->updated_at->format('Y-m-d') : ($previous->created_at ? $previous->created_at->format('Y-m-d') : '');
        $statusLabel = ucfirst($previous->status);

        return response()->json([
            'found' => true,
            'status' => $previous->status,
            'message' => "A previous invitation exists with status: {$statusLabel}",
            'details' => [
                'employee_name' => $previous->employee_name,
                'company_name' => $previous->company->name ?? 'N/A',
                'date' => $date,
                'status_label' => $statusLabel,
                'department' => $previous->department,
            ],
        ]);
    }
}
