@extends('layouts.admin')

@section('title', 'Analytics & Intelligence')
@section('page-title', 'Exit Analytics & Retention Intelligence')

@section('content')

<!-- ── Single-Line Filter Toolbar Banner ── -->
<div class="analytics-filter-banner">
    <div class="filter-header-title-row">
        <div class="title-with-icon">
            <div class="filter-banner-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <div>
                <h1 class="filter-main-heading">Exit Analytics & Retention Intelligence</h1>
                <p class="filter-sub-heading">Real-time organizational diagnostic: 20-item Likert heatmap, departure drivers, and retention insights.</p>
            </div>
        </div>

        <div class="counter-badge">
            Showing {{ $totalSubmissions }} {{ Str::plural('Record', $totalSubmissions) }}
        </div>
    </div>

    <!-- Single Horizontal Line Filter Form -->
    <form method="GET" action="{{ route('admin.analytics') }}" id="analyticsFiltersForm" class="single-line-filter-form">
        <!-- 1. Company Searchable Dropdown -->
        <div class="searchable-dropdown-wrap" id="companyDropdownWrap" style="flex: 1.1; min-width: 140px;">
            <input type="hidden" name="company_id" id="hiddenCompanyId" value="{{ $selectedCompany }}">
            <button type="button" class="searchable-trigger-btn" id="companyTriggerBtn" onclick="toggleSearchDropdown('company', event)" aria-expanded="false" aria-haspopup="listbox">
                <span class="trigger-text" id="companyTriggerText">
                    {{ $selectedCompany && $companies->firstWhere('id', $selectedCompany) ? $companies->firstWhere('id', $selectedCompany)->name : 'All Companies' }}
                </span>
                <span class="trigger-icons">
                    @if(!empty($selectedCompany))
                        <span class="clear-selection-btn" onclick="clearSelection('company', event)" title="Clear company selection">&times;</span>
                    @endif
                    <svg class="trigger-chevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </span>
            </button>

            <!-- Floating Menu -->
            <div class="searchable-dropdown-menu" id="companyDropdownMenu">
                <div class="dropdown-search-header">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" class="dropdown-search-input" id="companySearchInput" placeholder="Search company..." oninput="filterSearchOptions('company', this.value)" autocomplete="off">
                    <button type="button" class="dropdown-clear-input" onclick="clearSearchInput('company', event)">&times;</button>
                </div>
                <div class="dropdown-options-scroll" id="companyOptionsList">
                    <div class="dropdown-item-option {{ empty($selectedCompany) ? 'is-selected' : '' }}" onclick="selectSearchOption('company', '', 'All Companies')">
                        <span class="item-title">All Companies</span>
                        @if(empty($selectedCompany))
                            <svg class="item-check-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        @endif
                    </div>
                    @foreach($companies as $comp)
                        <div class="dropdown-item-option {{ (string)$selectedCompany === (string)$comp->id ? 'is-selected' : '' }}"
                             data-search="{{ strtolower($comp->name . ' ' . $comp->code) }}"
                             onclick="selectSearchOption('company', '{{ $comp->id }}', '{{ addslashes($comp->name) }}')">
                            <span class="item-title">{{ $comp->name }}</span>
                            @if((string)$selectedCompany === (string)$comp->id)
                                <svg class="item-check-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            @endif
                        </div>
                    @endforeach
                    <div class="dropdown-no-results" id="companyNoResults" style="display: none;">No companies match your search</div>
                </div>
            </div>
        </div>

        <!-- 2. Individual Employee Searchable Dropdown -->
        <div class="searchable-dropdown-wrap" id="employeeDropdownWrap" style="flex: 1.6; min-width: 180px;">
            <input type="hidden" name="survey_id" id="hiddenSurveyId" value="{{ $selectedSurveyId }}">
            <button type="button" class="searchable-trigger-btn" id="employeeTriggerBtn" onclick="toggleSearchDropdown('employee', event)" aria-expanded="false" aria-haspopup="listbox">
                <span class="trigger-text" id="employeeTriggerText">
                    @php
                        $currEmp = $selectedSurveyId ? $availableEmployees->firstWhere('id', (int)$selectedSurveyId) : null;
                    @endphp
                    {{ $currEmp ? $currEmp->employee_name . ' (' . ($currEmp->employee_id ?: 'ID N/A') . ')' : 'All Departing Employees' }}
                </span>
                <span class="trigger-icons">
                    @if(!empty($selectedSurveyId))
                        <span class="clear-selection-btn" onclick="clearSelection('employee', event)" title="Clear employee selection">&times;</span>
                    @endif
                    <svg class="trigger-chevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </span>
            </button>

            <!-- Floating Menu -->
            <div class="searchable-dropdown-menu" id="employeeDropdownMenu">
                <div class="dropdown-search-header">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" class="dropdown-search-input" id="employeeSearchInput" placeholder="Search employee name, ID, department..." oninput="filterSearchOptions('employee', this.value)" autocomplete="off">
                    <button type="button" class="dropdown-clear-input" onclick="clearSearchInput('employee', event)">&times;</button>
                </div>
                <div class="dropdown-options-scroll" id="employeeOptionsList">
                    <div class="dropdown-item-option {{ empty($selectedSurveyId) ? 'is-selected' : '' }}" onclick="selectSearchOption('employee', '', 'All Departing Employees')">
                        <div class="item-text-stack">
                            <span class="item-title">All Departing Employees</span>
                        </div>
                        @if(empty($selectedSurveyId))
                            <svg class="item-check-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        @endif
                    </div>
                    @foreach($availableEmployees as $emp)
                        <div class="dropdown-item-option {{ (string)$selectedSurveyId === (string)$emp->id ? 'is-selected' : '' }}"
                             data-search="{{ strtolower($emp->employee_name . ' ' . $emp->employee_id . ' ' . $emp->department . ' ' . $emp->company?->name) }}"
                             onclick="selectSearchOption('employee', '{{ $emp->id }}', '{{ addslashes($emp->employee_name . ' (' . ($emp->employee_id ?: 'ID N/A') . ')') }}')">
                            <div class="item-text-stack">
                                <div class="item-title">{{ $emp->employee_name }}</div>
                                <div class="item-subtitle">{{ $emp->employee_id ?: 'ID N/A' }} • {{ $emp->department }} • {{ $emp->company?->name }}</div>
                            </div>
                            @if((string)$selectedSurveyId === (string)$emp->id)
                                <svg class="item-check-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            @endif
                        </div>
                    @endforeach
                    <div class="dropdown-no-results" id="employeeNoResults" style="display: none;">No employees match your search</div>
                </div>
            </div>
        </div>

        <!-- 3. From Date -->
        <div class="single-line-date-box" style="flex: 1; min-width: 135px;">
            <span class="date-inline-label">From:</span>
            <input type="date" name="from_date" value="{{ $fromDate }}" class="single-filter-date" onchange="this.form.submit()" aria-label="From Date">
        </div>

        <!-- 4. To Date -->
        <div class="single-line-date-box" style="flex: 1; min-width: 135px;">
            <span class="date-inline-label">To:</span>
            <input type="date" name="to_date" value="{{ $toDate }}" class="single-filter-date" onchange="this.form.submit()" aria-label="To Date">
        </div>

        <!-- 5. Actions Group -->
        <div class="single-line-actions">
            <button type="submit" class="btn-single-filter">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                Filter
            </button>
            <a href="{{ route('admin.analytics') }}" class="btn-single-reset" title="Clear all filters">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                    <path d="M3 3v5h5"></path>
                </svg>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- ── Top KPI Metric Cards ── -->
