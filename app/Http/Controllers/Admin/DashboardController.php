<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\ExitSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $companyIds = $user->accessibleCompanyIds();

        $selectedCompany = $request->query('company_id');
        $selectedYear = $request->query('year');
        $selectedMonth = $request->query('month');

        // Scoped active companies
        $companies = Company::where('is_active', true)
            ->when($companyIds !== null, fn ($q) => $q->whereIn('id', $companyIds))
            ->orderBy('name')
            ->get();

        // Enforce subsidiary HR scope if selected company is not accessible
        if ($selectedCompany && $companyIds !== null && ! in_array((int) $selectedCompany, $companyIds)) {
            $selectedCompany = null;
        }

        // Available years for dropdown
        $availableYears = ExitSurvey::query()
            ->when($companyIds !== null, fn ($q) => $q->whereIn('company_id', $companyIds))
            ->whereNotNull('submitted_at')
            ->selectRaw('DISTINCT YEAR(submitted_at) as year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        $currentYear = (int) date('Y');
        if (! in_array($currentYear, $availableYears)) {
            $availableYears[] = $currentYear;
            rsort($availableYears);
        }

        // Months array
        $months = [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        // Base query for completed exit surveys matching filters
        $completedSurveysQuery = ExitSurvey::query()
            ->whereIn('status', ['submitted', 'system_generated'])
            ->when($companyIds !== null, fn ($q) => $q->whereIn('company_id', $companyIds))
            ->when($selectedCompany, fn ($q) => $q->where('company_id', $selectedCompany))
            ->when($selectedYear, fn ($q) => $q->whereYear(DB::raw('COALESCE(submitted_at, created_at)'), $selectedYear))
            ->when($selectedMonth, fn ($q) => $q->whereMonth(DB::raw('COALESCE(submitted_at, created_at)'), $selectedMonth));

        // 1. Total Exits
        $totalExits = (clone $completedSurveysQuery)->count();

        // 2. Total Headcount
        if ($selectedCompany) {
            $totalHeadcount = (int) ($companies->firstWhere('id', (int) $selectedCompany)?->headcount ?? 0);
            $selectedCompanyName = $companies->firstWhere('id', (int) $selectedCompany)?->name;
        } else {
            $totalHeadcount = (int) $companies->sum('headcount');
            $selectedCompanyName = null;
        }

        // Retrieve completed surveys with response to compute satisfaction & recommendation rate
        $completedSurveys = (clone $completedSurveysQuery)
            ->with(['company', 'response'])
            ->get();

        // 3. Recommendation Rate (% Yes)
        $recommendCount = 0;
        $recommendTotal = 0;
        foreach ($completedSurveys as $s) {
            $resp = $s->response;
            if ($resp) {
                $val = strtolower(trim((string) ($resp->recommend_company ?? $resp->would_recommend ?? '')));
                if ($val !== '') {
                    $recommendTotal++;
                    if ($val === 'yes') {
                        $recommendCount++;
                    }
                }
            }
        }
        $recommendationRate = $recommendTotal > 0 ? round(($recommendCount / $recommendTotal) * 100) : 0;

        // 4. Re-hire Eligibility (% Open Reapply / Yes)
        $rehireCount = 0;
        $rehireTotal = 0;
        foreach ($completedSurveys as $s) {
            $resp = $s->response;
            if ($resp) {
                $val = strtolower(trim((string) ($resp->open_to_reapply ?? $resp->would_return ?? '')));
                if ($val !== '') {
                    $rehireTotal++;
                    if ($val === 'yes') {
                        $rehireCount++;
                    }
                }
            }
        }
        $rehireRate = $rehireTotal > 0 ? round(($rehireCount / $rehireTotal) * 100) : 0;

        // 5. Avg Satisfaction
        $satisfactions = $completedSurveys
            ->map(fn ($s) => $s->overallSatisfaction())
            ->filter(fn ($score) => $score !== null && $score > 0);
        $avgSatisfaction = $satisfactions->count() > 0 ? round($satisfactions->avg(), 1) : null;

        // 6. Chart 1: Resignations by Subsidiary / Company
        $chartCompanies = [];
        $maxCompanyExits = 0;
        foreach ($companies as $comp) {
            // Count completed surveys for this company matching date filters
            $cCount = (clone $completedSurveysQuery)->where('company_id', $comp->id)->count();
            if ($cCount > $maxCompanyExits) {
                $maxCompanyExits = $cCount;
            }
            $chartCompanies[] = [
                'id' => $comp->id,
                'name' => $comp->name,
                'code' => $comp->code ?: $comp->name,
                'count' => $cCount,
            ];
        }

        // 7. Chart 2: Primary Reason Frequency (ranked highest to lowest)
        $standardReasonAliases = [
            '2. Going overseas' => ['Going oversees:', 'Going oversees', 'Going overseas', 'overseas', 'oversee'],
            '3. Shifting to another industry/career' => ['Shifting to another industry/career', 'Shifting to another industry / career', 'industry', 'career'],
            '1. Joining a local company for better position/salary' => ['Joining a local company for a better position/ salary', 'Joining a local company for a better position / salary', 'better position', 'salary'],
            '4. Personal health related reasons' => ['Personal health related reasons', 'health'],
            '5. Family or personal commitments/reasons' => ['Family or personal commitments/reasons', 'Family or personal commitments / reasons', 'Family'],
            '6. Distance to workplace / Relocation' => ['Distance to workplace/ Relocation', 'Distance to workplace / Relocation', 'Relocation', 'Distance'],
        ];

        $reasonCounts = [
            '2. Going overseas' => 0,
            '3. Shifting to another industry/career' => 0,
            '1. Joining a local company for better position/salary' => 0,
            '4. Personal health related reasons' => 0,
            '5. Family or personal commitments/reasons' => 0,
            '6. Distance to workplace / Relocation' => 0,
        ];

        foreach ($completedSurveys as $s) {
            $resp = $s->response;
            if ($resp) {
                $rawReason = trim((string) ($resp->main_reason_to_leave ?? $resp->primary_resignation_reason ?? ''));
                if ($rawReason !== '') {
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

        // Sort reasons descending by frequency
        arsort($reasonCounts);
        $maxReasonCount = max(array_values($reasonCounts) ?: [1]);
        if ($maxReasonCount < 1) {
            $maxReasonCount = 1;
        }

        $chartReasons = [];
        foreach ($reasonCounts as $label => $cnt) {
            $chartReasons[] = [
                'label' => $label,
                'count' => $cnt,
                'percentage' => $maxReasonCount > 0 ? round(($cnt / $maxReasonCount) * 100) : 0,
            ];
        }

        // Recent completed submissions (filtered)
        $recentSubmissions = (clone $completedSurveysQuery)
            ->with(['company', 'response'])
            ->latest('submitted_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'companies',
            'availableYears',
            'months',
            'selectedCompany',
            'selectedYear',
            'selectedMonth',
            'selectedCompanyName',
            'totalExits',
            'totalHeadcount',
            'recommendationRate',
            'rehireRate',
            'avgSatisfaction',
            'chartCompanies',
            'maxCompanyExits',
            'chartReasons',
            'maxReasonCount',
            'recentSubmissions'
        ));
    }
}
