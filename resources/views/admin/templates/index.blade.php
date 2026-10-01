@extends('layouts.admin')

@section('title', 'System Surveys')
@section('page-title', 'System Surveys')

@push('styles')
<style>
    /* Animated Wave Background Layer */
    .wave-bg-wrapper {
        position: fixed;
        top: 0;
        left: 270px;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
        background: 
            radial-gradient(circle at 10% 15%, rgba(225, 29, 72, 0.06) 0%, transparent 45%),
            radial-gradient(circle at 90% 75%, rgba(37, 99, 235, 0.05) 0%, transparent 45%),
            radial-gradient(circle at 50% 50%, rgba(244, 63, 94, 0.03) 0%, transparent 55%),
            #f6f9fc;
    }

    .top-ambient-wave {
        position: absolute;
        top: -120px;
        right: -80px;
        width: 520px;
        height: 520px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(225, 29, 72, 0.09) 0%, rgba(225, 29, 72, 0) 70%);
        animation: pulse-ambient 9s ease-in-out infinite alternate;
    }

    .bottom-ambient-wave {
        position: absolute;
        bottom: 40px;
        left: -100px;
        width: 480px;
        height: 480px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.07) 0%, rgba(37, 99, 235, 0) 70%);
        animation: pulse-ambient 11s ease-in-out infinite alternate-reverse;
    }

    .ocean-waves {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 180px;
        min-height: 140px;
        max-height: 220px;
    }

    .floating-waves > use {
        animation: wave-glide 22s cubic-bezier(.55,.5,.45,.5) infinite;
    }
    .floating-waves > use:nth-child(1) {
        animation-delay: -2s;
        animation-duration: 8s;
    }
    .floating-waves > use:nth-child(2) {
        animation-delay: -3s;
        animation-duration: 12s;
    }
    .floating-waves > use:nth-child(3) {
        animation-delay: -4s;
        animation-duration: 16s;
    }
    .floating-waves > use:nth-child(4) {
        animation-delay: -5s;
        animation-duration: 24s;
    }

    @keyframes wave-glide {
        0% { transform: translate3d(-90px, 0, 0); }
        100% { transform: translate3d(85px, 0, 0); }
    }

    @keyframes pulse-ambient {
        0% { transform: scale(1) translate(0, 0); opacity: 0.6; }
        100% { transform: scale(1.18) translate(-25px, 35px); opacity: 1; }
    }

    /* Full-width Responsive Container */
    .gform-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding-top: 6px;
        padding-bottom: 60px;
        position: relative;
        z-index: 10;
    }

    /* Clear spacing between each Card Section */
    #exitTemplateForm {
        display: flex;
        flex-direction: column;
        gap: 36px;
    }

    /* Cards with soft glassmorphism touch */
    .gform-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(226, 232, 240, 0.95);
        border-radius: 20px;
        padding: 34px 40px;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.05);
        position: relative;
        transition: all 0.25s ease;
    }
    .gform-card:hover, .gform-card:focus-within {
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.09);
        border-color: #cbd5e1;
    }

    /* Section Headers */
    .gform-section-title {
        font-size: 16.5px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .gform-section-desc {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 22px;
        line-height: 1.5;
    }
    .req-star {
        color: #e11d48;
        margin-left: 2px;
    }

    /* Modern Form Inputs */
    .gform-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    .gform-field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .gform-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }
    .gform-input, .gform-select {
        width: 100%;
        padding: 12px 15px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13.5px;
        color: #0f172a;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .gform-input:focus, .gform-select:focus, .gform-textarea:focus {
        outline: none;
        background: #ffffff;
        border-color: #e11d48;
        box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1);
    }
    .gform-textarea {
        width: 100%;
        min-height: 95px;
        padding: 14px 16px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13.5px;
        color: #0f172a;
        line-height: 1.55;
        resize: vertical;
        box-sizing: border-box;
        font-family: inherit;
        transition: all 0.2s ease;
    }

    /* Searchable Company Dropdown */
    .company-dropdown-container {
        position: relative;
        width: 100%;
    }
    .company-dropdown-trigger {
        width: 100%;
        padding: 11px 15px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13.5px;
        color: #0f172a;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        user-select: none;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .company-dropdown-trigger:hover {
        background: #ffffff;
        border-color: #cbd5e1;
    }
    .company-dropdown-container.open .company-dropdown-trigger {
        background: #ffffff;
        border-color: #e11d48;
        box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1);
    }
    .company-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
        z-index: 100;
        display: none;
        overflow: hidden;
        animation: dropDownSlide 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes dropDownSlide {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .company-dropdown-container.open .company-dropdown-menu {
        display: block;
    }
    .company-search-box {
        padding: 10px 12px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        position: relative;
    }
    .company-search-input {
        width: 100%;
        padding: 9px 12px 9px 34px;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13px;
        color: #0f172a;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.15s;
    }
    .company-search-input:focus {
        border-color: #e11d48;
    }
    .company-search-icon {
        position: absolute;
        left: 22px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        color: #94a3b8;
        pointer-events: none;
    }
    .company-options-list {
        max-height: 240px;
        overflow-y: auto;
        padding: 6px 0;
        list-style: none;
        margin: 0;
    }
    .company-option-item {
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background-color 0.12s;
        font-size: 13.5px;
    }
    .company-option-item:hover, .company-option-item.highlighted {
        background-color: #fff1f2;
    }
    .company-option-item.selected {
        background-color: #ffe4e6;
        font-weight: 600;
    }
    .company-badge-code {
        background: #e0e7ff;
        color: #3730a3;
        padding: 2px 7px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-left: 8px;
    }
    .company-meta-tag {
        font-size: 11px;
        color: #64748b;
    }
    .company-empty-state {
        padding: 18px 16px;
        text-align: center;
        color: #94a3b8;
        font-size: 13px;
    }

    /* Rating Scale Table Component */
    .rating-matrix-card {
        padding: 26px 32px;
    }
    .rating-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
        background: #ffffff;
    }
    .rating-matrix-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }
    .rating-matrix-table th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 14px 12px;
        font-size: 12.5px;
        font-weight: 700;
        text-align: center;
        color: #334155;
        vertical-align: middle;
    }
    .rating-matrix-table th.col-area {
        text-align: left;
        padding-left: 20px;
        font-size: 13px;
    }
    .rating-matrix-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .rating-matrix-table tr:last-child td {
        border-bottom: none;
    }
    .rating-matrix-table tr.matrix-row {
        transition: background-color 0.15s ease;
    }
    .rating-matrix-table tr.matrix-row:hover {
        background-color: #fff1f2;
    }
    .rating-matrix-table td.matrix-cell-radio {
        text-align: center;
        cursor: pointer;
        padding: 0;
        transition: background-color 0.15s;
    }
    .rating-matrix-table td.matrix-cell-radio:has(input:checked) {
        background-color: #ffe4e6;
    }
    .matrix-radio-label {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        padding: 14px 8px;
        cursor: pointer;
        margin: 0;
    }
    .matrix-radio-label input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #e11d48;
        cursor: pointer;
        margin: 0;
    }
    .col-header-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
    }

    /* Option Item Cards (Google Forms Style) */
    .gform-option-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .gform-option-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 13px 20px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.18s ease;
        font-size: 13.5px;
        color: #1e293b;
        font-weight: 500;
    }
    .gform-option-item:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    }
    .gform-option-item:has(input:checked) {
        background: #fff1f2;
        border-color: #e11d48;
        color: #9f1239;
        font-weight: 600;
        box-shadow: 0 0 0 2px rgba(225, 29, 72, 0.12);
    }
    .gform-option-item input[type="radio"], .gform-option-item input[type="checkbox"] {
        width: 19px;
        height: 19px;
        accent-color: #e11d48;
        cursor: pointer;
        flex-shrink: 0;
        margin: 0;
    }

    /* Horizontal Yes/No Row Layout */
    .recommend-reapply-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: stretch;
    }
    .recommend-box {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px 24px;
        transition: all 0.2s ease;
    }
    .recommend-box:hover {
        border-color: #cbd5e1;
        background: #ffffff;
        box-shadow: 0 4px 14px rgba(0,0,0,0.04);
    }
    .yes-no-group {
        display: flex;
        gap: 12px;
        margin-top: 14px;
    }
    .yes-no-chip {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        font-size: 13.5px;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        transition: all 0.18s ease;
    }
    .yes-no-chip:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .yes-no-chip:has(input:checked) {
        background: #fff1f2;
        border-color: #e11d48;
        color: #be123c;
        box-shadow: 0 0 0 2px rgba(225, 29, 72, 0.15);
    }

    /* Bottom Action Sticky Bar */
    .gform-action-bar {
        position: sticky;
        bottom: 20px;
        z-index: 40;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border: 1px solid #cbd5e1;
        border-radius: 18px;
        padding: 18px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    @media (max-width: 900px) {
        .recommend-reapply-row { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .wave-bg-wrapper { left: 0; }
        .gform-card { padding: 22px 18px; }
        .gform-header-body { padding: 24px 20px; }
        .gform-action-bar { flex-direction: column; text-align: center; }
    }

    @media print {
        body { background: #ffffff !important; }
        .wave-bg-wrapper, .sidebar, .topbar, .gform-action-bar, .btn { display: none !important; }
        .main { margin-left: 0 !important; }
        .content { padding: 0 !important; }
        .gform-card { box-shadow: none !important; border: 1px solid #ccc !important; }
    }
</style>
@endpush

@section('content')
<!-- Animated Wave Background -->
<div class="wave-bg-wrapper">
    <div class="top-ambient-wave"></div>
    <div class="bottom-ambient-wave"></div>
    <svg class="ocean-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none">
        <defs>
            <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
        </defs>
        <g class="floating-waves">
            <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(225, 29, 72, 0.05)" />
            <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(244, 63, 94, 0.07)" />
            <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(37, 99, 235, 0.04)" />
            <use xlink:href="#gentle-wave" x="48" y="7" fill="rgba(241, 245, 249, 0.85)" />
        </g>
    </svg>
</div>

<div class="gform-container">

    @if (session('success'))
        <div style="background: #f0fdf4; border: 1.5px solid #86efac; border-left: 5px solid #16a34a; border-radius: 14px; padding: 20px 24px; color: #166534; font-size: 14px; box-shadow: 0 4px 15px rgba(22,163,74,0.12); display: flex; align-items: flex-start; justify-content: space-between; gap: 14px;">
            <div style="display: flex; gap: 14px; align-items: center;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #dcfce7; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                    ✅
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 15px; color: #14532d; margin-bottom: 2px;">
                        Exit Interview Successfully Recorded!
                    </div>
                    <div style="color: #166534; font-size: 13.5px; line-height: 1.5;">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 18px; color: #15803d; cursor: pointer; padding: 4px; line-height: 1;">✕</button>
        </div>
    @endif

    @if ($errors->any())
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; border-radius: 12px; padding: 18px 22px; color: #991b1b; font-size: 13.5px; box-shadow: 0 2px 10px rgba(239,68,68,0.1);">
            <div style="font-weight: 700; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                <span>⚠️</span> <span>Please fill in all mandatory questions before submitting:</span>
            </div>
            <ul style="margin: 0; padding-left: 20px; font-size: 12.5px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.templates.store') }}" id="exitTemplateForm">
        @csrf

        <!-- 1. Team Member Details Card -->
        <div class="gform-card">
            <div class="gform-section-title">
                <span>Team Member Details:</span>
                <span style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px;">SECTION 1</span>
            </div>
            <div class="gform-section-desc">
                Basic candidate particulars and employment timeline at George Steuart Group.
            </div>

            <div class="gform-grid">
                <div class="gform-field-group">
                    <label class="gform-label">Name: <span class="req-star">*</span></label>
                    <input type="text" name="employee_name" class="gform-input" placeholder="Full name of employee" value="{{ old('employee_name') }}" required>
                </div>

                <div class="gform-field-group">
                    <label class="gform-label">Team Member No: <span class="req-star">*</span></label>
                    <input type="text" name="employee_id" class="gform-input" placeholder="e.g. EPF or Staff ID" value="{{ old('employee_id') }}" required>
                </div>

                <div class="gform-field-group">
                    <label class="gform-label">Company: <span class="req-star">*</span></label>
                    <div class="company-dropdown-container" id="companyDropdownContainer">
                        <input type="hidden" name="company_id" id="selectedCompanyId" value="{{ old('company_id') }}" required>
                        <div class="company-dropdown-trigger" id="companyDropdownTrigger" tabindex="0">
                            <span id="selectedCompanyText" style="display:flex;align-items:center;gap:8px;">
                                <span style="color:#94a3b8;">-- Select Subsidiary Company --</span>
                            </span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="transition:transform 0.2s;" id="companyDropdownChevron">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="company-dropdown-menu" id="companyDropdownMenu">
                            <div class="company-search-box">
                                <span class="company-search-icon">🔍</span>
                                <input type="text" class="company-search-input" id="companySearchInput" placeholder="Search company name or code..." autocomplete="off">
                            </div>
                            <ul class="company-options-list" id="companyOptionsList">
                                @foreach($companies as $company)
                                    <li class="company-option-item {{ old('company_id') == $company->id ? 'selected' : '' }}"
                                        data-id="{{ $company->id }}"
                                        data-name="{{ $company->name }}"
                                        data-code="{{ $company->code }}"
                                        data-search="{{ strtolower($company->name . ' ' . $company->code) }}">
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <span class="company-name-text">{{ $company->name }}</span>
                                            <span class="company-badge-code">{{ $company->code }}</span>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            @if($company->headcount)
                                                <span class="company-meta-tag">👥 {{ number_format($company->headcount) }}</span>
                                            @endif
                                            <span class="check-indicator" style="display:{{ old('company_id') == $company->id ? 'inline' : 'none' }};color:#e11d48;font-weight:700;">✓</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="company-empty-state" id="companyEmptyState" style="display:none;">
                                🏢 No companies found matching your search.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="gform-field-group">
                    <label class="gform-label">Designation: <span class="req-star">*</span></label>
                    <input type="text" name="designation" class="gform-input" placeholder="e.g. Senior Executive" value="{{ old('designation') }}" required>
                </div>

                <div class="gform-field-group">
                    <label class="gform-label">Section/ Division/ Department: <span class="req-star">*</span></label>
                    <input type="text" name="department" class="gform-input" placeholder="e.g. Finance & Accounting" value="{{ old('department') }}" required>
                </div>

                <div class="gform-field-group">
                    <label class="gform-label">Immediate Supervisor’s Name: <span class="req-star">*</span></label>
                    <input type="text" name="supervisor_name" class="gform-input" placeholder="e.g. Reporting Manager" value="{{ old('supervisor_name') }}" required>
                </div>

                <div class="gform-field-group">
                    <label class="gform-label">Date of Joining: <span class="req-star">*</span></label>
                    <input type="date" name="date_joined" class="gform-input" value="{{ old('date_joined') }}" required>
                </div>

                <div class="gform-field-group">
                    <label class="gform-label">Date of Resignation: <span class="req-star">*</span></label>
                    <input type="date" name="date_of_resignation" class="gform-input" value="{{ old('date_of_resignation') }}" required>
                </div>

                <div class="gform-field-group" style="grid-column: 1 / -1;">
                    <label class="gform-label">Employee Email: <span class="req-star">*</span></label>
                    <input type="email" name="employee_email" class="gform-input" placeholder="e.g. personal.email@example.com (Required for notification & link records)" value="{{ old('employee_email') }}" required>
                </div>
            </div>
        </div>

        <!-- 2. Rating Matrix Card (20 Areas) -->
        <div class="gform-card rating-matrix-card">
            <div class="gform-section-title">
                <span>Experience Assessment Matrix (20 Specific Areas) <span class="req-star">*</span></span>
                <span style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px;">SECTION 2</span>
            </div>
            <div class="gform-section-desc">
                Please complete the following, based on your experience with the company, by marking ‘✓’ in the given box:
            </div>

            <div class="rating-table-wrapper">
                <table class="rating-matrix-table">
                    <thead>
                        <tr>
                            <th style="width: 44px; text-align: center;">No</th>
                            <th class="col-area">Area</th>
                            <th style="width: 115px;">
                                <div class="col-header-pill" style="background: #dcfce7; color: #15803d;">Highly Satisfied</div>
                                <div style="font-size: 10.5px; color: #64748b; margin-top: 2px; font-weight: 500;">(5)</div>
                            </th>
                            <th style="width: 105px;">
                                <div class="col-header-pill" style="background: #ccfbf1; color: #0f766e;">Satisfied</div>
                                <div style="font-size: 10.5px; color: #64748b; margin-top: 2px; font-weight: 500;">(4)</div>
                            </th>
                            <th style="width: 100px;">
                                <div class="col-header-pill" style="background: #fef3c7; color: #b45309;">Average</div>
                                <div style="font-size: 10.5px; color: #64748b; margin-top: 2px; font-weight: 500;">(3)</div>
                            </th>
                            <th style="width: 105px;">
                                <div class="col-header-pill" style="background: #ffedd5; color: #c2410c;">Dissatisfied</div>
                                <div style="font-size: 10.5px; color: #64748b; margin-top: 2px; font-weight: 500;">(2)</div>
                            </th>
                            <th style="width: 120px;">
                                <div class="col-header-pill" style="background: #fee2e2; color: #b91c1c;">Highly Dissatisfied</div>
                                <div style="font-size: 10.5px; color: #64748b; margin-top: 2px; font-weight: 500;">(1)</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($areas as $num => $areaTitle)
                        <tr class="matrix-row">
                            <td style="text-align: center; font-weight: 700; color: #64748b; font-size: 12px;">{{ $num }}</td>
                            <td style="padding-left: 20px; font-size: 13.5px; font-weight: 500; color: #1e293b;">
                                {{ $areaTitle }} <span class="req-star">*</span>
                            </td>
                            @for($rating = 5; $rating >= 1; $rating--)
                            <td class="matrix-cell-radio">
                                <label class="matrix-radio-label" title="{{ $areaTitle }} - {{ $rating }}">
                                    <input type="radio" name="area_ratings[{{ $num }}]" value="{{ $rating }}" {{ old("area_ratings.{$num}") == $rating ? 'checked' : '' }} required>
                                </label>
                            </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. What is your main reason to leave the company? -->
        <div class="gform-card">
            <div class="gform-section-title">
                <span>What is your main reason to leave the company? (Select one option and mark with ‘✓’) <span class="req-star">*</span></span>
                <span style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px;">SECTION 3</span>
            </div>
            <div class="gform-section-desc">
                Select the single primary driver that triggered your resignation.
            </div>

            <div class="gform-option-list">
                <!-- Option 1 -->
                <label class="gform-option-item">
                    <input type="radio" name="main_reason_to_leave" value="Joining a local company for a better position/ salary" {{ old('main_reason_to_leave') == 'Joining a local company for a better position/ salary' ? 'checked' : '' }} required>
                    <span>1. Joining a local company for a better position/ salary</span>
                </label>

                <!-- Option 2: Going oversees -->
                <div style="border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 20px; background: #f8fafc; transition: all 0.2s;" id="overseasContainer">
                    <label style="display: flex; align-items: center; gap: 14px; cursor: pointer; font-size: 13.5px; font-weight: 600; color: #1e293b;">
                        <input type="radio" name="main_reason_to_leave" value="Going oversees:" id="overseasRadio" {{ old('main_reason_to_leave') == 'Going oversees:' ? 'checked' : '' }} required style="width: 19px; height: 19px; accent-color: #e11d48;">
                        <span>2. Going oversees:</span>
                    </label>

                    <div style="margin-top: 12px; margin-left: 32px; display: flex; flex-wrap: wrap; gap: 14px;">
                        <label class="gform-option-item" style="padding: 8px 16px; font-size: 13px; background: #ffffff;">
                            <input type="radio" name="overseas_sub_option" value="Overseas job" {{ old('overseas_sub_option') == 'Overseas job' ? 'checked' : '' }}>
                            <span>Overseas job</span>
                        </label>
                        <label class="gform-option-item" style="padding: 8px 16px; font-size: 13px; background: #ffffff;">
                            <input type="radio" name="overseas_sub_option" value="Higher studies" {{ old('overseas_sub_option') == 'Higher studies' ? 'checked' : '' }}>
                            <span>Higher studies</span>
                        </label>
                        <label class="gform-option-item" style="padding: 8px 16px; font-size: 13px; background: #ffffff;">
                            <input type="radio" name="overseas_sub_option" value="Migration" {{ old('overseas_sub_option') == 'Migration' ? 'checked' : '' }}>
                            <span>Migration</span>
                        </label>
                    </div>
                </div>

                <!-- Option 3 -->
                <label class="gform-option-item">
                    <input type="radio" name="main_reason_to_leave" value="Shifting to another industry/career" {{ old('main_reason_to_leave') == 'Shifting to another industry/career' ? 'checked' : '' }} required>
                    <span>3. Shifting to another industry/career</span>
                </label>

                <!-- Option 4 -->
                <label class="gform-option-item">
                    <input type="radio" name="main_reason_to_leave" value="Personal health related reasons" {{ old('main_reason_to_leave') == 'Personal health related reasons' ? 'checked' : '' }} required>
                    <span>4. Personal health related reasons</span>
                </label>

                <!-- Option 5 -->
                <label class="gform-option-item">
                    <input type="radio" name="main_reason_to_leave" value="Family or personal commitments/reasons" {{ old('main_reason_to_leave') == 'Family or personal commitments/reasons' ? 'checked' : '' }} required>
                    <span>5. Family or personal commitments/reasons</span>
                </label>

                <!-- Option 6 -->
                <label class="gform-option-item">
                    <input type="radio" name="main_reason_to_leave" value="Distance to workplace/ Relocation" {{ old('main_reason_to_leave') == 'Distance to workplace/ Relocation' ? 'checked' : '' }} required>
                    <span>6. Distance to workplace/ Relocation</span>
                </label>
            </div>
        </div>

        <!-- 4. Any other reasons to leave the company? -->
        <div class="gform-card">
            <div class="gform-section-title">
                <span>Any other reasons to leave the company? (Select multiple options and mark with ‘✓’ if applicable) <span class="req-star">*</span></span>
                <span style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px;">SECTION 4</span>
            </div>
            <div class="gform-section-desc">
                Select all applicable secondary contributing reasons.
            </div>

            <div class="gform-option-list">
                @foreach($otherReasons as $num => $reason)
                <label class="gform-option-item">
                    <input type="checkbox" name="other_reasons_to_leave[]" value="{{ $reason }}" {{ is_array(old('other_reasons_to_leave')) && in_array($reason, old('other_reasons_to_leave')) ? 'checked' : '' }}>
                    <span>{{ $num }}. {{ $reason }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- 5. What did you like the most about your work experience? -->
        <div class="gform-card">
            <div class="gform-section-title">
                <span>What did you like the most about your work experience? (Select multiple options and mark with ‘✓’ as applicable) <span class="req-star">*</span></span>
                <span style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px;">SECTION 5</span>
            </div>
            <div class="gform-section-desc">
                Check all areas that brought fulfillment, pride, and value during your tenure.
            </div>

            <div class="gform-grid" style="gap: 14px;">
                @foreach($likedMost as $num => $aspect)
                <label class="gform-option-item" style="font-size: 13px;">
                    <input type="checkbox" name="liked_most_aspects[]" value="{{ $aspect }}" {{ is_array(old('liked_most_aspects')) && in_array($aspect, old('liked_most_aspects')) ? 'checked' : '' }}>
                    <span>{{ $num }}. {{ $aspect }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- 6. In your own words, what did you enjoy most about working here? -->
        <div class="gform-card">
            <div class="gform-section-title">
                <span>In your own words, what did you enjoy most about working here? <span class="req-star">*</span></span>
                <span style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px;">SECTION 6</span>
            </div>
            <div class="gform-section-desc">
                Share your personal highlights, projects, camaraderie, or unique achievements.
            </div>
            <textarea name="enjoyed_most" class="gform-textarea" placeholder="Your reflections on the best parts of working with us..." required>{{ old('enjoyed_most') }}</textarea>
        </div>

        <!-- 7. What would have prevented your resignation: -->
        <div class="gform-card">
            <div class="gform-section-title">
                <span>What would have prevented your resignation: <span class="req-star">*</span></span>
                <span style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px;">SECTION 7</span>
            </div>
            <div class="gform-section-desc">
                What changes in management, compensation, role clarity, or culture could have persuaded you to stay?
            </div>
            <textarea name="prevented_resignation" class="gform-textarea" placeholder="Constructive changes that could have retained your talent..." required>{{ old('prevented_resignation') }}</textarea>
        </div>

        <!-- 8 & 9. Recommendation & Future Reapplication (Single Line / Row Layout) -->
        <div class="gform-card">
            <div class="recommend-reapply-row">
                <!-- 8. Would you recommend our company as a potential employer to others? -->
                <div class="recommend-box">
                    <div>
                        <div style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px; display: inline-block; margin-bottom: 8px;">SECTION 8</div>
                        <div style="font-size: 14.5px; font-weight: 700; color: #0f172a; line-height: 1.4;">
                            Would you recommend our company as a potential employer to others? (mark ‘Yes’ or ‘No’) <span class="req-star">*</span>
                        </div>
                        <div style="font-size: 12.5px; color: #64748b; margin-top: 4px;">
                            Your candid evaluation as an employee ambassador (eNPS benchmark).
                        </div>
                    </div>
                    <div class="yes-no-group">
                        <label class="yes-no-chip">
                            <input type="radio" name="recommend_company" value="Yes" {{ old('recommend_company') == 'Yes' ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48;">
                            <span>👍 Yes, I would recommend</span>
                        </label>
                        <label class="yes-no-chip">
                            <input type="radio" name="recommend_company" value="No" {{ old('recommend_company') == 'No' ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48;">
                            <span>👎 No, I would not</span>
                        </label>
                    </div>
                </div>

                <!-- 9. Would you be open to reapply for future opportunities with our company? -->
                <div class="recommend-box">
                    <div>
                        <div style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px; display: inline-block; margin-bottom: 8px;">SECTION 9</div>
                        <div style="font-size: 14.5px; font-weight: 700; color: #0f172a; line-height: 1.4;">
                            Would you be open to reapply for future opportunities with our company? (mark ‘Yes’ or ‘No’) <span class="req-star">*</span>
                        </div>
                        <div style="font-size: 12.5px; color: #64748b; margin-top: 4px;">
                            Potential for future rehiring and talent boomerang opportunities.
                        </div>
                    </div>
                    <div class="yes-no-group">
                        <label class="yes-no-chip">
                            <input type="radio" name="open_to_reapply" value="Yes" {{ old('open_to_reapply') == 'Yes' ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48;">
                            <span>🤝 Yes, I would return</span>
                        </label>
                        <label class="yes-no-chip">
                            <input type="radio" name="open_to_reapply" value="No" {{ old('open_to_reapply') == 'No' ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48;">
                            <span>🛑 No, not at this stage</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 10. Any other comments: -->
        <div class="gform-card">
            <div class="gform-section-title">
                <span>Any other comments: <span class="req-star">*</span></span>
                <span style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px;">SECTION 10</span>
            </div>
            <div class="gform-section-desc">
                Final remarks, confidential leadership notes, or constructive observations.
            </div>
            <textarea name="other_comments" class="gform-textarea" placeholder="Any additional comments or parting thoughts..." required>{{ old('other_comments') }}</textarea>
        </div>

        <!-- 11. Sign-off / Review Block -->
        <div class="gform-card">
            <div class="gform-section-title">
                <span>Team Member Sign-off &amp; Executive Review</span>
                <span style="font-size: 11px; background: #fee2e2; color: #be123c; font-weight: 700; padding: 3px 8px; border-radius: 6px;">SECTION 11</span>
            </div>
            <div class="gform-section-desc">
                Authentication of submitted feedback and leadership acknowledgment.
            </div>

            <!-- Team Member Signature -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 24px;">
                <div style="font-size: 13.5px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Team Member Signature:</div>
                <div class="gform-grid">
                    <div class="gform-field-group">
                        <label class="gform-label">Digital Signature (Type Full Legal Name): <span class="req-star">*</span></label>
                        <input type="text" name="team_member_signature" class="gform-input" placeholder="e.g. Johnathan Silva" value="{{ old('team_member_signature') }}" required>
                    </div>
                    <div class="gform-field-group">
                        <label class="gform-label">Date Signed: <span class="req-star">*</span></label>
                        <input type="date" name="team_member_signed_date" class="gform-input" value="{{ old('team_member_signed_date', date('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>

            <!-- Reviewed By Block -->
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Reviewed By: (Optional / Administrative)</div>
            <div class="gform-grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
                <!-- Director Group HR -->
                <div style="border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px; background: #ffffff;">
                    <div style="font-size: 12.5px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">Director - Group HR &amp; Administration</div>
                    <div class="gform-field-group" style="margin-bottom: 8px;">
                        <input type="text" name="reviewed_director_hr" class="gform-input" placeholder="Reviewer Signature / Name" value="{{ old('reviewed_director_hr') }}">
                    </div>
                    <div class="gform-field-group">
                        <input type="date" name="reviewed_director_hr_date" class="gform-input" value="{{ old('reviewed_director_hr_date') }}">
                    </div>
                </div>

                <!-- Company Head -->
                <div style="border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px; background: #ffffff;">
                    <div style="font-size: 12.5px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">Company Head</div>
                    <div class="gform-field-group" style="margin-bottom: 8px;">
                        <input type="text" name="reviewed_company_head" class="gform-input" placeholder="Reviewer Signature / Name" value="{{ old('reviewed_company_head') }}">
                    </div>
                    <div class="gform-field-group">
                        <input type="date" name="reviewed_company_head_date" class="gform-input" value="{{ old('reviewed_company_head_date') }}">
                    </div>
                </div>

                <!-- Group Chairman -->
                <div style="border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px; background: #ffffff;">
                    <div style="font-size: 12.5px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">Group Chairman</div>
                    <div class="gform-field-group" style="margin-bottom: 8px;">
                        <input type="text" name="reviewed_group_chairman" class="gform-input" placeholder="Reviewer Signature / Name" value="{{ old('reviewed_group_chairman') }}">
                    </div>
                    <div class="gform-field-group">
                        <input type="date" name="reviewed_group_chairman_date" class="gform-input" value="{{ old('reviewed_group_chairman_date') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Action Bar with Submit Button -->
        <div class="gform-action-bar">
            <div>
                <div style="font-size: 15px; font-weight: 700; color: #0f172a;">Ready to record exit survey?</div>
                <div style="font-size: 12.5px; color: #64748b;">Submitting saves all particulars, ratings, and feedback directly into the database.</div>
            </div>
            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="reset" class="btn btn-secondary" style="padding: 12px 20px; font-size: 13.5px;">
                    Reset
                </button>
                <button type="submit" class="btn btn-primary" style="padding: 13px 30px; font-size: 14.5px; font-weight: 700; background: linear-gradient(135deg, #e11d48, #be123c); border-color: #be123c; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 16px rgba(225, 29, 72, 0.35);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Submit Exit Interview &amp; Save to Database
                </button>
            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    // Searchable Company Dropdown
    (function() {
        const container = document.getElementById('companyDropdownContainer');
        if (!container) return;

        const trigger = document.getElementById('companyDropdownTrigger');
        const searchInput = document.getElementById('companySearchInput');
        const hiddenInput = document.getElementById('selectedCompanyId');
        const selectedText = document.getElementById('selectedCompanyText');
        const optionsList = document.getElementById('companyOptionsList');
        const emptyState = document.getElementById('companyEmptyState');
        const chevron = document.getElementById('companyDropdownChevron');
        const items = optionsList.querySelectorAll('.company-option-item');

        function openDropdown() {
            container.classList.add('open');
            chevron.style.transform = 'rotate(180deg)';
            searchInput.value = '';
            filterOptions('');
            setTimeout(() => searchInput.focus(), 60);
        }

        function closeDropdown() {
            container.classList.remove('open');
            chevron.style.transform = 'rotate(0deg)';
        }

        function filterOptions(query) {
            const q = query.toLowerCase().trim();
            let matches = 0;
            items.forEach(item => {
                const searchText = item.getAttribute('data-search') || '';
                if (searchText.includes(q)) {
                    item.style.display = 'flex';
                    matches++;
                } else {
                    item.style.display = 'none';
                }
            });
            emptyState.style.display = matches === 0 ? 'block' : 'none';
        }

        function selectCompany(id, name, code) {
            hiddenInput.value = id;
            selectedText.innerHTML = `
                <span style="font-weight:600;color:#0f172a;">${name}</span>
                <span class="company-badge-code">${code}</span>
            `;
            items.forEach(item => {
                const isSelected = item.getAttribute('data-id') == id;
                item.classList.toggle('selected', isSelected);
                const check = item.querySelector('.check-indicator');
                if (check) check.style.display = isSelected ? 'inline' : 'none';
            });
            closeDropdown();
            trigger.focus();
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        // Initialize preselected option if any
        if (hiddenInput.value) {
            const preselectedItem = optionsList.querySelector(`.company-option-item[data-id="${hiddenInput.value}"]`);
            if (preselectedItem) {
                selectCompany(
                    preselectedItem.getAttribute('data-id'),
                    preselectedItem.getAttribute('data-name'),
                    preselectedItem.getAttribute('data-code')
                );
            }
        }

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            if (container.classList.contains('open')) {
                closeDropdown();
            } else {
                openDropdown();
            }
        });

        trigger.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                e.preventDefault();
                openDropdown();
            }
        });

        searchInput.addEventListener('input', function(e) {
            filterOptions(e.target.value);
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDropdown();
                trigger.focus();
            }
        });

        items.forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const code = this.getAttribute('data-code');
                selectCompany(id, name, code);
            });
        });

        document.addEventListener('click', function(e) {
            if (!container.contains(e.target)) {
                closeDropdown();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && container.classList.contains('open')) {
                closeDropdown();
                trigger.focus();
            }
        });
    })();

    // Frontend validation to verify required fields and at least one checkbox is checked
    document.getElementById('exitTemplateForm').addEventListener('submit', function(e) {
        const companyId = document.getElementById('selectedCompanyId').value;
        if (!companyId) {
            e.preventDefault();
            alert('Please select a Subsidiary Company from the searchable dropdown.');
            document.getElementById('companyDropdownTrigger').scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.getElementById('companyDropdownTrigger').focus();
            return false;
        }

        const otherReasons = document.querySelectorAll('input[name="other_reasons_to_leave[]"]:checked');
        if (otherReasons.length === 0) {
            e.preventDefault();
            alert('Please select at least one option under "Section 4: Any other reasons to leave the company?".');
            return false;
        }

        const likedAspects = document.querySelectorAll('input[name="liked_most_aspects[]"]:checked');
        if (likedAspects.length === 0) {
            e.preventDefault();
            alert('Please select at least one option under "Section 5: What did you like the most about your work experience?".');
            return false;
        }
    });
</script>
@endpush
