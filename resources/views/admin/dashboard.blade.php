@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Exit Analytics Dashboard')

@section('content')

<!-- Header Filter Banner -->
<div class="analytics-header-card">
    <div class="header-left">
        <div class="header-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
        </div>
        <div>
            <h1 class="header-title">Exit Analytics Dashboard</h1>
            <p class="header-subtitle">Real-time tracking of employee departures, satisfaction metrics, and retention risks.</p>
        </div>
    </div>

    <div class="header-right">
        <form method="GET" action="{{ route('admin.dashboard') }}" id="analyticsFilterForm" class="filter-controls-row">
            <!-- Company Filter -->
            <div class="select-wrapper">
                <select name="company_id" onchange="this.form.submit()" class="filter-dropdown" aria-label="Filter by Company">
                    <option value="">All Companies</option>
                    @foreach($companies as $comp)
                        <option value="{{ $comp->id }}" {{ (string)$selectedCompany === (string)$comp->id ? 'selected' : '' }}>
                            {{ $comp->name }}
                        </option>
                    @endforeach
                </select>
                <svg class="dropdown-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>

            <!-- Year Filter -->
            <div class="select-wrapper">
                <select name="year" onchange="this.form.submit()" class="filter-dropdown" aria-label="Filter by Year">
                    <option value="">All Years</option>
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ (string)$selectedYear === (string)$yr ? 'selected' : '' }}>
                            {{ $yr }}
                        </option>
                    @endforeach
                </select>
                <svg class="dropdown-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>

            <!-- Month Filter -->
            <div class="select-wrapper">
                <select name="month" onchange="this.form.submit()" class="filter-dropdown" aria-label="Filter by Month">
                    <option value="">All Months</option>
                    @foreach($months as $num => $mName)
                        <option value="{{ $num }}" {{ (string)$selectedMonth === (string)$num ? 'selected' : '' }}>
                            {{ $mName }}
                        </option>
                    @endforeach
                </select>
                <svg class="dropdown-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>

            <!-- Reset Button -->
            <a href="{{ route('admin.dashboard') }}" class="btn-reset-filters" title="Reset all filters">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                    <path d="M3 3v5h5"></path>
                </svg>
                Reset Filters
            </a>
        </form>

        <div class="records-counter-text">
            Showing all {{ $totalExits }} {{ Str::plural('record', $totalExits) }}
        </div>
    </div>
</div>

<!-- 4 Top KPI Cards -->
<div class="kpi-banner-grid">
    <!-- Card 1: HEADCOUNT & EXITS -->
    <div class="analytics-kpi-card card-dark">
        <div class="kpi-top">
            <span class="kpi-heading-tag">HEADCOUNT & EXITS</span>
            <div class="kpi-icon-pill">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
            </div>
        </div>
        <div class="headcount-split-row">
            <div class="split-col">
                <div class="split-number">{{ $totalExits }}</div>
                <div class="split-label">TOTAL EXITS</div>
            </div>
            <div class="split-divider"></div>
            <div class="split-col">
                <div class="split-number">{{ number_format($totalHeadcount) }}</div>
                <div class="split-label">TOTAL HEADCOUNT</div>
            </div>
        </div>
        <div class="kpi-subtext">
            {{ $selectedCompanyName ? 'For ' . $selectedCompanyName : 'Across all registered companies' }}
        </div>
    </div>

    <!-- Card 2: RECOMMENDATION RATE -->
    <div class="analytics-kpi-card card-burgundy">
        <div class="kpi-top">
            <span class="kpi-heading-tag">RECOMMENDATION RATE</span>
            <div class="kpi-icon-pill">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                </svg>
            </div>
        </div>
        <div class="kpi-single-value">{{ $recommendationRate }}%</div>
        <div class="kpi-subtext-white">(Yes)</div>
    </div>

    <!-- Card 3: RE-HIRE ELIGIBILITY -->
    <div class="analytics-kpi-card card-wine">
        <div class="kpi-top">
            <span class="kpi-heading-tag">RE-HIRE ELIGIBILITY</span>
            <div class="kpi-icon-pill">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"></polyline>
                    <polyline points="1 20 1 14 7 14"></polyline>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                </svg>
            </div>
        </div>
        <div class="kpi-single-value">{{ $rehireRate }}%</div>
        <div class="kpi-subtext-white">(Open Reapply)</div>
    </div>

    <!-- Card 4: AVG. SATISFACTION -->
    <div class="analytics-kpi-card card-navy">
        <div class="kpi-top">
            <span class="kpi-heading-tag">AVG. SATISFACTION</span>
            <div class="kpi-icon-pill">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </div>
        </div>
        <div class="kpi-single-value">{{ $avgSatisfaction ? number_format($avgSatisfaction, 1) : '—' }}</div>
        <div class="kpi-subtext-white">Rating</div>
    </div>