<div class="analytics-kpi-grid">
    <!-- Card 1: Total Completed Submissions -->
    <div class="metric-card dark-card">
        <div class="metric-card-top">
            <span class="metric-label">SUBMISSIONS & EXITS</span>
            <div class="metric-icon-box">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
        </div>
        <div class="metric-split-row">
            <div>
                <div class="metric-big-number">{{ $totalSubmissions }}</div>
                <div class="metric-sub-label">TOTAL SUBMITTED</div>
            </div>
            <div class="metric-split-line"></div>
            <div>
                <div class="metric-big-number">{{ number_format($totalHeadcount) }}</div>
                <div class="metric-sub-label">TOTAL HEADCOUNT</div>
            </div>
        </div>
        <div class="metric-footer-text">
            {{ $selectedCompanyName ? 'Company: ' . $selectedCompanyName : 'Across accessible companies' }}
        </div>
    </div>

    <!-- Card 2: Overall Satisfaction -->
    <div class="metric-card burgundy-card">
        <div class="metric-card-top">
            <span class="metric-label">OVERALL SATISFACTION</span>
            <div class="metric-icon-box">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </div>
        </div>
        <div class="metric-single-number">
            {{ $avgSatisfaction ? number_format($avgSatisfaction, 1) : '—' }}
            <span class="metric-denom">/ 5.0</span>
        </div>
        <div class="metric-footer-text-white">Mean Likert across all 20 areas</div>
    </div>

    <!-- Card 3: Recommendation Rate -->
    <div class="metric-card wine-card">
        <div class="metric-card-top">
            <span class="metric-label">RECOMMENDATION RATE</span>
            <div class="metric-icon-box">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                </svg>
            </div>
        </div>
        <div class="metric-single-number">{{ $recommendationRate }}%</div>
        <div class="metric-footer-text-white">Would recommend company</div>
    </div>

    <!-- Card 4: Re-Hire Eligibility -->
    <div class="metric-card deep-card">
        <div class="metric-card-top">
            <span class="metric-label">RE-HIRE ELIGIBILITY</span>
            <div class="metric-icon-box">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"></polyline>
                    <polyline points="1 20 1 14 7 14"></polyline>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                </svg>
            </div>
        </div>
        <div class="metric-single-number">{{ $rehireRate }}%</div>
        <div class="metric-footer-text-white">Open to reapply in future</div>
    </div>

    <!-- Card 5: Average Tenure -->
    <div class="metric-card navy-card">
        <div class="metric-card-top">
            <span class="metric-label">AVERAGE TENURE</span>
            <div class="metric-icon-box">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
        </div>
        <div class="metric-single-number">
            {{ $avgTenure ? $avgTenure : '—' }}
            <span class="metric-denom">Yrs</span>
        </div>
        <div class="metric-footer-text-white">Average service period</div>
    </div>
</div>

