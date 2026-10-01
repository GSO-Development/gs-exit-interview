<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Exit Interview Form</title>
<style>
    @page {
        margin: 8mm 12mm 14mm 12mm;
        size: a4 portrait;
    }
    * {
        box-sizing: border-box;
    }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 7.5pt;
        line-height: 1.15;
        color: #000000;
        margin: 0;
        padding: 0;
    }

    /* Fixed Bottom Footer Anchored at Page Bottom on Every Page */
    .fixed-page-footer {
        position: fixed;
        bottom: -10mm;
        left: 0;
        right: 0;
        height: 16px;
        font-size: 7pt;
        color: #333333;
    }
    .page-number:before {
        content: "Page " counter(page) " of 2";
    }

    /* Top Header Box */
    .header-box {
        width: 100%;
        border: 1px solid #8C0026;
        border-collapse: collapse;
        margin-bottom: 14px;
    }
    .header-box td {
        padding: 4px 8px;
        vertical-align: middle;
    }
    .header-logo-cell {
        width: 130px;
        text-align: center;
        border-right: 1px solid #8C0026;
    }
    .header-logo-img {
        max-height: 38px;
        max-width: 120px;
        display: block;
        margin: 0 auto;
    }
    .header-title-cell {
        text-align: center;
        font-size: 13pt;
        font-weight: bold;
        color: #000000;
        letter-spacing: 0.5px;
    }

    /* Team Member Details */
    .details-heading {
        font-size: 8.5pt;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }
    .details-table td {
        border: 1px solid #000000;
        padding: 3.5px 6px;
        font-size: 7.8pt;
        vertical-align: middle;
    }
    .details-table .lbl {
        width: 20%;
        font-weight: bold;
        color: #000000;
    }
    .details-table .val {
        width: 30%;
        color: #000000;
    }

    /* Section Instructions */
    .section-instruction {
        font-size: 8pt;
        font-weight: bold;
        margin-top: 0;
        margin-bottom: 6px;
    }

    /* Rating Table */
    .rating-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .rating-table th {
        border: 1px solid #000000;
        padding: 3px 2px;
        font-size: 6.8pt;
        font-weight: bold;
        text-align: center;
        background: #ffffff;
        vertical-align: middle;
    }
    .rating-table td {
        border: 1px solid #000000;
        padding: 3.4px 4px;
        font-size: 7.2pt;
        vertical-align: middle;
    }
    .rating-table td.col-no {
        width: 26px;
        text-align: center;
    }
    .rating-table td.col-area {
        width: 54%;
    }
    .rating-table td.col-rate {
        width: 8%;
        text-align: center;
        font-size: 9.5pt;
        font-weight: bold;
        color: #000000;
    }

    /* Question 2 Table - Compact width matching Image 1 */
    .q2-table {
        width: 65%;
        border-collapse: collapse;
        margin-bottom: 0px;
    }
    .q2-table td {
        border: 1px solid #000000;
        padding: 3.2px 4px;
        font-size: 7.3pt;
        vertical-align: middle;
        white-space: nowrap;
    }
    .q2-table .q-no {
        width: 26px;
        text-align: center;
        white-space: nowrap;
    }
    .q2-table .q-check {
        width: 30px;
        text-align: center;
        font-size: 9pt;
        font-weight: bold;
        white-space: nowrap;
    }

    .page-break {
        page-break-after: always;
        break-after: page;
    }

    /* Page 2 Specifics - Questions 3 & 4 Tables */
    .q-table {
        width: 70%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }
    .q-table td {
        border: 1px solid #000000;
        padding: 2.8px 5px;
        font-size: 7.3pt;
        vertical-align: middle;
        white-space: nowrap;
    }
    .q-table .q-no {
        width: 26px;
        text-align: center;
        white-space: nowrap;
    }
    .q-table .q-text {
        white-space: nowrap;
        padding-left: 6px;
        padding-right: 12px;
    }
    .q-table .q-check {
        width: 32px;
        text-align: center;
        font-size: 9pt;
        font-weight: bold;
        white-space: nowrap;
    }

    .text-q-label {
        font-weight: bold;
        font-size: 7.8pt;
        margin-top: 0;
        margin-bottom: 3px;
    }
    .text-q-line {
        border-bottom: 1px solid #666666;
        min-height: 22px;
        padding: 2px 2px;
        font-size: 7.4pt;
        color: #000000;
        margin-bottom: 15px;
    }

    .yes-no-row {
        width: 100%;
        margin-top: 0;
        margin-bottom: 15px;
    }
    .yes-no-row td {
        font-size: 7.8pt;
        vertical-align: middle;
    }
    .yes-no-box {
        display: inline-block;
        width: 18px;
        height: 16px;
        border: 1px solid #000000;
        text-align: center;
        line-height: 14px;
        font-weight: bold;
        font-size: 9pt;
        margin-left: 5px;
    }

    /* Signatures */
    .team-member-sig-block {
        width: 100%;
        margin-top: 0;
        margin-bottom: 16px;
    }
    .team-member-sig-block td {
        font-size: 7.8pt;
        vertical-align: bottom;
    }
    .sig-caption {
        font-size: 7.2pt;
        color: #333333;
    }

    /* Digital Seal Stamp */
    .digital-seal-badge {
        display: inline-block;
        border: 1.5px solid #8C0026;
        background: #fff8f9;
        padding: 3px 10px;
        border-radius: 4px;
        text-align: center;
    }
    .digital-seal-title {
        font-size: 6.8pt;
        font-weight: bold;
        color: #8C0026;
        letter-spacing: 0.5px;
    }
    .digital-seal-name {
        font-size: 8pt;
        font-weight: bold;
        color: #000000;
        margin: 1px 0;
    }
    .digital-seal-sub {
        font-size: 6pt;
        color: #666666;
    }

    .reviewed-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 4px;
        margin-bottom: 0px;
    }
    .reviewed-table th {
        border: 1px solid #000000;
        padding: 3px 4px;
        font-size: 7.2pt;
        font-weight: bold;
        text-align: left;
        background: #ffffff;
    }
    .reviewed-table td {
        border: 1px solid #000000;
        padding: 3px 4px;
        font-size: 7.2pt;
        height: 50px;
        vertical-align: top;
    }