</div>

<!-- Two Main Chart Cards -->
<div class="charts-double-grid">
    <!-- CHART 1: Resignations by Subsidiary / Company -->
    <div class="chart-card">
        <div>
            <div class="chart-tag-label">CHART 1</div>
            <h2 class="chart-main-title">Resignations by Subsidiary / Company</h2>
            <p class="chart-subtitle">Departure volume per group company from completed exit surveys.</p>
        </div>

        <div class="chart1-plot-area">
            @php
                $barScaleMax = max($maxCompanyExits, 1);
            @endphp
            <div class="bars-container">
                @forelse($chartCompanies as $comp)
                    @php
                        $barPct = $comp['count'] > 0 ? round(($comp['count'] / $barScaleMax) * 100) : 0;
                        $displayH = $comp['count'] > 0 ? max(18, round(($comp['count'] / $barScaleMax) * 125)) : 5;
                    @endphp
                    <div class="vertical-bar-column">
                        <div class="bar-count-badge {{ $comp['count'] > 0 ? 'active' : '' }}">
                            {{ $comp['count'] }}
                        </div>
                        <div class="bar-pillar-wrap" style="height: 130px;">
                            <div class="bar-pillar {{ $comp['count'] > 0 ? 'filled' : 'zero' }}"
                                 style="height: {{ $displayH }}px;"
                                 title="{{ $comp['name'] }}: {{ $comp['count'] }} exits">
                            </div>
                        </div>
                        <div class="bar-company-label" title="{{ $comp['name'] }}">
                            {{ Str::limit($comp['name'], 11) }}
                        </div>
                    </div>
                @empty
                    <div class="no-chart-data">No companies registered</div>
                @endforelse
            </div>
        </div>

        <div class="chart-footer-note">
            Axis shows every registered company. Based on {{ $totalExits }} completed submissions.
        </div>
    </div>

    <!-- CHART 2: Primary Reason Frequency -->
    <div class="chart-card">
        <div>
            <div class="chart-tag-label">CHART 2</div>
            <h2 class="chart-main-title">Primary Reason Frequency</h2>
            <p class="chart-subtitle">Most common reasons selected for leaving, ranked highest to lowest.</p>
        </div>

        <div class="chart2-plot-area">
            @php
                $rScaleMax = max($maxReasonCount, 1);
                // Compute 4 scale steps (e.g. 0, 5, 10, 15)
                if ($rScaleMax <= 4) {
                    $tickSteps = [0, 2, 4];
                } elseif ($rScaleMax <= 12) {
                    $tickSteps = [0, 4, 8, 12];
                } else {
                    $step = ceil($rScaleMax / 3);
                    $tickSteps = [0, (int)$step, (int)($step * 2), (int)($step * 3)];
                }
                $highestTick = end($tickSteps);
            @endphp

            <!-- Scale Ticks Header -->
            <div class="scale-ruler-header">
                <div class="scale-label-spacer"></div>
                <div class="scale-ticks-track">
                    @foreach($tickSteps as $tick)
                        <div class="scale-tick-item" style="left: {{ $highestTick > 0 ? round(($tick / $highestTick) * 100) : 0 }}%;">
                            <span class="tick-number">{{ $tick }}</span>
                            <span class="tick-line"></span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Ranked Horizontal Bars -->
            <div class="horizontal-bars-list">
                @forelse($chartReasons as $item)
                    @php
                        $relPct = $highestTick > 0 ? round(($item['count'] / $highestTick) * 100) : 0;
                    @endphp
                    <div class="reason-bar-row">
                        <div class="reason-label-text" title="{{ $item['label'] }}">
                            {{ $item['label'] }}
                        </div>
                        <div class="reason-track-container">
                            <div class="reason-track-background">
                                @if($item['count'] > 0)
                                    <div class="reason-bar-fill" style="width: {{ $relPct }}%;"></div>
                                @endif
                            </div>
                            <span class="reason-count-text {{ $item['count'] > 0 ? 'has-count' : 'zero-count' }}">
                                {{ $item['count'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="no-chart-data">No reason data recorded</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Submissions Table -->
<div class="card recent-submissions-card">
    <div class="card-header">
        <span class="card-title">🕒 Recent Completed Submissions</span>
        <a href="{{ route('admin.surveys.index') }}" class="btn btn-ghost btn-sm">View All →</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Company</th>
                    <th>Designation</th>
                    <th>Dept</th>
                    <th>Satisfaction</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSubmissions as $survey)
                    <tr>
                        <td>
                            <div style="font-weight:600;color:var(--text)">{{ $survey->employee_name }}</div>
                            <div style="font-size:11.5px;color:var(--text-muted)">{{ $survey->employee_id }}</div>
                        </td>
                        <td><span class="badge badge-info">{{ $survey->company->name }}</span></td>
                        <td style="font-size:13px">{{ $survey->designation }}</td>
                        <td style="font-size:13px;color:var(--text-muted)">{{ $survey->department }}</td>
                        <td>
                            @php $rating = $survey->overallSatisfaction(); @endphp
                            @if($rating)
                                <span style="font-weight:700;color:var(--accent)">⭐ {{ $rating }}</span>
                            @else
                                <span style="color:var(--text-muted)">—</span>
                            @endif
                        </td>
                        <td style="font-size:12.5px;color:var(--text-muted)">{{ $survey->submitted_at?->diffForHumans() ?? $survey->created_at?->diffForHumans() }}</td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('admin.surveys.show', $survey) }}" class="btn btn-ghost btn-xs">👁 View</a>
                                <a href="{{ route('admin.surveys.pdf', $survey) }}" class="btn btn-ghost btn-xs">📥 PDF</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px">
                            No completed submissions found matching the selected filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