<!-- ── Individual Employee Verbatim Qualitative Feedback Card ── -->
@if($selectedSurvey)
    @php
        $indResp = $selectedSurvey->response;
        $preventedAns = $indResp ? ($indResp->prevented_resignation ?? $indResp->resignation_elaboration) : null;
        $enjoyedAns = $indResp ? $indResp->enjoyed_most : null;
        $commentsAns = $indResp ? ($indResp->other_comments ?? $indResp->improvement_suggestions) : null;
    @endphp
    <div class="individual-feedback-panel">
        <div class="feedback-panel-header">
            <div class="employee-header-left">
                <div class="feedback-avatar">
                    {{ strtoupper(substr($selectedSurvey->employee_name, 0, 2)) }}
                </div>
                <div>
                    <div class="feedback-panel-title">
                        Individual Employee Feedback: <span style="color: #8C0026;">{{ $selectedSurvey->employee_name }}</span>
                    </div>
                    <div class="feedback-panel-meta">
                        {{ $selectedSurvey->employee_id ? 'Employee ID: ' . $selectedSurvey->employee_id . ' • ' : '' }}
                        {{ $selectedSurvey->designation }} • {{ $selectedSurvey->department }} • {{ $selectedSurvey->company?->name }}
                        • Submitted {{ $selectedSurvey->submitted_at?->format('d M Y') ?? $selectedSurvey->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.surveys.pdf', $selectedSurvey) }}" class="btn-download-pdf-badge" target="_blank" title="Download Form PDF">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Download 2-Page PDF Form
            </a>
        </div>

        <div class="feedback-questions-grid">
            <!-- 1. What would have prevented your resignation -->
            <div class="feedback-q-card rose-box">
                <div class="feedback-q-title">
                    <span class="q-badge-icon">🛡️</span>
                    <span>What would have prevented your resignation: *</span>
                </div>
                <div class="feedback-q-body">
                    @if(!empty(trim((string)$preventedAns)))
                        “{{ $preventedAns }}”
                    @else
                        <span class="empty-q-text">No comments provided</span>
                    @endif
                </div>
            </div>

            <!-- 2. What did you enjoy most about working here -->
            <div class="feedback-q-card emerald-box">
                <div class="feedback-q-title">
                    <span class="q-badge-icon">❤️</span>
                    <span>In your own words, what did you enjoy most about working here? *</span>
                </div>
                <div class="feedback-q-body">
                    @if(!empty(trim((string)$enjoyedAns)))
                        “{{ $enjoyedAns }}”
                    @else
                        <span class="empty-q-text">No comments provided</span>
                    @endif
                </div>
            </div>

            <!-- 3. Any other comments -->
            <div class="feedback-q-card slate-box">
                <div class="feedback-q-title">
                    <span class="q-badge-icon">💬</span>
                    <span>Any other comments: *</span>
                </div>
                <div class="feedback-q-body">
                    @if(!empty(trim((string)$commentsAns)))
                        “{{ $commentsAns }}”
                    @else
                        <span class="empty-q-text">No comments provided</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<!-- ── Main Visualizations: Heatmap & Side Charts ── -->
<div class="analytics-content-grid">
    <!-- LEFT COLUMN: SATISFACTION HEATMAP (LIKERT SCALE ANALYSIS) -->
    <div class="analytics-card heatmap-card">
        <div class="card-title-header">
            <span class="section-tag-label">ANALYTICS</span>
            <h2 class="section-main-heading">SATISFACTION HEATMAP (LIKERT SCALE ANALYSIS)</h2>
            <p class="section-sub-heading">Full 20-item Likert breakdown: share of ratings across the five-point scale for each survey area, shown in survey order with per-area averages.</p>
        </div>

        <div class="heatmap-rows-wrap">
            @foreach($likertHeatmap as $row)
                <div class="heatmap-row">
                    <div class="heatmap-row-info">
                        <div class="item-title" title="{{ $row['number'] }} {{ $row['title'] }}">
                            {{ $row['number'] }} {{ $row['title'] }}
                        </div>
                        <div class="item-meta">
                            Avg {{ $row['avg'] }} • {{ $row['rated_count'] }} rated
                        </div>
                    </div>

                    <!-- 100% Stacked Segmented Horizontal Bar -->
                    <div class="heatmap-bar-track">
                        @if($row['rated_count'] > 0)
                            <!-- 5: Highly Satisfied -->
                            @if($row['pcts'][5] > 0)
                                <div class="bar-segment seg-5" style="width: {{ $row['pcts'][5] }}%;"
                                     title="Highly Satisfied (5): {{ $row['counts'][5] }} ({{ $row['pcts'][5] }}%)">
                                    @if($row['pcts'][5] >= 8) <span>{{ $row['pcts'][5] }}%</span> @endif
                                </div>
                            @endif

                            <!-- 4: Satisfied -->
                            @if($row['pcts'][4] > 0)
                                <div class="bar-segment seg-4" style="width: {{ $row['pcts'][4] }}%;"
                                     title="Satisfied (4): {{ $row['counts'][4] }} ({{ $row['pcts'][4] }}%)">
                                    @if($row['pcts'][4] >= 8) <span>{{ $row['pcts'][4] }}%</span> @endif
                                </div>
                            @endif

                            <!-- 3: Average -->
                            @if($row['pcts'][3] > 0)
                                <div class="bar-segment seg-3" style="width: {{ $row['pcts'][3] }}%;"
                                     title="Average (3): {{ $row['counts'][3] }} ({{ $row['pcts'][3] }}%)">
                                    @if($row['pcts'][3] >= 8) <span>{{ $row['pcts'][3] }}%</span> @endif
                                </div>
                            @endif

                            <!-- 2: Dissatisfied -->
                            @if($row['pcts'][2] > 0)
                                <div class="bar-segment seg-2" style="width: {{ $row['pcts'][2] }}%;"
                                     title="Dissatisfied (2): {{ $row['counts'][2] }} ({{ $row['pcts'][2] }}%)">
                                    @if($row['pcts'][2] >= 8) <span>{{ $row['pcts'][2] }}%</span> @endif
                                </div>
                            @endif

                            <!-- 1: Highly Dissatisfied -->
                            @if($row['pcts'][1] > 0)
                                <div class="bar-segment seg-1" style="width: {{ $row['pcts'][1] }}%;"
                                     title="Highly Dissatisfied (1): {{ $row['counts'][1] }} ({{ $row['pcts'][1] }}%)">
                                    @if($row['pcts'][1] >= 8) <span>{{ $row['pcts'][1] }}%</span> @endif
                                </div>
                            @endif
                        @else
                            <div class="empty-bar-segment">No ratings recorded</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Heatmap Legend -->
        <div class="heatmap-legend-wrap">
            <div class="legend-chip"><span class="legend-dot seg-5"></span> Highly Satisfied</div>
            <div class="legend-chip"><span class="legend-dot seg-4"></span> Satisfied</div>
            <div class="legend-chip"><span class="legend-dot seg-3"></span> Average</div>
            <div class="legend-chip"><span class="legend-dot seg-2"></span> Dissatisfied</div>
            <div class="legend-chip"><span class="legend-dot seg-1"></span> Highly Dissatisfied</div>
        </div>

        <div class="heatmap-footer-note">
            Based on {{ $totalSubmissions }} completed submissions with per-question ratings. Each row is one of the 20 Likert areas from the survey; every bar totals 100% of the ratings recorded for that area. Hover a segment for its exact count and share.
        </div>
    </div>

    <!-- RIGHT COLUMN: CHART 5 & CHART 3 -->
    <div class="analytics-side-column">
        <!-- CHART 5: Primary Reason Frequency -->
        <div class="analytics-card">
            <div class="card-title-header">
                <span class="section-tag-label">CHART 5</span>
                <h2 class="section-main-heading">Primary Reason Frequency</h2>
                <p class="section-sub-heading">Most cited primary reasons for leaving, ranked from highest to lowest share.</p>
            </div>

            <div class="primary-reasons-bars-list">
                @foreach($primaryReasonsChart as $reasonItem)
                    <div class="reason-ranking-row">
                        <div class="reason-name-text" title="{{ $reasonItem['label'] }}">
                            {{ $reasonItem['label'] }}
                        </div>
                        <div class="reason-progress-wrap">
                            <div class="reason-pill-track">
                                @if($reasonItem['percentage'] > 0)
                                    <div class="reason-pill-bar" style="width: {{ $reasonItem['percentage'] }}%;"></div>
                                @endif
                            </div>
                            <span class="reason-pct-value {{ $reasonItem['percentage'] > 0 ? 'active' : '' }}">
                                {{ $reasonItem['percentage'] }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- CHART 3: Secondary Exit Factors Breakdown (Donut Chart) -->
        <div class="analytics-card">
            <div class="card-title-header">
                <span class="section-tag-label">CHART 3</span>
                <h2 class="section-main-heading">Secondary Exit Factors Breakdown</h2>
                <p class="section-sub-heading">Distribution of contributing factors selected under additional exit reasons.</p>
            </div>

            <div class="donut-chart-container">
                <div class="donut-svg-wrapper">
                    <svg viewBox="0 0 36 36" class="donut-svg">
                        <!-- Background Circle -->
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#f1f5f9" stroke-width="4.2"></circle>

                        @if($totalSecondarySelections > 0)
                            @foreach($secondaryFactorsChart as $factor)
                                @if($factor['percentage'] > 0)
                                    <circle cx="18" cy="18" r="15.9155" fill="none"
                                            stroke="{{ $factor['color'] }}"
                                            stroke-width="4.5"
                                            stroke-dasharray="{{ $factor['dash_array'] }}"
                                            stroke-dashoffset="{{ $factor['dash_offset'] }}">
                                    </circle>
                                @endif
                            @endforeach
                        @else
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e2e8f0" stroke-width="4.5" stroke-dasharray="100 0"></circle>
                        @endif
                    </svg>

                    <!-- Center Text -->
                    <div class="donut-center-info">
                        <div class="donut-total-count">{{ $totalSecondarySelections }}</div>
                        <div class="donut-total-label">SELECTIONS</div>
                    </div>
                </div>

                <!-- Donut Legend List -->
                <div class="donut-factors-legend">
                    @foreach($secondaryFactorsChart as $factor)
                        <div class="donut-legend-item">
                            <div class="legend-left">
                                <span class="factor-color-dot" style="background-color: {{ $factor['color'] }};"></span>
                                <span class="factor-title" title="{{ $factor['label'] }}">{{ $factor['label'] }}</span>
                            </div>
                            <span class="factor-pct-tag">{{ $factor['percentage'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Positive Aspects Liked Most (Question 5) -->
        <div class="analytics-card">
            <div class="card-title-header">
                <span class="section-tag-label">ORGANIZATIONAL STRENGTHS</span>
                <h2 class="section-main-heading">Positive Work Experience Liked Most</h2>
                <p class="section-sub-heading">Key workplace attributes departing employees appreciated most during their tenure.</p>
            </div>

            <div class="liked-aspects-list">
                @forelse($likedAspectsChart as $likedItem)
                    <div class="liked-row">
                        <div class="liked-label" title="{{ $likedItem['label'] }}">{{ $likedItem['label'] }}</div>
                        <div class="liked-bar-wrap">
                            <div class="liked-bar-fill" style="width: {{ $likedItem['percentage'] }}%;"></div>
                        </div>
                        <div class="liked-count-badge">{{ $likedItem['count'] }} ({{ $likedItem['percentage'] }}%)</div>
                    </div>
                @empty
                    <div class="empty-state-muted">No positive aspects recorded yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- ── Table of Submitted Forms ── -->
<div class="analytics-card submissions-table-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="section-main-heading" style="margin: 0; font-size: 17px;">📋 Submitted Exit Interview Records</h2>
            <p class="section-sub-heading" style="margin-top: 3px;">Full database log of completed submissions matching current filter parameters.</p>
        </div>
        <a href="{{ route('admin.surveys.index') }}" class="btn btn-ghost btn-sm">Manage All Surveys →</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Company</th>
                    <th>Designation & Dept</th>
                    <th>Primary Reason for Leaving</th>
                    <th>Satisfaction</th>
                    <th>Recommend</th>
                    <th>Submitted</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surveys as $survey)
                    @php
                        $resp = $survey->response;
                        $rating = $survey->overallSatisfaction();
                        $reason = $resp ? ($resp->main_reason_to_leave ?? $resp->primary_resignation_reason) : null;
                        $rec = $resp ? ($resp->recommend_company ?? $resp->would_recommend) : null;
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">{{ $survey->employee_name }}</div>
                            <div style="font-size: 11px; color: #64748b;">ID: {{ $survey->employee_id ?: 'N/A' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-info" style="font-weight: 600;">{{ $survey->company?->name }}</span>
                        </td>
                        <td>
                            <div style="font-size: 12.5px; font-weight: 600; color: #334155;">{{ $survey->designation }}</div>
                            <div style="font-size: 11.5px; color: #64748b;">{{ $survey->department }}</div>
                        </td>
                        <td>
                            <span style="font-size: 12.5px; color: #1e293b; font-weight: 500;">
                                {{ $reason ? Str::limit($reason, 38) : 'Not specified' }}
                            </span>
                        </td>
                        <td>
                            @if($rating)
                                <div style="display: flex; align-items: center; gap: 4px; font-weight: 700; color: #c9993a;">
                                    <span>★</span>
                                    <span>{{ number_format($rating, 1) }}</span>
                                </div>
                            @else
                                <span style="color: #94a3b8;">—</span>
                            @endif
                        </td>
                        <td>
                            @if(strtolower((string)$rec) === 'yes')
                                <span class="badge badge-success" style="font-size: 11px;">Yes</span>
                            @elseif(strtolower((string)$rec) === 'no')
                                <span class="badge badge-danger" style="font-size: 11px;">No</span>
                            @else
                                <span style="color: #94a3b8;">—</span>
                            @endif
                        </td>
                        <td style="font-size: 12px; color: #64748b;">
                            {{ $survey->submitted_at ? $survey->submitted_at->format('d M Y') : $survey->created_at->format('d M Y') }}
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('admin.analytics', array_merge(request()->query(), ['survey_id' => $survey->id])) }}" class="btn btn-ghost btn-xs" style="color: #8C0026; font-weight: 600;" title="Inspect Comments">
                                    💬 Inspect
                                </a>
                                <a href="{{ route('admin.surveys.show', $survey) }}" class="btn btn-ghost btn-xs" title="View Dossier">
                                    👁 View
                                </a>
                                <a href="{{ route('admin.surveys.pdf', $survey) }}" class="btn btn-ghost btn-xs" style="color: #8C0026;" title="Download PDF Form">
                                    📥 PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #64748b; padding: 48px;">
                            No completed survey records found matching the selected filter criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
/* ── Top Filter Banner & Single-Line Toolbar ── */
.analytics-filter-banner {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 18px 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
}
.filter-header-title-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 10px;
}
.title-with-icon {
    display: flex;
    align-items: center;
    gap: 12px;
}
.filter-banner-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #FFF0F3;
    border: 1px solid #FFE4E8;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8C0026;
    flex-shrink: 0;
}
.filter-main-heading {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.3px;
    margin: 0;
    line-height: 1.25;
}
.filter-sub-heading {
    font-size: 12.5px;
    color: #64748b;
    margin: 2px 0 0;
}
.counter-badge {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-size: 11.5px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 9999px;
}

/* ── Single Horizontal Line Filter Form ── */
.single-line-filter-form {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
}
/* ── Searchable Dropdowns in Filter Toolbar ── */
.searchable-dropdown-wrap {
    position: relative;
    display: inline-block;
}
.searchable-trigger-btn {
    width: 100%;
    height: 38px;
    padding: 0 10px 0 12px;
    border-radius: 9px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    cursor: pointer;
    text-align: left;
    transition: all 0.15s ease;
}
.searchable-trigger-btn:hover {
    border-color: #94a3b8;
}
.searchable-trigger-btn[aria-expanded="true"] {
    border-color: #8C0026;
    box-shadow: 0 0 0 3px rgba(140, 0, 38, 0.12);
}
.trigger-text {
    font-size: 12.5px;
    font-weight: 500;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}
.trigger-icons {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
}
.clear-selection-btn {
    font-size: 15px;
    font-weight: 700;
    color: #94a3b8;
    line-height: 1;
    padding: 2px 4px;
    border-radius: 4px;
    cursor: pointer;
    transition: color 0.15s;
}
.clear-selection-btn:hover {
    color: #ef4444;
}
.trigger-chevron {
    color: #64748b;
    transition: transform 0.2s ease;
}
.searchable-trigger-btn[aria-expanded="true"] .trigger-chevron {
    transform: rotate(180deg);
}

/* Floating Dropdown Menu */
.searchable-dropdown-menu {
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    width: 100%;
    min-width: 270px;
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.14);
    z-index: 1000;
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: fadeInDown 0.15s ease-out;
}
.searchable-dropdown-menu.is-open {
    display: flex;
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
.dropdown-search-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    color: #64748b;
}
.dropdown-search-input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 12.5px;
    color: #1e293b;
    padding: 2px 0;
}
.dropdown-search-input::placeholder {
    color: #94a3b8;
}
.dropdown-clear-input {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 14px;
    cursor: pointer;
    padding: 0 2px;
}
.dropdown-clear-input:hover {
    color: #475569;
}
.dropdown-options-scroll {
    max-height: 250px;
    overflow-y: auto;
    padding: 6px;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.dropdown-item-option {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.12s ease;
}
.dropdown-item-option:hover {
    background: #FFF0F3;
}
.dropdown-item-option.is-selected {
    background: #FFF0F3;
    color: #8C0026;
    font-weight: 600;
}
.item-text-stack {
    display: flex;
    flex-direction: column;
    gap: 1px;
    overflow: hidden;
}
.item-title {
    font-size: 12.5px;
    color: #1e293b;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dropdown-item-option:hover .item-title,
.dropdown-item-option.is-selected .item-title {
    color: #8C0026;
}
.item-subtitle {
    font-size: 11px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.item-check-icon {
    color: #8C0026;
    flex-shrink: 0;
    margin-left: 8px;
}
.dropdown-no-results {
    padding: 18px 12px;
    text-align: center;
    font-size: 12px;
    color: #94a3b8;
}
.single-line-date-box {
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    padding: 0 8px 0 10px;
    height: 38px;
}
.date-inline-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    margin-right: 6px;
    white-space: nowrap;
    text-transform: uppercase;
}
.single-filter-date {
    border: none;
    outline: none;
    font-size: 12.5px;
    color: #1e293b;
    background: transparent;
    width: 100%;
    cursor: pointer;
}
.single-line-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}
.btn-single-filter {
    height: 38px;
    padding: 0 14px;
    border-radius: 9px;
    background: #8C0026;
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 600;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.15s ease;
}
.btn-single-filter:hover {
    background: #66001C;
}
.btn-single-reset {
    height: 38px;
    padding: 0 12px;
    border-radius: 9px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
    font-size: 12.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.15s ease;
}
.btn-single-reset:hover {
    background: #f1f5f9;
    color: #0f172a;
}

/* ── Top KPI Grid ── */
.analytics-kpi-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.metric-card {
    border-radius: 16px;
    padding: 20px 22px;
    color: #ffffff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 148px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}
.dark-card {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.burgundy-card {
    background: linear-gradient(135deg, #8C0026 0%, #600018 100%);
    border: 1px solid rgba(140, 0, 38, 0.3);
}
.wine-card {
    background: linear-gradient(135deg, #7A0A26 0%, #4D0014 100%);
    border: 1px solid rgba(122, 10, 38, 0.3);
}
.deep-card {
    background: linear-gradient(135deg, #5B0018 0%, #38000F 100%);
    border: 1px solid rgba(91, 0, 24, 0.3);
}
.navy-card {
    background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%);
    border: 1px solid rgba(30, 58, 138, 0.3);
}
.metric-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.metric-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: rgba(255, 255, 255, 0.75);
    text-transform: uppercase;
}
.metric-icon-box {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    flex-shrink: 0;
}
.metric-split-row {
    display: flex;
    align-items: center;
    margin: 10px 0 4px;
}
.metric-big-number {
    font-size: 26px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1;
}
.metric-sub-label {
    font-size: 9.5px;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.5px;
    margin-top: 4px;
    text-transform: uppercase;
}
.metric-split-line {
    width: 1px;
    height: 34px;
    background: rgba(255, 255, 255, 0.16);
    margin: 0 14px;
}
.metric-single-number {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1;
    margin: 10px 0 4px;
}
.metric-denom {
    font-size: 16px;
    font-weight: 600;
    opacity: 0.8;
}
.metric-footer-text {
    font-size: 11px;
    color: #94a3b8;
}
.metric-footer-text-white {
    font-size: 11.5px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.85);
}

/* ── Individual Employee Qualitative Feedback Panel ── */
.individual-feedback-panel {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 22px 24px;
    margin-bottom: 26px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}
.feedback-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}
.employee-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.feedback-avatar {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #8C0026 0%, #600018 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 800;
    flex-shrink: 0;
    box-shadow: 0 3px 8px rgba(140, 0, 38, 0.3);
}
.feedback-panel-title {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
}
.feedback-panel-meta {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}
.btn-download-pdf-badge {
    height: 34px;
    padding: 0 14px;
    border-radius: 8px;
    background: #FFF0F3;
    border: 1px solid #FFE4E8;
    color: #8C0026;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.15s ease;
}
.btn-download-pdf-badge:hover {
    background: #8C0026;
    color: #ffffff;
}

.feedback-questions-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}
.feedback-q-card {
    border-radius: 12px;
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.rose-box {
    background: #FFF5F7;
    border: 1px solid #FCE4EC;
    border-left: 4px solid #8C0026;
}
.emerald-box {
    background: #F0FDF4;
    border: 1px solid #DCFCE7;
    border-left: 4px solid #10b981;
}
.slate-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-left: 4px solid #3b82f6;
}
.feedback-q-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 12px;
    line-height: 1.35;
}
.q-badge-icon {
    font-size: 16px;
    flex-shrink: 0;
}
.feedback-q-body {
    font-size: 13.5px;
    line-height: 1.6;
    color: #334155;
    font-style: italic;
    background: #ffffff;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    min-height: 80px;
}
.empty-q-text {
    color: #94a3b8;
    font-style: normal;
    font-size: 12.5px;
}

/* ── Content Grid: Heatmap Left, Charts Right ── */
.analytics-content-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 24px;
    margin-bottom: 28px;
}
.analytics-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 24px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
}
.card-title-header {
    margin-bottom: 20px;
}
.section-tag-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #94a3b8;
    text-transform: uppercase;
}
.section-main-heading {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin: 2px 0 0;
    line-height: 1.3;
}
.section-sub-heading {
    font-size: 12px;
    color: #64748b;
    margin: 3px 0 0;
}

