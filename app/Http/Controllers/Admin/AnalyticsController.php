<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\ExitSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $companyIds = $user->accessibleCompanyIds();

        // Filter Inputs
        $selectedCompany = $request->query('company_id');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $selectedSurveyId = $request->query('survey_id');

        // Scoped active companies
        $companies = Company::where('is_active', true)
            ->when($companyIds !== null, fn ($q) => $q->whereIn('id', $companyIds))
            ->orderBy('name')
            ->get();

        // Enforce subsidiary HR scope
        if ($selectedCompany && $companyIds !== null && ! in_array((int) $selectedCompany, $companyIds)) {
            $selectedCompany = null;
        }

        // List of all completed survey participants for the Individual Employee dropdown filter
        $availableEmployees = ExitSurvey::query()
            ->whereIn('status', ['submitted', 'system_generated'])
            ->when($companyIds !== null, fn ($q) => $q->whereIn('company_id', $companyIds))
            ->when($selectedCompany, fn ($q) => $q->where('company_id', $selectedCompany))
            ->with('company')
            ->orderBy('employee_name')
            ->get(['id', 'employee_name', 'employee_id', 'company_id', 'department']);

        // Base query for completed exit surveys with all filters applied
        $surveysQuery = ExitSurvey::query()
            ->whereIn('status', ['submitted', 'system_generated'])
            ->when($companyIds !== null, fn ($q) => $q->whereIn('company_id', $companyIds))
            ->when($selectedCompany, fn ($q) => $q->where('company_id', $selectedCompany))
            ->when($selectedSurveyId, fn ($q) => $q->where('id', $selectedSurveyId))
            ->when($fromDate, fn ($q) => $q->whereDate(DB::raw('COALESCE(submitted_at, created_at)'), '>=', $fromDate))
            ->when($toDate, fn ($q) => $q->whereDate(DB::raw('COALESCE(submitted_at, created_at)'), '<=', $toDate));

        $totalSubmissions = (clone $surveysQuery)->count();

        // Eager load matching surveys with company and response
        $surveys = (clone $surveysQuery)
            ->with(['company', 'response'])
            ->latest('submitted_at')
            ->get();

        // ── 1. Top KPI Metrics ───────────────────────────────────────────────────

        // Total Headcount (scoped)
        if ($selectedCompany) {
            $totalHeadcount = (int) ($companies->firstWhere('id', (int) $selectedCompany)?->headcount ?? 0);
            $selectedCompanyName = $companies->firstWhere('id', (int) $selectedCompany)?->name;
        } else {
            $totalHeadcount = (int) $companies->sum('headcount');
            $selectedCompanyName = null;
        }

        // Overall Satisfaction
        $satisfactions = $surveys
            ->map(fn ($s) => $s->overallSatisfaction())
            ->filter(fn ($val) => $val !== null && $val > 0);
        $avgSatisfaction = $satisfactions->count() > 0 ? round($satisfactions->avg(), 1) : null;

        // Recommendation Rate (Yes / Promoters)
        $recommendYes = 0;
        $recommendTotal = 0;
        foreach ($surveys as $s) {
            $resp = $s->response;
            if ($resp) {
                $val = strtolower(trim((string) ($resp->recommend_company ?? $resp->would_recommend ?? '')));
                if ($val !== '') {
                    $recommendTotal++;
                    if ($val === 'yes') {
                        $recommendYes++;
                    }
                }
            }
        }
        $recommendationRate = $recommendTotal > 0 ? round(($recommendYes / $recommendTotal) * 100) : 0;

        // Re-hire Eligibility (Open to Reapply)
        $rehireYes = 0;
        $rehireTotal = 0;
        foreach ($surveys as $s) {
            $resp = $s->response;
            if ($resp) {
                $val = strtolower(trim((string) ($resp->open_to_reapply ?? $resp->would_return ?? '')));
                if ($val !== '') {
                    $rehireTotal++;
                    if ($val === 'yes') {
                        $rehireYes++;
                    }
                }
            }
        }
        $rehireRate = $rehireTotal > 0 ? round(($rehireYes / $rehireTotal) * 100) : 0;

        // Average Tenure
        $tenureYearsList = [];
        foreach ($surveys as $s) {
            if ($s->date_joined && $s->last_working_date) {
                $months = $s->date_joined->diffInMonths($s->last_working_date);
                $tenureYearsList[] = $months / 12;
            }
        }
        $avgTenure = count($tenureYearsList) > 0 ? round(array_sum($tenureYearsList) / count($tenureYearsList), 1) : null;

        // ── 2. Likert Scale Analysis: Satisfaction Heatmap (All 20 Areas) ────────
        $areaDefinitions = [
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

        $likertHeatmap = [];
        foreach ($areaDefinitions as $areaNum => $areaTitle) {
            $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            $sum = 0;
            $ratedCount = 0;

            foreach ($surveys as $s) {
                $resp = $s->response;
                if ($resp && is_array($resp->area_ratings)) {
                    $val = $resp->area_ratings[(string) $areaNum] ?? $resp->area_ratings[$areaNum] ?? null;
                    if (is_numeric($val) && (int) $val >= 1 && (int) $val <= 5) {
                        $intVal = (int) $val;
                        $counts[$intVal]++;
                        $sum += $intVal;
                        $ratedCount++;
                    }
                }
            }

            $areaAvg = $ratedCount > 0 ? round($sum / $ratedCount, 1) : 0;
            $pcts = [];
            foreach ([5, 4, 3, 2, 1] as $score) {
                $pcts[$score] = $ratedCount > 0 ? round(($counts[$score] / $ratedCount) * 100) : 0;
            }

            $likertHeatmap[] = [
                'number' => '1.'.$areaNum,
                'title' => $areaTitle,
                'avg' => $areaAvg,
                'rated_count' => $ratedCount,
                'counts' => $counts,
                'pcts' => $pcts,
            ];
        }

        // ── 3. Primary Reason Frequency (CHART 5) ─────────────────────────────────
        $standardReasonAliases = [
            '2. Going overseas' => ['Going oversees:', 'Going oversees', 'Going overseas', 'overseas', 'oversee'],
            '1. Joining a local company for a better position/salary' => ['Joining a local company for a better position/ salary', 'Joining a local company for a better position / salary', 'better position', 'salary'],
            '3. Shifting to another industry/career' => ['Shifting to another industry/career', 'Shifting to another industry / career', 'industry', 'career'],
            '4. Personal health related reasons' => ['Personal health related reasons', 'health'],
            '5. Family or personal commitments/reasons' => ['Family or personal commitments/reasons', 'Family or personal commitments / reasons', 'Family'],
            '6. Distance to workplace / Relocation' => ['Distance to workplace/ Relocation', 'Distance to workplace / Relocation', 'Relocation', 'Distance'],
        ];

        $reasonCounts = [
            '2. Going overseas' => 0,
            '1. Joining a local company for a better position/salary' => 0,
            '3. Shifting to another industry/career' => 0,
            '4. Personal health related reasons' => 0,
            '5. Family or personal commitments/reasons' => 0,
            '6. Distance to workplace / Relocation' => 0,
        ];

        $totalReasonRespondents = 0;
        foreach ($surveys as $s) {
            $resp = $s->response;
            if ($resp) {
                $rawReason = trim((string) ($resp->main_reason_to_leave ?? $resp->primary_resignation_reason ?? ''));
                if ($rawReason !== '') {
                    $totalReasonRespondents++;
                    $matched = false;
                    foreach ($standardReasonAliases as $label => $aliases) {
                        foreach ($aliases as $alias) {
                            if (stripos($rawReason, $alias) !== false) {
                                $reasonCounts[$label]++;
                                $matched = true;
                                break 2;
                            }
                        }
                    }
                    if (! $matched) {
                        $reasonCounts[$rawReason] = ($reasonCounts[$rawReason] ?? 0) + 1;
                    }
                }
            }
        }

        // Sort descending by count
        arsort($reasonCounts);
        $primaryReasonsChart = [];
        foreach ($reasonCounts as $label => $cnt) {
            $pct = $totalReasonRespondents > 0 ? round(($cnt / $totalReasonRespondents) * 100) : 0;
            $primaryReasonsChart[] = [
                'label' => $label,
                'count' => $cnt,
                'percentage' => $pct,
            ];
        }

        // ── 4. Secondary Exit Factors Breakdown (CHART 3 - Donut Chart) ───────────
        $secondaryFactorPalette = [
            'Inadequate salary/benefits' => '#10b981',
            'Limited opportunities for growth and career advancement' => '#0ea5e9',
            'Inadequate work-life balance' => '#f59e0b',
            'Challenging relationship with immediate supervisor' => '#8b5cf6',
            'Insufficient strategic focus from company management' => '#ec4899',
            'Unfavourable/unsupportive work culture' => '#64748b',
        ];

        $secondaryFactorCounts = [];
        foreach (array_keys($secondaryFactorPalette) as $factorName) {
            $secondaryFactorCounts[$factorName] = 0;
        }

        $totalSecondarySelections = 0;
        foreach ($surveys as $s) {
            $resp = $s->response;
            if ($resp && is_array($resp->other_reasons_to_leave)) {
                foreach ($resp->other_reasons_to_leave as $factor) {
                    $factorStr = trim((string) $factor);
                    if ($factorStr !== '') {
                        $matched = false;
                        foreach (array_keys($secondaryFactorPalette) as $defName) {
                            if (stripos($defName, $factorStr) !== false || stripos($factorStr, $defName) !== false) {
                                $secondaryFactorCounts[$defName]++;
                                $totalSecondarySelections++;
                                $matched = true;
                                break;
                            }
                        }
                        if (! $matched) {
                            $secondaryFactorCounts[$factorStr] = ($secondaryFactorCounts[$factorStr] ?? 0) + 1;
                            $totalSecondarySelections++;
                        }
                    }
                }
            }
        }

        $secondaryFactorsChart = [];
        $accumulatedOffset = 0;
        foreach ($secondaryFactorCounts as $label => $cnt) {
            $share = $totalSecondarySelections > 0 ? round(($cnt / $totalSecondarySelections) * 100) : 0;
            $color = $secondaryFactorPalette[$label] ?? '#8C0026';
            $secondaryFactorsChart[] = [
                'label' => $label,
                'count' => $cnt,
                'percentage' => $share,
                'color' => $color,
                'dash_array' => $share.' '.(100 - $share),
                'dash_offset' => 100 - $accumulatedOffset,
            ];
            $accumulatedOffset += $share;
        }

        // ── 5. Positive Work Experience Liked Most (Question 5) ───────────────────
        $likedAspectCounts = [];
        $totalLikedSelections = 0;
        foreach ($surveys as $s) {
            $resp = $s->response;
            if ($resp && is_array($resp->liked_most_aspects)) {
                foreach ($resp->liked_most_aspects as $aspect) {
                    $aspectStr = trim((string) $aspect);
                    if ($aspectStr !== '') {
                        $likedAspectCounts[$aspectStr] = ($likedAspectCounts[$aspectStr] ?? 0) + 1;
                        $totalLikedSelections++;
                    }
                }
            }
        }
        arsort($likedAspectCounts);
        $likedAspectsChart = [];
        foreach (array_slice($likedAspectCounts, 0, 6, true) as $label => $cnt) {
            $likedAspectsChart[] = [
                'label' => $label,
                'count' => $cnt,
                'percentage' => $totalLikedSelections > 0 ? round(($cnt / $totalLikedSelections) * 100) : 0,
            ];
        }

        // ── 6. Department Resignation Volume ──────────────────────────────────────
        $departmentCounts = $surveys
            ->groupBy('department')
            ->map(fn ($group) => $group->count())
            ->sortDesc()
            ->take(6);

        $selectedSurvey = $selectedSurveyId ? $surveys->firstWhere('id', (int) $selectedSurveyId) : null;

        return view('admin.analytics', compact(
            'companies',
            'availableEmployees',
            'selectedCompany',
            'selectedCompanyName',
            'fromDate',
            'toDate',
            'selectedSurveyId',
            'selectedSurvey',
            'totalSubmissions',
            'totalHeadcount',
            'avgSatisfaction',
            'recommendationRate',
            'rehireRate',
            'avgTenure',
            'likertHeatmap',
            'primaryReasonsChart',
            'secondaryFactorsChart',
            'totalSecondarySelections',
            'likedAspectsChart',
            'totalLikedSelections',
            'departmentCounts',
            'surveys'
        ));
    }
}