/* ── Analytics Header Card ── */
.analytics-header-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
}
.header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}
.header-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #FFF0F3;
    border: 1px solid #FFE4E8;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8C0026;
    flex-shrink: 0;
}
.header-title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.3px;
    margin: 0;
    line-height: 1.25;
}
.header-subtitle {
    font-size: 13px;
    color: #64748b;
    margin-top: 3px;
    margin-bottom: 0;
}
.header-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
}
.filter-controls-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.select-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
}
.filter-dropdown {
    height: 38px;
    padding: 0 32px 0 14px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    font-size: 13px;
    font-weight: 500;
    color: #334155;
    background: #ffffff;
    appearance: none;
    cursor: pointer;
    transition: all 0.15s ease;
}
.filter-dropdown:focus {
    outline: none;
    border-color: #8C0026;
    box-shadow: 0 0 0 3px rgba(140, 0, 38, 0.12);
}
.dropdown-chevron {
    position: absolute;
    right: 12px;
    color: #64748b;
    pointer-events: none;
}
.btn-reset-filters {
    height: 38px;
    padding: 0 14px;
    border-radius: 10px;
    border: 1px solid #fecdd3;
    background: #ffffff;
    color: #8C0026;
    font-size: 12.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease;
}
.btn-reset-filters:hover {
    background: #FFF0F3;
    border-color: #fda4af;
    color: #66001C;
}
.records-counter-text {
    font-size: 11.5px;
    font-weight: 500;
    color: #64748b;
    text-align: right;
}

/* ── 4 Top KPI Cards Grid ── */
.kpi-banner-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 26px;
}
.analytics-kpi-card {
    border-radius: 16px;
    padding: 22px 24px;
    color: #ffffff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 154px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.analytics-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}
.card-dark {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.card-burgundy {
    background: linear-gradient(135deg, #8C0026 0%, #600018 100%);
    border: 1px solid rgba(140, 0, 38, 0.3);
}
.card-wine {
    background: linear-gradient(135deg, #7A0A26 0%, #4D0014 100%);
    border: 1px solid rgba(122, 10, 38, 0.3);
}
.card-navy {
    background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%);
    border: 1px solid rgba(30, 58, 138, 0.3);
}
.kpi-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.kpi-heading-tag {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: rgba(255, 255, 255, 0.75);
    text-transform: uppercase;
}
.kpi-icon-pill {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    flex-shrink: 0;
}
.headcount-split-row {
    display: flex;
    align-items: center;
    margin: 12px 0 6px;
}
.split-col {
    flex: 1;
}
.split-number {
    font-size: 28px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1;
}
.split-label {
    font-size: 10px;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.6px;
    margin-top: 5px;
    text-transform: uppercase;
}
.split-divider {
    width: 1px;
    height: 38px;
    background: rgba(255, 255, 255, 0.16);
    margin: 0 16px;
}
.kpi-single-value {
    font-size: 36px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1;
    margin: 12px 0 4px;
}
.kpi-subtext {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 4px;
}
.kpi-subtext-white {
    font-size: 12.5px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.85);
}

/* ── Two Main Chart Cards ── */
.charts-double-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 28px;
}
.chart-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 24px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.chart-tag-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #94a3b8;
    text-transform: uppercase;
}
.chart-main-title {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin: 3px 0 0;
    line-height: 1.3;
}
.chart-subtitle {
    font-size: 12px;
    color: #64748b;
    margin: 3px 0 0;
}