/* ── Satisfaction Heatmap (Likert Scale) ── */
.heatmap-rows-wrap {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.heatmap-row {
    display: flex;
    align-items: center;
    gap: 14px;
}
.heatmap-row-info {
    width: 210px;
    flex-shrink: 0;
}
.item-title {
    font-size: 12px;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.item-meta {
    font-size: 10.5px;
    font-weight: 500;
    color: #94a3b8;
    margin-top: 1px;
}
.heatmap-bar-track {
    flex: 1;
    height: 20px;
    background: #f1f5f9;
    border-radius: 6px;
    overflow: hidden;
    display: flex;
}
.bar-segment {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9.5px;
    font-weight: 700;
    color: #ffffff;
    transition: all 0.3s ease;
    cursor: pointer;
}
.bar-segment:hover {
    filter: brightness(1.1);
}
.seg-5 { background: #064e3b; }
.seg-4 { background: #10b981; }
.seg-3 { background: #cbd5e1; color: #475569 !important; }
.seg-2 { background: #f97316; }
.seg-1 { background: #dc2626; }

.empty-bar-segment {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10.5px;
    color: #94a3b8;
}
.heatmap-legend-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-top: 22px;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
}
.legend-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 600;
    color: #475569;
}
.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}
.heatmap-footer-note {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 14px;
    line-height: 1.4;
}

/* ── Side Column: Chart 5 & Chart 3 ── */
.analytics-side-column {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.primary-reasons-bars-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.reason-ranking-row {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.reason-name-text {
    font-size: 12px;
    font-weight: 500;
    color: #334155;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.reason-progress-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.reason-pill-track {
    flex: 1;
    height: 14px;
    background: #f1f5f9;
    border-radius: 9999px;
    overflow: hidden;
}
.reason-pill-bar {
    height: 100%;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    border-radius: 9999px;
    box-shadow: 0 1px 4px rgba(16, 185, 129, 0.2);
}
.reason-pct-value {
    width: 36px;
    font-size: 11.5px;
    font-weight: 600;
    color: #94a3b8;
    text-align: right;
}
.reason-pct-value.active {
    color: #0f172a;
    font-weight: 700;
}

/* Donut Chart */
.donut-chart-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 10px;
}
.donut-svg-wrapper {
    position: relative;
    width: 150px;
    height: 150px;
    margin-bottom: 20px;
}
.donut-svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}
.donut-center-info {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}
.donut-total-count {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}
.donut-total-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #94a3b8;
    margin-top: 4px;
}
.donut-factors-legend {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.donut-legend-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 11.5px;
}
.legend-left {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow: hidden;
    padding-right: 8px;
}
.factor-color-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}
.factor-title {
    color: #475569;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.factor-pct-tag {
    font-weight: 700;
    color: #0f172a;
    flex-shrink: 0;
}

/* Liked Most Aspects */
.liked-aspects-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.liked-row {
    display: flex;
    align-items: center;
    gap: 10px;
}
.liked-label {
    width: 150px;
    font-size: 11.5px;
    color: #334155;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex-shrink: 0;
}
.liked-bar-wrap {
    flex: 1;
    height: 10px;
    background: #f1f5f9;
    border-radius: 9999px;
    overflow: hidden;
}
.liked-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #8C0026 0%, #B30031 100%);
    border-radius: 9999px;
}
.liked-count-badge {
    font-size: 11px;
    font-weight: 700;
    color: #8C0026;
    width: 60px;
    text-align: right;
    flex-shrink: 0;
}

/* Submissions Table Card */
.submissions-table-card {
    padding: 0;
    overflow: hidden;
}
.submissions-table-card .card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
}