</style>
</head>
<body>

@php
    $response = $survey->response;
    $ratings = (array) ($response->area_ratings ?? []);
    $otherReasonsSaved = (array) ($response->other_reasons_to_leave ?? $response->resignation_factors ?? []);
    $likedMostSaved = (array) ($response->liked_most_aspects ?? []);

    $formFilledDate = $survey->submitted_at ?? $response?->team_member_signed_date ?? $survey->created_at ?? now();
    $issuedOnFormatted = $formFilledDate->format('j') . '<sup>' . $formFilledDate->format('S') . '</sup> ' . $formFilledDate->format('M Y');

    $pdfGeneratedDate = now();
    $updatedOnFormatted = $pdfGeneratedDate->format('j') . '<sup>' . $pdfGeneratedDate->format('S') . '</sup> ' . $pdfGeneratedDate->format('M Y');
@endphp

<!-- Fixed Bottom Footer (Anchored at very bottom of Page 1 & Page 2) -->
<div class="fixed-page-footer">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: left;">Group HR/Exit/ Issued on: {!! $issuedOnFormatted !!}/Updated on: {!! $updatedOnFormatted !!}</td>
            <td style="text-align: right;"><span class="page-number"></span></td>
        </tr>
    </table>
</div>

@php

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
        1 => 'Joining a local company for a better position/ salary',
        2 => 'Going oversees:',
        3 => 'Shifting to another industry/career',
        4 => 'Personal health related reasons',
        5 => 'Family or personal commitments/reasons',
        6 => 'Distance to workplace/ Relocation',
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

    $logoPath = public_path('George_Steuart_Group_Logo.png');
    $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';

    $selectedMain = $response->main_reason_to_leave ?? $response->primary_resignation_reason ?? '';
    $overseasSub = $response->overseas_sub_option ?? '';

    $isOpt1 = (strcasecmp(trim($selectedMain), trim($mainReasons[1])) === 0);
    $isOpt2 = (str_contains(strtolower($mainReasons[2]), 'oversees') && str_contains(strtolower($selectedMain), 'oversea')) ||
              (strcasecmp(trim($selectedMain), trim($mainReasons[2])) === 0);
    $isOpt3 = (strcasecmp(trim($selectedMain), trim($mainReasons[3])) === 0);
    $isOpt4 = (strcasecmp(trim($selectedMain), trim($mainReasons[4])) === 0);
    $isOpt5 = (strcasecmp(trim($selectedMain), trim($mainReasons[5])) === 0);
    $isOpt6 = (strcasecmp(trim($selectedMain), trim($mainReasons[6])) === 0);

    $subJob = $isOpt2 && (strcasecmp(trim($overseasSub), 'Overseas job') === 0 || str_contains(strtolower($selectedMain), 'overseas job'));
    $subStudies = $isOpt2 && (strcasecmp(trim($overseasSub), 'Higher studies') === 0 || str_contains(strtolower($selectedMain), 'higher studies'));
    $subMigration = $isOpt2 && (strcasecmp(trim($overseasSub), 'Migration') === 0 || str_contains(strtolower($selectedMain), 'migration'));