/* Chart 1: Subsidiary Bar Chart */
.chart1-plot-area {
    margin-top: 24px;
    height: 200px;
    display: flex;
    align-items: flex-end;
}
.bars-container {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    gap: 12px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 4px;
}
.vertical-bar-column {
    flex: 1;
    min-width: 44px;
    max-width: 72px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    height: 100%;
}
.bar-count-badge {
    font-size: 12px;
    font-weight: 700;
    color: #94a3b8;
    margin-bottom: 6px;
    transition: color 0.2s ease;
}
.bar-count-badge.active {
    color: #8C0026;
    font-size: 13px;
}
.bar-pillar-wrap {
    width: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}
.bar-pillar {
    width: 100%;
    max-width: 44px;
    transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.bar-pillar.filled {
    background: linear-gradient(180deg, #B30031 0%, #8C0026 100%);
    border-radius: 8px 8px 2px 2px;
    box-shadow: 0 4px 12px rgba(140, 0, 38, 0.2);
}
.bar-pillar.zero {
    background: #f1f5f9;
    height: 5px !important;
    border-radius: 3px;
}
.bar-company-label {
    font-size: 11px;
    font-weight: 500;
    color: #475569;
    margin-top: 8px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}
.chart-footer-note {
    font-size: 11.5px;
    color: #94a3b8;
    margin-top: 16px;
}

/* Chart 2: Primary Reasons Horizontal */
.chart2-plot-area {
    margin-top: 18px;
}
.scale-ruler-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}
.scale-label-spacer {
    width: 170px;
    flex-shrink: 0;
}
.scale-ticks-track {
    flex: 1;
    position: relative;
    height: 20px;
    margin-right: 32px;
}
.scale-tick-item {
    position: absolute;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
}
.tick-number {
    font-size: 10.5px;
    font-weight: 600;
    color: #94a3b8;
}
.tick-line {
    width: 1px;
    height: 5px;
    background: #cbd5e1;
    margin-top: 2px;
}
.horizontal-bars-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.reason-bar-row {
    display: flex;
    align-items: center;
    gap: 12px;
}
.reason-label-text {
    width: 170px;
    font-size: 12px;
    font-weight: 500;
    color: #334155;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex-shrink: 0;
}
.reason-track-container {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
}
.reason-track-background {
    flex: 1;
    height: 18px;
    background: #f1f5f9;
    border-radius: 9999px;
    overflow: hidden;
    position: relative;
}
.reason-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #600018 0%, #8C0026 60%, #B30031 100%);
    border-radius: 9999px;
    box-shadow: 0 2px 8px rgba(140, 0, 38, 0.25);
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.reason-count-text {
    width: 22px;
    font-size: 12px;
    font-weight: 700;
    text-align: left;
    flex-shrink: 0;
}
.reason-count-text.has-count {
    color: #0f172a;
}
.reason-count-text.zero-count {
    color: #94a3b8;
    font-weight: 500;
}
.no-chart-data {
    text-align: center;
    color: #94a3b8;
    font-size: 12.5px;
    padding: 30px 0;
    width: 100%;
}

/* ── Responsive adjustments ── */
@media (max-width: 1200px) {
    .kpi-banner-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 900px) {
    .charts-double-grid {
        grid-template-columns: 1fr;
    }
    .analytics-header-card {
        flex-direction: column;
        align-items: flex-start;
    }
    .header-right {
        align-items: flex-start;
        width: 100%;
    }
    .records-counter-text {
        text-align: left;
    }
}
@media (max-width: 600px) {
    .kpi-banner-grid {
        grid-template-columns: 1fr;
    }
    .filter-controls-row {
        width: 100%;
    }
    .filter-dropdown {
        width: 100%;
    }
    .select-wrapper {
        width: 100%;
    }
}
</style>

@endsection