/* Responsive */
@media (max-width: 1280px) {
    .analytics-kpi-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .analytics-content-grid {
        grid-template-columns: 1fr;
    }
    .feedback-questions-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 1024px) {
    .single-line-filter-form {
        flex-wrap: wrap;
    }
}
@media (max-width: 768px) {
    .analytics-kpi-grid {
        grid-template-columns: 1fr;
    }
    .single-line-filter-form {
        flex-direction: column;
        align-items: stretch;
    }
    .single-line-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>

<script>
function toggleSearchDropdown(type, event) {
    if (event) event.stopPropagation();
    const companyMenu = document.getElementById('companyDropdownMenu');
    const employeeMenu = document.getElementById('employeeDropdownMenu');
    const targetMenu = type === 'company' ? companyMenu : employeeMenu;
    const otherMenu = type === 'company' ? employeeMenu : companyMenu;
    const targetBtn = document.getElementById(type + 'TriggerBtn');
    const otherBtn = document.getElementById((type === 'company' ? 'employee' : 'company') + 'TriggerBtn');

    if (otherMenu) {
        otherMenu.classList.remove('is-open');
        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
    }

    if (targetMenu) {
        const isOpen = targetMenu.classList.toggle('is-open');
        targetBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        if (isOpen) {
            const input = document.getElementById(type + 'SearchInput');
            if (input) {
                input.value = '';
                filterSearchOptions(type, '');
                setTimeout(() => input.focus(), 60);
            }
        }
    }
}

function filterSearchOptions(type, query) {
    const list = document.getElementById(type + 'OptionsList');
    const noResults = document.getElementById(type + 'NoResults');
    if (!list) return;

    const q = (query || '').toLowerCase().trim();
    const items = list.querySelectorAll('.dropdown-item-option');
    let visibleCount = 0;

    items.forEach(item => {
        const dataSearch = item.getAttribute('data-search') || '';
        const titleText = (item.querySelector('.item-title')?.textContent || '').toLowerCase();
        const subtitleText = (item.querySelector('.item-subtitle')?.textContent || '').toLowerCase();

        // "All..." option is always visible if query is empty or matches "all"
        if (!item.hasAttribute('data-search')) {
            if (q === '' || titleText.includes(q)) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
            return;
        }

        if (dataSearch.includes(q) || titleText.includes(q) || subtitleText.includes(q)) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    if (noResults) {
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

function selectSearchOption(type, value, label) {
    const hiddenInput = document.getElementById(type === 'company' ? 'hiddenCompanyId' : 'hiddenSurveyId');
    if (hiddenInput) {
        hiddenInput.value = value;
    }
    const triggerText = document.getElementById(type + 'TriggerText');
    if (triggerText) {
        triggerText.textContent = label;
    }
    const menu = document.getElementById(type + 'DropdownMenu');
    if (menu) menu.classList.remove('is-open');

    // Auto submit form
    document.getElementById('analyticsFiltersForm').submit();
}

function clearSelection(type, event) {
    if (event) event.stopPropagation();
    selectSearchOption(type, '', type === 'company' ? 'All Companies' : 'All Departing Employees');
}

function clearSearchInput(type, event) {
    if (event) event.stopPropagation();
    const input = document.getElementById(type + 'SearchInput');
    if (input) {
        input.value = '';
        filterSearchOptions(type, '');
        input.focus();
    }
}

// Close when clicking outside
document.addEventListener('click', function(e) {
    const compWrap = document.getElementById('companyDropdownWrap');
    const empWrap = document.getElementById('employeeDropdownWrap');
    if (compWrap && !compWrap.contains(e.target)) {
        const menu = document.getElementById('companyDropdownMenu');
        const btn = document.getElementById('companyTriggerBtn');
        if (menu) menu.classList.remove('is-open');
        if (btn) btn.setAttribute('aria-expanded', 'false');
    }
    if (empWrap && !empWrap.contains(e.target)) {
        const menu = document.getElementById('employeeDropdownMenu');
        const btn = document.getElementById('employeeTriggerBtn');
        if (menu) menu.classList.remove('is-open');
        if (btn) btn.setAttribute('aria-expanded', 'false');
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const compMenu = document.getElementById('companyDropdownMenu');
        const empMenu = document.getElementById('employeeDropdownMenu');
        const compBtn = document.getElementById('companyTriggerBtn');
        const empBtn = document.getElementById('employeeTriggerBtn');
        if (compMenu) compMenu.classList.remove('is-open');
        if (empMenu) empMenu.classList.remove('is-open');
        if (compBtn) compBtn.setAttribute('aria-expanded', 'false');
        if (empBtn) empBtn.setAttribute('aria-expanded', 'false');
    }
});
</script>

@endsection