@endphp

<!-- ================= PAGE 1 ================= -->

<!-- Top Header Box -->
<table class="header-box">
    <tr>
        <td class="header-logo-cell">
            @if($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" class="header-logo-img" alt="Logo">
            @endif
        </td>
        <td class="header-title-cell">
            Exit Interview Form
        </td>
    </tr>
</table>

<!-- Team Member Details -->
<div class="details-heading">Team Member Details:</div>
<table class="details-table">
    <tr>
        <td class="lbl">Name:</td>
        <td class="val">{{ $survey->employee_name }}</td>
        <td class="lbl">Team Member No:</td>
        <td class="val">{{ $survey->employee_id ?? '' }}</td>
    </tr>
    <tr>
        <td class="lbl">Company:</td>
        <td class="val">{{ $survey->company->name ?? '' }}</td>
        <td class="lbl">Designation:</td>
        <td class="val">{{ $survey->designation }}</td>
    </tr>
    <tr>
        <td class="lbl">Section/ Division/<br>Department:</td>
        <td class="val">{{ $survey->section_division ?? $survey->department }}</td>
        <td class="lbl">Immediate<br>Supervisor’s Name:</td>
        <td class="val">{{ $survey->supervisor_name ?? $survey->reporting_manager ?? '' }}</td>
    </tr>
    <tr>
        <td class="lbl">Date of Joining:</td>
        <td class="val">{{ $survey->date_joined ? $survey->date_joined->format('Y-m-d') : '' }}</td>
        <td class="lbl">Date of Resignation:</td>
        <td class="val">{{ $survey->date_of_resignation ? $survey->date_of_resignation->format('Y-m-d') : ($survey->last_working_date ? $survey->last_working_date->format('Y-m-d') : '') }}</td>
    </tr>
</table>

<!-- Section 1 -->
<div class="section-instruction">
    1. &nbsp;Please complete the following, based on your experience with the company, by marking ‘✓’ in the given box:
</div>

<table class="rating-table">
    <thead>
        <tr>
            <th style="width: 26px;">No</th>
            <th style="text-align: left; padding-left: 5px;">Area</th>
            <th style="width: 50px;">Highly<br>Satisfied</th>
            <th style="width: 46px;">Satisfied</th>
            <th style="width: 44px;">Average</th>
            <th style="width: 50px;">Dissatisfied</th>
            <th style="width: 54px;">Highly<br>Dissatisfied</th>
        </tr>
    </thead>
    <tbody>
        @foreach($areas as $num => $areaTitle)
            @php
                $val = (int) ($ratings[$num] ?? $ratings[(string)$num] ?? 0);
            @endphp
            <tr>
                <td class="col-no">1.{{ $num }}</td>
                <td class="col-area">{{ $areaTitle }}</td>
                <td class="col-rate">{{ $val === 5 ? '✓' : '' }}</td>
                <td class="col-rate">{{ $val === 4 ? '✓' : '' }}</td>
                <td class="col-rate">{{ $val === 3 ? '✓' : '' }}</td>
                <td class="col-rate">{{ $val === 2 ? '✓' : '' }}</td>
                <td class="col-rate">{{ $val === 1 ? '✓' : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Section 2 -->
<div class="section-instruction">
    2. &nbsp;What is your main reason to leave the company? (<u>Select one option</u> and mark with ‘✓’)
</div>

<table class="q2-table">
    <tr>
        <td class="q-no">1</td>
        <td colspan="5">Joining a local company for a better position/ salary</td>
        <td class="q-check">{{ $isOpt1 ? '✓' : '' }}</td>
    </tr>
    <tr>
        <td rowspan="2" class="q-no" style="vertical-align: middle;">2</td>
        <td colspan="6" style="border-bottom: none; padding-top: 3px; padding-bottom: 1px;">Going oversees:</td>
    </tr>
    <tr>
        <td style="border-top: none;">Overseas job</td>
        <td class="q-check">{{ $subJob ? '✓' : '' }}</td>
        <td style="border-top: none;">Higher studies</td>
        <td class="q-check">{{ $subStudies ? '✓' : '' }}</td>
        <td style="border-top: none;">Migration</td>
        <td class="q-check">{{ $subMigration ? '✓' : '' }}</td>
    </tr>
    <tr>
        <td class="q-no">3</td>
        <td colspan="5">Shifting to another industry/career</td>
        <td class="q-check">{{ $isOpt3 ? '✓' : '' }}</td>
    </tr>
    <tr>
        <td class="q-no">4</td>
        <td colspan="5">Personal health related reasons</td>
        <td class="q-check">{{ $isOpt4 ? '✓' : '' }}</td>
    </tr>
    <tr>
        <td class="q-no">5</td>
        <td colspan="5">Family or personal commitments/reasons</td>
        <td class="q-check">{{ $isOpt5 ? '✓' : '' }}</td>
    </tr>
    <tr>
        <td class="q-no">6</td>
        <td colspan="5">Distance to workplace/ Relocation</td>
        <td class="q-check">{{ $isOpt6 ? '✓' : '' }}</td>
    </tr>
</table>

<div class="page-break"></div>

<!-- ================= PAGE 2 ================= -->

<!-- Top Header Box (Page 2) -->
<table class="header-box">
    <tr>
        <td class="header-logo-cell">
            @if($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" class="header-logo-img" alt="Logo">
            @endif
        </td>
        <td class="header-title-cell">
            Exit Interview Form
        </td>
    </tr>
</table>

<!-- Section 3 -->
<div class="section-instruction">
    3. &nbsp;Any other reasons to leave the company? (<u>Select multiple options</u> and mark with ‘✓’ if applicable)
</div>

<table class="q-table">
    @foreach($otherReasons as $num => $label)
        @php
            $isOtherChecked = false;
            foreach ($otherReasonsSaved as $os) {
                if (strcasecmp(trim((string)$os), trim($label)) === 0) {
                    $isOtherChecked = true;
                    break;
                }
            }
        @endphp
        <tr>
            <td class="q-no">{{ $num }}</td>
            <td class="q-text">{{ $label }}</td>
            <td class="q-check">{{ $isOtherChecked ? '✓' : '' }}</td>
        </tr>
    @endforeach
</table>

<!-- Section 4 -->
<div class="section-instruction">
    4. &nbsp;What did you <u>like the most</u> about your work experience? (Select multiple options and mark with ‘✓’ as applicable)
</div>

<table class="q-table">
    @foreach($likedMost as $num => $label)
        @php
            $isLikedChecked = false;
            foreach ($likedMostSaved as $ls) {
                if (strcasecmp(trim((string)$ls), trim($label)) === 0) {
                    $isLikedChecked = true;
                    break;
                }
            }
        @endphp
        <tr>
            <td class="q-no">{{ $num }}</td>
            <td class="q-text">{{ $label }}</td>
            <td class="q-check">{{ $isLikedChecked ? '✓' : '' }}</td>
        </tr>
    @endforeach
</table>

<!-- Section 5 -->
<div class="text-q-label">5. &nbsp;In your own words, what did you enjoy most about working here?</div>
<div class="text-q-line">
    {{ $response->enjoyed_most ?? '' }}
</div>

<!-- Section 6 -->
<div class="text-q-label">6. &nbsp;What would have prevented your resignation:</div>
<div class="text-q-line">
    {{ $response->prevented_resignation ?? $response->resignation_elaboration ?? '' }}
</div>

<!-- Section 7 & 8 -->
@php
    $recVal = strtolower((string)($response->recommend_company ?? $response->would_recommend ?? ''));
    $reapVal = strtolower((string)($response->open_to_reapply ?? $response->would_return ?? ''));
@endphp

<table class="yes-no-row">
    <tr>
        <td style="width: 82%;">
            <strong>7. &nbsp;Would you recommend our company as a potential employer to others?</strong> (mark ‘Yes’ or ‘No’)
        </td>
        <td style="width: 18%; text-align: right;">
            Yes <span class="yes-no-box">{{ $recVal === 'yes' ? '✓' : '' }}</span>
            &nbsp;
            No <span class="yes-no-box">{{ $recVal === 'no' ? '✓' : '' }}</span>
        </td>
    </tr>
    <tr>
        <td style="width: 82%; padding-top: 4px;">
            <strong>8. &nbsp;Would you be <u>open</u> to reapply for future opportunities with our company?</strong> (mark ‘Yes’ or ‘No’)
        </td>
        <td style="width: 18%; text-align: right; padding-top: 4px;">
            Yes <span class="yes-no-box">{{ $reapVal === 'yes' ? '✓' : '' }}</span>
            &nbsp;
            No <span class="yes-no-box">{{ $reapVal === 'no' ? '✓' : '' }}</span>
        </td>
    </tr>
</table>

<!-- Section 9 -->
<div class="text-q-label">9. &nbsp;Any other comments:</div>
<div class="text-q-line">
    {{ $response->other_comments ?? $response->improvement_suggestions ?? '' }}
</div>

<!-- Signatures -->
<table class="team-member-sig-block">
    <tr>
        <td style="width: 16%; font-weight: bold;">Team Member:</td>
        <td style="width: 50%; text-align: center;">
            <!-- System Generated Digital Stamp / Seal -->
            <div class="digital-seal-badge">
                <div class="digital-seal-title">★ SYSTEM VERIFIED &amp; CONFIRMED ★</div>
                <div class="digital-seal-name">{{ strtoupper($response->team_member_signature ?? $survey->employee_name) }}</div>
                <div class="digital-seal-sub">SIGNED VIA GEORGE STEUART HR EXIT PORTAL</div>
            </div>
            <div class="sig-caption" style="margin-top: 2px;">Signature</div>
        </td>
        <td style="width: 34%; text-align: center;">
            <div style="font-weight: bold; font-size: 8pt; border-bottom: 1px solid #000000; padding-bottom: 2px; margin: 0 auto; width: 85%;">
                {{ $response->team_member_signed_date ? $response->team_member_signed_date->format('Y-m-d') : ($survey->submitted_at ? $survey->submitted_at->format('Y-m-d') : '') }}
            </div>
            <div class="sig-caption" style="margin-top: 2px;">Date</div>
        </td>
    </tr>
</table>

<div style="font-size: 7.8pt; font-weight: bold; margin-top: 4px; margin-bottom: 3px;">Reviewed By:</div>
<table class="reviewed-table">
    <thead>
        <tr>
            <th style="width: 33.3%;">Director - Group HR &amp; Administration</th>
            <th style="width: 33.3%;">Company Head</th>
            <th style="width: 33.4%;">Group Chairman</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                Signature &amp; Date:
                @if($response && $response->reviewed_director_hr)
                    <div style="font-weight: bold; margin-top: 3px;">{{ $response->reviewed_director_hr }}</div>
                    <div style="font-size: 6.8pt; color: #555;">{{ $response->reviewed_director_hr_date ? $response->reviewed_director_hr_date->format('Y-m-d') : '' }}</div>
                @endif
            </td>
            <td>
                Signature &amp; Date:
                @if($response && $response->reviewed_company_head)
                    <div style="font-weight: bold; margin-top: 3px;">{{ $response->reviewed_company_head }}</div>
                    <div style="font-size: 6.8pt; color: #555;">{{ $response->reviewed_company_head_date ? $response->reviewed_company_head_date->format('Y-m-d') : '' }}</div>
                @endif
            </td>
            <td>
                Signature &amp; Date:
                @if($response && $response->reviewed_group_chairman)
                    <div style="font-weight: bold; margin-top: 3px;">{{ $response->reviewed_group_chairman }}</div>
                    <div style="font-size: 6.8pt; color: #555;">{{ $response->reviewed_group_chairman_date ? $response->reviewed_group_chairman_date->format('Y-m-d') : '' }}</div>
                @endif
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
