<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Exit Interview Form — {{ $survey->company->name ?? 'George Steuart Group' }}</title>
    <meta name="description" content="Official George Steuart Group Exit Interview Questionnaire">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8C0026;
            --primary-light: #B30031;
            --accent: #c9993a;
            --surface: #ffffff;
            --text: #1a202c;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --brand-red: #e11d48;
            --brand-dark: #0f172a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f6f9fc;
            color: var(--text);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Wave Background Layer (Full Viewport) */
        .wave-bg-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
            background: 
                radial-gradient(circle at 10% 15%, rgba(140, 0, 38, 0.05) 0%, transparent 45%),
                radial-gradient(circle at 90% 75%, rgba(140, 0, 38, 0.04) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(179, 0, 49, 0.03) 0%, transparent 55%),
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
        .floating-waves > use:nth-child(1) { animation-delay: -2s; animation-duration: 8s; }
        .floating-waves > use:nth-child(2) { animation-delay: -3s; animation-duration: 12s; }
        .floating-waves > use:nth-child(3) { animation-delay: -4s; animation-duration: 16s; }
        .floating-waves > use:nth-child(4) { animation-delay: -5s; animation-duration: 24s; }

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
            max-width: 1240px;
            margin: 0 auto;
            padding: 32px 24px 80px;
            position: relative;
            z-index: 10;
        }

        .form-layout {
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        /* Public Top Navigation Bar */
        .public-topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.04);
            height: 68px;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .public-topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .public-topbar-logo {
            height: 44px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .public-topbar-center {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .topbar-expiry-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12.5px;
            color: #92400e;
            font-weight: 600;
        }

        .topbar-expiry-pill .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }

        .topbar-save-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            color: #166534;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }

        .topbar-save-pill .save-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
        }

        .public-topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-exit-tag {
            font-size: 14px;
            font-weight: 700;
            color: #8C0026;
            letter-spacing: -0.2px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 6px 14px;
            border-radius: 8px;
        }

        /* Section Cards */
        .gform-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 32px 36px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .gform-section-title {
            font-size: 17px;
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
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .req-star { color: #e11d48; margin-left: 2px; }

        /* Form Grids */
        .gform-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px 24px;
        }

        .gform-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 24px;
        }

        @media (max-width: 960px) {
            .gform-grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .gform-grid-3,
            .gform-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .gform-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .gform-label {
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .gform-input {
            width: 100%;
            height: 44px;
            padding: 0 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13.5px;
            color: #0f172a;
            background: #ffffff;
            font-family: inherit;
            transition: all 0.2s;
        }

        .gform-input:focus {
            outline: none;
            border-color: #e11d48;
            box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.12);
        }

        .gform-input-disabled {
            background: #f8fafc !important;
            color: #334155 !important;
            font-weight: 600 !important;
            border-color: #e2e8f0 !important;
            cursor: not-allowed !important;
        }

        .gform-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13.5px;
            color: #0f172a;
            font-family: inherit;
            min-height: 100px;
            resize: vertical;
            transition: all 0.2s;
        }

        .gform-textarea:focus {
            outline: none;
            border-color: #e11d48;
            box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.12);
        }

        /* Matrix Table */
        .rating-table-wrapper {
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .rating-matrix-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .rating-matrix-table th {
            background: #f8fafc;
            padding: 14px 10px;
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 700;
        }

        .rating-matrix-table th.col-area {
            text-align: left;
            padding-left: 20px;
            font-size: 13px;
            color: #334155;
        }

        .col-header-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .matrix-row {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }

        .matrix-row:hover { background: #fafbfc; }

        .matrix-cell-radio {
            text-align: center;
            padding: 12px 6px;
        }

        .matrix-radio-label {
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            height: 100%;
        }

        .matrix-radio-label input[type="radio"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #e11d48;
        }

        /* Options list & grids */
        .gform-option-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .gform-options-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px 16px;
        }

        .departure-other-reasons-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 32px;
        }

        .gform-options-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px 16px;
        }

        @media (max-width: 1080px) {
            .gform-options-grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .gform-options-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .gform-options-grid-3 {
                grid-template-columns: 1fr;
            }
            .departure-other-reasons-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }
        }

        .gform-option-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            cursor: pointer;
            font-size: 13.5px;
            font-weight: 500;
            color: #1e293b;
            line-height: 1.45;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            height: 100%;
        }

        .gform-option-item:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .gform-option-item:has(input:checked),
        .gform-option-item.is-selected {
            border-color: #e11d48;
            background: #fff5f7;
            box-shadow: 0 4px 14px rgba(225, 29, 72, 0.08);
        }

        .gform-option-item input[type="radio"],
        .gform-option-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #e11d48;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Section 3: Single-line Main Reason Cards (No icons, number + text in one line) */
        .main-reasons-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        @media (max-width: 820px) {
            .main-reasons-grid {
                grid-template-columns: 1fr;
            }
        }

        .main-reason-card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            min-height: 62px;
        }

        .main-reason-card#overseasCard {
            flex-direction: column;
            align-items: stretch;
            justify-content: center;
        }

        .main-reason-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .main-reason-card:has(input[type="radio"].main-radio-native:checked),
        .main-reason-card.is-selected {
            border-color: #e11d48;
            background: linear-gradient(135deg, #fff5f7 0%, #ffffff 100%);
            box-shadow: 0 6px 20px rgba(225, 29, 72, 0.1);
        }

        .reason-card-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
            padding-right: 16px;
        }

        .reason-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .main-reason-card:has(input[type="radio"].main-radio-native:checked) .reason-badge,
        .main-reason-card.is-selected .reason-badge {
            background: #e11d48;
            color: #ffffff;
        }

        .reason-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.4;
        }

        .main-reason-card input[type="radio"].main-radio-native {
            position: static;
            width: 20px;
            height: 20px;
            accent-color: #e11d48;
            cursor: pointer;
            flex-shrink: 0;
            margin: 0;
        }

        .overseas-subgroup {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1.5px dashed #fecdd3;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .sub-chip-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s;
        }

        .sub-chip-label:hover {
            border-color: #e11d48;
            background: #fff1f2;
        }

        .sub-chip-label:has(input:checked),
        .sub-chip-label.is-selected {
            border-color: #e11d48;
            background: #e11d48;
            color: #ffffff;
        }

        .sub-chip-label input[type="radio"] {
            accent-color: #e11d48;
            width: 14px;
            height: 14px;
        }

        /* Questions 7 & 8 in a Single Row */
        .choice-cards-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 900px) {
            .choice-cards-row {
                grid-template-columns: 1fr;
            }
        }

        .single-line-choice-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 22px 24px;
            height: 100%;
        }

        .single-line-choice-card .question-text {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            flex: 1;
            line-height: 1.45;
        }

        .single-line-options-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .choice-pill-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            background: #ffffff;
            cursor: pointer;
            font-size: 13.5px;
            font-weight: 700;
            color: #334155;
            transition: all 0.2s;
        }

        .choice-pill-label:hover {
            border-color: #e11d48;
            background: #fff1f2;
        }

        .choice-pill-label input[type="radio"] {
            width: 17px;
            height: 17px;
            accent-color: #e11d48;
        }

        /* Terms & Conditions Agreement Card */
        .terms-card {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px 24px;
            transition: all 0.2s ease;
        }

        .terms-card:has(input:checked) {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .terms-checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
        }

        .terms-checkbox {
            width: 22px;
            height: 22px;
            accent-color: #e11d48;
            cursor: pointer;
            flex-shrink: 0;
        }

        .terms-text {
            font-size: 13.5px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.5;
        }

        .terms-modal-trigger {
            color: #e11d48;
            text-decoration: underline;
            font-weight: 700;
            cursor: pointer;
        }

        .terms-modal-trigger:hover {
            color: #be123c;
        }

        /* Modal Styles */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-container {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 680px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: scaleInModal 0.22s ease-out;
        }

        @keyframes scaleInModal {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .modal-close-btn {
            background: none;
            border: none;
            font-size: 26px;
            color: #64748b;
            cursor: pointer;
            padding: 2px 8px;
            border-radius: 8px;
            line-height: 1;
        }

        .modal-close-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            max-height: 55vh;
        }

        .terms-content-box {
            font-size: 13.5px;
            line-height: 1.75;
            color: #334155;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            white-space: pre-line;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 24px;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .btn-terms-accept {
            padding: 10px 22px;
            background: linear-gradient(135deg, #8C0026 0%, #B30031 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 13.5px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-terms-accept:hover {
            background: #66001C;
        }

        .btn-terms-cancel {
            padding: 10px 18px;
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 13.5px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
        }

        .btn-terms-cancel:hover {
            background: #e2e8f0;
        }

        /* Submit Button & Live Validation Notice */
        .btn-submit-survey {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 36px;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(225, 29, 72, 0.35);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            width: 100%;
        }

        .btn-submit-survey:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(225, 29, 72, 0.45);
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
        }

        .btn-submit-survey:active:not(:disabled) { transform: translateY(0); }

        .btn-submit-survey:disabled {
            opacity: 0.55 !important;
            background: #94a3b8 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            transform: none !important;
        }

        .submit-validation-notice {
            font-size: 13px;
            font-weight: 600;
            color: #b45309;
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 8px 18px;
            border-radius: 20px;
            text-align: center;
            max-width: 680px;
            transition: all 0.25s ease;
            line-height: 1.45;
        }

        .submit-validation-notice.is-valid {
            color: #15803d;
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        @media (max-width: 768px) {
            .public-topbar { height: auto; padding: 12px 18px; flex-wrap: wrap; gap: 10px; }
            .public-topbar-logo { height: 38px; }
            .public-topbar-center { order: 3; width: 100%; justify-content: flex-start; }
            .gform-grid-2 { grid-template-columns: 1fr; }
            .single-line-choice-card { flex-direction: column; align-items: flex-start; }
            .single-line-options-wrap { width: 100%; justify-content: flex-start; }
            .gform-card { padding: 24px 20px; }
        }
    </style>
</head>
<body>

<!-- Animated Wave Background -->
<div class="wave-bg-wrapper">
    <div class="top-ambient-wave"></div>
    <div class="bottom-ambient-wave"></div>
    <svg class="ocean-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
        <defs>
            <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
        </defs>
        <g class="floating-waves">
            <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(225, 29, 72, 0.02)" />
            <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(37, 99, 235, 0.03)" />
            <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(244, 63, 94, 0.03)" />
            <use xlink:href="#gentle-wave" x="48" y="7" fill="rgba(255, 255, 255, 0.75)" />
        </g>
    </svg>
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
    17 => 'Having work life balance',
    18 => 'Feeling respected and valued as a unique person',
    19 => 'Overall work environment and existence of ‘Api Culture’',
    20 => 'Approachability and supportiveness of Group HR',
];

$otherReasons = [
    1 => 'Inadequate salary/benefits',
    2 => 'Limited opportunities for growth and career advancement',
    3 => 'Inadequate work-life balance',
    4 => 'Challenging relationship with immediate supervisor',
    5 => 'Insufficient strategic focus from company management',
    6 => 'Unfavourable/unsupportive work culture',
];

$likedAspects = [
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
@endphp

<!-- Top Navigation Bar -->
<header class="public-topbar">
    <div class="public-topbar-left">
        <img src="{{ asset('George_Steuart_Group_Logo.png') }}" alt="George Steuart Group" class="public-topbar-logo">
    </div>

    <div class="public-topbar-center">
        <div class="topbar-expiry-pill">
            <span class="badge-dot"></span>
            <span>Link expires in <strong>{{ $survey->daysRemaining() }} days</strong> ({{ $survey->expires_at->format('M d, Y') }})</span>
        </div>
        <div id="draftSaveBadge" class="topbar-save-pill" style="display: none;">
            <span class="save-dot"></span>
            <span>Progress saved</span>
        </div>
    </div>

    <div class="public-topbar-right">
        <div class="topbar-exit-tag">
            <span style="font-size: 16px;">📝</span>
            <span>Exit Interview</span>
        </div>
    </div>
</header>

<div class="gform-container">
    <form method="POST" action="{{ route('survey.submit', $survey->token) }}" id="exitSurveyForm" class="form-layout">
        @csrf
        <input type="hidden" name="survey_auth_key" value="{{ $surveyAuthKey ?? '' }}">

        <!-- 1. Team Member Details (Read-only / Pre-filled from HR Record) -->
        <div class="gform-card">
            <div class="gform-section-title">
                <span style="display: inline-flex; align-items: center;">
                    <span style="background: #e11d48; color: #fff; font-size: 12px; font-weight: 800; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px;">1</span>
                    Team Member Details
                </span>
            </div>
            <div class="gform-section-desc">
                These details are for office use only.
            </div>

            <div class="gform-grid-3">
                <!-- Full Name -->
                <div class="gform-group">
                    <label class="gform-label">FULL NAME</label>
                    <input type="text" class="gform-input gform-input-disabled" readonly value="{{ $survey->employee_name }}" placeholder="Enter your full name">
                </div>

                <!-- Team Member No -->
                <div class="gform-group">
                    <label class="gform-label">TEAM MEMBER NO</label>
                    <input type="text" class="gform-input gform-input-disabled" readonly value="{{ $survey->employee_id ?: '—' }}">
                </div>

                <!-- Company / Sub-Entity -->
                <div class="gform-group">
                    <label class="gform-label">COMPANY / SUB-ENTITY</label>
                    <input type="text" class="gform-input gform-input-disabled" readonly value="{{ $survey->company->name ?? '—' }}">
                </div>

                <!-- Designation -->
                <div class="gform-group">
                    <label class="gform-label">DESIGNATION</label>
                    <input type="text" class="gform-input gform-input-disabled" readonly value="{{ $survey->designation }}" placeholder="Your job title">
                </div>

                <!-- Division / Section / Department -->
                <div class="gform-group">
                    <label class="gform-label">DIVISION / SECTION / DEPARTMENT</label>
                    <input type="text" class="gform-input gform-input-disabled" readonly value="{{ $survey->section_division ?: ($survey->department ?: '—') }}" placeholder="e.g. Engineering">
                </div>

                <!-- Immediate Manager / Reporting Person -->
                <div class="gform-group">
                    <label class="gform-label">IMMEDIATE MANAGER / REPORTING PERSON</label>
                    <input type="text" class="gform-input gform-input-disabled" readonly value="{{ $survey->supervisor_name ?: ($survey->reporting_manager ?: '—') }}" placeholder="Name of your manager">
                </div>

                <!-- Date of Joining -->
                <div class="gform-group">
                    <label class="gform-label">DATE OF JOINING</label>
                    <input type="text" class="gform-input gform-input-disabled" readonly value="{{ $survey->date_joined ? $survey->date_joined->format('Y-m-d') : '—' }}">
                </div>

                <!-- Date of Resignation -->
                <div class="gform-group">
                    <label class="gform-label">DATE OF RESIGNATION</label>
                    <input type="text" class="gform-input gform-input-disabled" readonly value="{{ $survey->date_of_resignation ? $survey->date_of_resignation->format('Y-m-d') : ($survey->last_working_date ? $survey->last_working_date->format('Y-m-d') : '—') }}">
                </div>

                <!-- Email -->
                <div class="gform-group">
                    <label class="gform-label">EMAIL</label>
                    <input type="email" class="gform-input gform-input-disabled" readonly value="{{ $survey->employee_email ?: '—' }}">
                </div>
            </div>
        </div>

        <!-- 2. Satisfaction Rating -->
        <div class="gform-card rating-matrix-card">
            <div class="gform-section-title">
                <span style="display: inline-flex; align-items: center;">
                    <span style="background: #e11d48; color: #fff; font-size: 12px; font-weight: 800; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px;">2</span>
                    Satisfaction Rating
                </span>
            </div>
            <div class="gform-section-desc">
                Please complete the following based on your experience for each area according to the 5-point scale below.
            </div>

            <div class="rating-table-wrapper">
                <table class="rating-matrix-table">
                    <thead>
                        <tr>
                            <th class="col-area" style="font-size: 11px; font-weight: 700; color: #475569; letter-spacing: 0.5px; text-transform: uppercase; padding: 14px 20px; text-align: left;">SATISFACTION AREA</th>
                            <th style="width: 105px; font-size: 11px; font-weight: 600; color: #334155; text-align: center; line-height: 1.25; padding: 14px 8px;">Highly<br>Satisfied</th>
                            <th style="width: 95px; font-size: 11px; font-weight: 600; color: #334155; text-align: center; padding: 14px 8px;">Satisfied</th>
                            <th style="width: 90px; font-size: 11px; font-weight: 600; color: #334155; text-align: center; padding: 14px 8px;">Average</th>
                            <th style="width: 95px; font-size: 11px; font-weight: 600; color: #334155; text-align: center; padding: 14px 8px;">Dissatisfied</th>
                            <th style="width: 105px; font-size: 11px; font-weight: 600; color: #334155; text-align: center; line-height: 1.25; padding: 14px 8px;">Highly<br>Dissatisfied</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($areas as $num => $areaTitle)
                        <tr class="matrix-row">
                            <td style="padding: 13px 20px; font-size: 13px; font-weight: 500; color: #1e293b;">
                                1.{{ $num }} {{ $areaTitle }}
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

        <!-- Departure Reasons (Sections 3 & 4) -->
        <div class="gform-card">
            <div class="gform-section-title" style="margin-bottom: 4px;">
                Departure Reasons
            </div>
            <div class="gform-section-desc" style="margin-bottom: 24px;">
                Help us understand what led to your decision to leave.
            </div>

            <!-- 3. What is your main reason to leave the company? (Select one) -->
            <div style="margin-bottom: 24px;">
                <div style="display: flex; align-items: center; margin-bottom: 16px;">
                    <span style="background: #e11d48; color: #fff; font-size: 12px; font-weight: 800; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px;">3</span>
                    <span style="font-size: 14px; font-weight: 700; color: #0f172a;">What is your main reason to leave the company? (Select one)</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px; padding-left: 2px;">
                    <!-- Option 1 -->
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 14px; color: #334155;">
                        <input type="radio" name="main_reason_to_leave" value="Joining a local company for a better position/ salary" class="main-reason-radio" {{ old('main_reason_to_leave') == 'Joining a local company for a better position/ salary' ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                        <span>1. Joining a local company for a better position/salary</span>
                    </label>

                    <!-- Option 2: Going oversees -->
                    <div>
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 14px; color: #334155;">
                            <input type="radio" name="main_reason_to_leave" id="overseasRadio" value="Going oversees:" class="main-reason-radio" {{ old('main_reason_to_leave') == 'Going oversees:' ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                            <span>2. Going oversees:</span>
                        </label>

                        <!-- Indented Sub-options -->
                        <div id="overseasSubgroup" style="margin-left: 30px; padding-left: 16px; border-left: 2px solid #e2e8f0; margin-top: 10px; display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13.5px; color: #334155;">
                                <input type="radio" name="overseas_sub_option" value="Overseas job" {{ old('overseas_sub_option') == 'Overseas job' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                                <span>2.1 Overseas job</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13.5px; color: #334155;">
                                <input type="radio" name="overseas_sub_option" value="Higher studies" {{ old('overseas_sub_option') == 'Higher studies' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                                <span>2.2 Higher studies</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13.5px; color: #334155;">
                                <input type="radio" name="overseas_sub_option" value="Migration" {{ old('overseas_sub_option') == 'Migration' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                                <span>2.3 Migration</span>
                            </label>
                        </div>
                    </div>

                    <!-- Option 3 -->
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 14px; color: #334155;">
                        <input type="radio" name="main_reason_to_leave" value="Shifting to another industry/career" class="main-reason-radio" {{ old('main_reason_to_leave') == 'Shifting to another industry/career' ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                        <span>3. Shifting to another industry/career</span>
                    </label>

                    <!-- Option 4 -->
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 14px; color: #334155;">
                        <input type="radio" name="main_reason_to_leave" value="Personal health related reasons" class="main-reason-radio" {{ old('main_reason_to_leave') == 'Personal health related reasons' ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                        <span>4. Personal health related reasons</span>
                    </label>

                    <!-- Option 5 -->
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 14px; color: #334155;">
                        <input type="radio" name="main_reason_to_leave" value="Family or personal commitments/reasons" class="main-reason-radio" {{ old('main_reason_to_leave') == 'Family or personal commitments/reasons' ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                        <span>5. Family or personal commitments/reasons</span>
                    </label>

                    <!-- Option 6 -->
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 14px; color: #334155;">
                        <input type="radio" name="main_reason_to_leave" value="Distance to workplace/ Relocation" class="main-reason-radio" {{ (old('main_reason_to_leave') == 'Distance to workplace/ Relocation' || old('main_reason_to_leave') == 'Distance to workplace/Relocation') ? 'checked' : '' }} required style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                        <span>6. Distance to workplace/Relocation</span>
                    </label>
                </div>
            </div>

            <!-- Divider -->
            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 28px 0;">

            <!-- 4. Any other reasons to leave the company? (Select all that apply) -->
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 16px;">
                    <span style="background: #e11d48; color: #fff; font-size: 12px; font-weight: 800; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px;">4</span>
                    <span style="font-size: 14px; font-weight: 700; color: #0f172a;">Any other reasons to leave the company? (Select all that apply)</span>
                </div>

                <div class="departure-other-reasons-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px 32px; padding-left: 2px;">
                    @foreach($otherReasons as $num => $reason)
                    <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; font-size: 13.5px; color: #334155; line-height: 1.4;">
                        <input type="checkbox" name="other_reasons_to_leave[]" value="{{ $reason }}" {{ is_array(old('other_reasons_to_leave')) && in_array($reason, old('other_reasons_to_leave')) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #e11d48; margin-top: 1px; cursor: pointer; border-radius: 4px;">
                        <span>{{ $num }}. {{ $reason }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 5. What did you like the most about your work experience? -->
        <div class="gform-card">
            <div class="gform-section-title" style="margin-bottom: 6px;">
                <span style="display: inline-flex; align-items: center;">
                    <span style="background: #e11d48; color: #fff; font-size: 12px; font-weight: 800; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px;">5</span>
                    What did you like the most about your work experience? (Select all that apply)
                </span>
            </div>
            <div class="gform-section-desc" style="margin-bottom: 20px;">
                Choose everything that made your time here worthwhile.
            </div>

            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 0 0 20px 0;">

            <div class="departure-other-reasons-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px 32px; padding-left: 2px;">
                @foreach($likedAspects as $num => $aspect)
                <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; font-size: 13.5px; color: #334155; line-height: 1.4;">
                    <input type="checkbox" name="liked_most_aspects[]" value="{{ $aspect }}" {{ is_array(old('liked_most_aspects')) && in_array($aspect, old('liked_most_aspects')) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #e11d48; margin-top: 1px; cursor: pointer; border-radius: 4px;">
                    <span>{{ $num }}. {{ $aspect }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- 6. Open Reflections & Recommendations -->
        <div class="gform-card">
            <div class="gform-section-title" style="margin-bottom: 6px;">
                <span style="display: inline-flex; align-items: center;">
                    <span style="background: #e11d48; color: #fff; font-size: 12px; font-weight: 800; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px;">6</span>
                    Open Reflections & Recommendations
                </span>
            </div>
            <div class="gform-section-desc" style="margin-bottom: 24px;">
                We value your honest feedback. Share as much or as little as you'd like.
            </div>

            <!-- Question 1: Enjoyed most -->
            <div style="margin-bottom: 22px;">
                <label style="display: block; font-size: 13.5px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                    In your own words, what did you enjoy most about working here?
                </label>
                <textarea name="enjoyed_most" class="gform-textarea" placeholder="Share what you valued about your time here..." style="min-height: 90px;">{{ old('enjoyed_most') }}</textarea>
            </div>

            <!-- Question 2: Prevented resignation -->
            <div style="margin-bottom: 22px;">
                <label style="display: block; font-size: 13.5px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                    What would have prevented your resignation?
                </label>
                <textarea name="prevented_resignation" class="gform-textarea" placeholder="What could the company have done differently to keep you?" style="min-height: 90px;">{{ old('prevented_resignation') }}</textarea>
            </div>

            <!-- Questions 3 & 4: Recommend company & Return for future opportunities -->
            <div class="departure-other-reasons-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 22px;">
                <!-- Recommend company -->
                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; background: #fff;">
                    <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 14px; line-height: 1.4;">
                        Would you recommend this company as a good place to work to others?
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <label class="yn-pill-option" style="flex: 1; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 9px 16px; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; background: #fff; transition: all 0.15s;">
                            <input type="radio" name="recommend_company" value="Yes" {{ old('recommend_company') == 'Yes' ? 'checked' : '' }} required style="width: 17px; height: 17px; accent-color: #e11d48; cursor: pointer;">
                            <span style="font-size: 13.5px; font-weight: 600; color: #1e293b;">Yes</span>
                        </label>
                        <label class="yn-pill-option" style="flex: 1; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 9px 16px; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; background: #fff; transition: all 0.15s;">
                            <input type="radio" name="recommend_company" value="No" {{ old('recommend_company') == 'No' ? 'checked' : '' }} required style="width: 17px; height: 17px; accent-color: #e11d48; cursor: pointer;">
                            <span style="font-size: 13.5px; font-weight: 600; color: #1e293b;">No</span>
                        </label>
                    </div>
                </div>

                <!-- Return / Reapply -->
                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; background: #fff;">
                    <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 14px; line-height: 1.4;">
                        Would you be open to return for future opportunities with the company?
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <label class="yn-pill-option" style="flex: 1; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 9px 16px; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; background: #fff; transition: all 0.15s;">
                            <input type="radio" name="open_to_reapply" value="Yes" {{ old('open_to_reapply') == 'Yes' ? 'checked' : '' }} required style="width: 17px; height: 17px; accent-color: #e11d48; cursor: pointer;">
                            <span style="font-size: 13.5px; font-weight: 600; color: #1e293b;">Yes</span>
                        </label>
                        <label class="yn-pill-option" style="flex: 1; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 9px 16px; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; background: #fff; transition: all 0.15s;">
                            <input type="radio" name="open_to_reapply" value="No" {{ old('open_to_reapply') == 'No' ? 'checked' : '' }} required style="width: 17px; height: 17px; accent-color: #e11d48; cursor: pointer;">
                            <span style="font-size: 13.5px; font-weight: 600; color: #1e293b;">No</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Question 5: Other comments -->
            <div>
                <label style="display: block; font-size: 13.5px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                    Any other comments you would like to share?
                </label>
                <textarea name="other_comments" class="gform-textarea" placeholder="Anything else we should know..." style="min-height: 90px;">{{ old('other_comments') }}</textarea>
            </div>
        </div>

        <!-- Declaration & Submit -->
        <input type="hidden" name="team_member_signature" value="{{ $survey->employee_name }}">
        <input type="hidden" name="team_member_signed_date" value="{{ date('Y-m-d') }}">

        <div class="gform-card">
            <div class="gform-section-title" style="margin-bottom: 4px;">
                Declaration & Submit
            </div>
            <div class="gform-section-desc" style="margin-bottom: 24px;">
                Confirm your details before submitting.
            </div>

            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 0 0 20px 0;">

            <label class="terms-checkbox-wrap" for="termsAgreed" style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                <input type="checkbox" name="terms_agreed" id="termsAgreed" value="1" required class="terms-checkbox" {{ old('terms_agreed', $survey->declaration_accepted ? '1' : '') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #e11d48; margin-top: 2px; cursor: pointer; border-radius: 4px;">
                <span class="terms-text" style="font-size: 13.5px; color: #334155; line-height: 1.5;">
                    I hereby confirm that all details provided are true and accurate, and I agree to the <a href="javascript:void(0)" id="openTermsModalBtn" style="color: inherit; text-decoration: underline;">Employee Exit Policy</a>.
                </span>
            </label>
        </div>

        <!-- Submit Button Area -->
        <div style="margin-top: 14px; display: flex; flex-direction: column; align-items: center; gap: 12px;">
            <button type="submit" class="btn-submit-survey" id="btnSubmitSurvey" disabled>
                <span>Submit Confidential Exit Survey</span>
                <span>➔</span>
            </button>
            <div id="submitValidationNotice" class="submit-validation-notice">
                ⏳ Please complete all mandatory sections (*) and accept the Employee Exit Policy to enable submission.
            </div>
            <div style="text-align: center; font-size: 12px; color: #94a3b8;">
                🔒 Your response will be securely stored and your access link will be permanently closed.
            </div>
        </div>
    </form>
</div>

<!-- Terms and Conditions Modal Popup -->
<div id="termsModal" class="modal-backdrop" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 22px;">📜</span>
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">Employee Exit Policy</h3>
                    <div style="font-size: 12px; color: #64748b;">George Steuart Group Corporate Offboarding Policy</div>
                </div>
            </div>
            <button type="button" class="modal-close-btn" id="closeTermsModalBtn">&times;</button>
        </div>
        <div class="modal-body">
            <div class="terms-content-box">{!! nl2br(e($termsAndConditions ?? '')) !!}</div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-terms-accept" id="acceptTermsBtn">
                ✓ I Read & Accept Employee Exit Policy
            </button>
            <button type="button" class="btn-terms-cancel" id="cancelTermsBtn">
                Close
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const STORAGE_KEY = 'gs_exit_survey_draft_{{ $survey->token }}';
    const form = document.getElementById('exitSurveyForm');
    const submitBtn = document.getElementById('btnSubmitSurvey');
    const notice = document.getElementById('submitValidationNotice');
    const draftBadge = document.getElementById('draftSaveBadge');
    const overseasRadio = document.getElementById('overseasRadio');
    const overseasSubgroup = document.getElementById('overseasSubgroup');
    const overseasCard = document.getElementById('overseasCard');

    // Terms Modal elements
    const termsModal = document.getElementById('termsModal');
    const openTermsModalBtn = document.getElementById('openTermsModalBtn');
    const closeTermsModalBtn = document.getElementById('closeTermsModalBtn');
    const cancelTermsBtn = document.getElementById('cancelTermsBtn');
    const acceptTermsBtn = document.getElementById('acceptTermsBtn');
    const termsCheckbox = document.getElementById('termsAgreed');

    let saveTimeout = null;

    // Open terms modal
    if (openTermsModalBtn && termsModal) {
        openTermsModalBtn.addEventListener('click', function (e) {
            e.preventDefault();
            termsModal.style.display = 'flex';
        });
    }

    // Close terms modal
    function closeTermsModal() {
        if (termsModal) termsModal.style.display = 'none';
    }

    if (closeTermsModalBtn) closeTermsModalBtn.addEventListener('click', closeTermsModal);
    if (cancelTermsBtn) cancelTermsBtn.addEventListener('click', closeTermsModal);

    // Accept terms in modal
    if (acceptTermsBtn) {
        acceptTermsBtn.addEventListener('click', function () {
            if (termsCheckbox) {
                termsCheckbox.checked = true;
            }
            closeTermsModal();
            syncCardClasses();
            validateForm();
            saveDraft();
        });
    }

    // Close modal on backdrop click
    if (termsModal) {
        termsModal.addEventListener('click', function (e) {
            if (e.target === termsModal) {
                closeTermsModal();
            }
        });
    }

    // Keep overseas sub-options visible matching questionnaire design
    function syncOverseasVisibility() {
        if (!overseasSubgroup) return;
        overseasSubgroup.style.display = 'flex';
    }

    // Sync active classes on cards/pills
    function syncCardClasses() {
        document.querySelectorAll('.main-reason-card').forEach(card => {
            const radio = card.querySelector('input[type="radio"].main-radio-native');
            if (radio && radio.checked) {
                card.classList.add('is-selected');
            } else {
                card.classList.remove('is-selected');
            }
        });

        document.querySelectorAll('.sub-chip-label').forEach(chip => {
            const radio = chip.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                chip.classList.add('is-selected');
            } else {
                chip.classList.remove('is-selected');
            }
        });

        document.querySelectorAll('.gform-option-item').forEach(item => {
            const input = item.querySelector('input[type="checkbox"], input[type="radio"]');
            if (input && input.checked) {
                item.classList.add('is-selected');
            } else {
                item.classList.remove('is-selected');
            }
        });

        syncOverseasVisibility();
    }

    // Mandatory Form Validation Check
    function validateForm() {
        let isValid = true;
        let missing = [];

        // 1. Matrix: 20 ratings
        let ratedCount = 0;
        for (let i = 1; i <= 20; i++) {
            if (form.querySelector(`input[name="area_ratings[${i}]"]:checked`)) {
                ratedCount++;
            }
        }
        if (ratedCount < 20) {
            isValid = false;
            missing.push(`Experience Assessment Matrix (${ratedCount}/20)`);
        }

        // 2. Main Reason
        const mainReason = form.querySelector('input[name="main_reason_to_leave"]:checked');
        if (!mainReason) {
            isValid = false;
            missing.push('Main Reason');
        } else if (mainReason.value === 'Going oversees:') {
            const sub = form.querySelector('input[name="overseas_sub_option"]:checked');
            if (!sub) {
                isValid = false;
                missing.push('Overseas Sub-Category');
            }
        }

        // 3. Other reasons (at least 1)
        const otherChecked = form.querySelectorAll('input[name="other_reasons_to_leave[]"]:checked');
        if (otherChecked.length === 0) {
            isValid = false;
            missing.push('Other Reasons');
        }

        // 4. Liked aspects (at least 1)
        const likedChecked = form.querySelectorAll('input[name="liked_most_aspects[]"]:checked');
        if (likedChecked.length === 0) {
            isValid = false;
            missing.push('Liked Aspects');
        }

        // 5. Open to reapply
        const reapply = form.querySelector('input[name="open_to_reapply"]:checked');
        if (!reapply) {
            isValid = false;
            missing.push('Reapply (Yes/No)');
        }

        // 6. Recommend company
        const recommend = form.querySelector('input[name="recommend_company"]:checked');
        if (!recommend) {
            isValid = false;
            missing.push('Recommend (Yes/No)');
        }

        // 7. Terms agreed
        if (!termsCheckbox || !termsCheckbox.checked) {
            isValid = false;
            missing.push('Employee Exit Policy Acceptance');
        }

        // Note: enjoyed_most, prevented_resignation, and other_comments are open reflections ("Share as much or as little as you'd like")

        if (submitBtn) {
            submitBtn.disabled = !isValid;
        }

        if (notice) {
            if (isValid) {
                notice.className = 'submit-validation-notice is-valid';
                notice.innerHTML = '✓ All mandatory fields completed. You can now submit your survey.';
            } else {
                notice.className = 'submit-validation-notice';
                notice.innerHTML = `⏳ Incomplete sections detected: <strong>${missing.slice(0, 3).join(', ')}${missing.length > 3 ? ' +' + (missing.length - 3) + ' more' : ''}</strong>`;
            }
        }

        return isValid;
    }

    // Auto-save form draft to localStorage
    function saveDraft() {
        try {
            const draft = {
                area_ratings: {},
                main_reason_to_leave: form.querySelector('input[name="main_reason_to_leave"]:checked')?.value || '',
                overseas_sub_option: form.querySelector('input[name="overseas_sub_option"]:checked')?.value || '',
                other_reasons_to_leave: [],
                liked_most_aspects: [],
                enjoyed_most: form.querySelector('textarea[name="enjoyed_most"]')?.value || '',
                prevented_resignation: form.querySelector('textarea[name="prevented_resignation"]')?.value || '',
                open_to_reapply: form.querySelector('input[name="open_to_reapply"]:checked')?.value || '',
                recommend_company: form.querySelector('input[name="recommend_company"]:checked')?.value || '',
                other_comments: form.querySelector('textarea[name="other_comments"]')?.value || '',
                team_member_signature: form.querySelector('input[name="team_member_signature"]')?.value || '',
                team_member_signed_date: form.querySelector('input[name="team_member_signed_date"]')?.value || '',
                terms_agreed: termsCheckbox ? termsCheckbox.checked : false
            };

            for (let i = 1; i <= 20; i++) {
                const checked = form.querySelector(`input[name="area_ratings[${i}]"]:checked`);
                if (checked) {
                    draft.area_ratings[i] = checked.value;
                }
            }

            form.querySelectorAll('input[name="other_reasons_to_leave[]"]:checked').forEach(cb => {
                draft.other_reasons_to_leave.push(cb.value);
            });

            form.querySelectorAll('input[name="liked_most_aspects[]"]:checked').forEach(cb => {
                draft.liked_most_aspects.push(cb.value);
            });

            localStorage.setItem(STORAGE_KEY, JSON.stringify(draft));

            if (draftBadge) {
                draftBadge.style.display = 'inline-flex';
                clearTimeout(draftBadge._timer);
                draftBadge._timer = setTimeout(() => {
                    draftBadge.style.display = 'none';
                }, 2500);
            }
        } catch (e) {
            console.error('Draft auto-save error:', e);
        }
    }

    // Restore draft from localStorage
    function restoreDraft() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            const draft = JSON.parse(raw);

            // Restore ratings (1-20)
            if (draft.area_ratings) {
                Object.entries(draft.area_ratings).forEach(([num, rating]) => {
                    const el = form.querySelector(`input[name="area_ratings[${num}]"][value="${rating}"]`);
                    if (el) el.checked = true;
                });
            }

            // Restore main reason
            if (draft.main_reason_to_leave) {
                const el = form.querySelector(`input[name="main_reason_to_leave"][value="${draft.main_reason_to_leave}"]`);
                if (el) el.checked = true;
            }

            // Restore overseas sub-option
            if (draft.overseas_sub_option) {
                const el = form.querySelector(`input[name="overseas_sub_option"][value="${draft.overseas_sub_option}"]`);
                if (el) el.checked = true;
            }

            // Restore checkboxes: other reasons
            if (Array.isArray(draft.other_reasons_to_leave)) {
                draft.other_reasons_to_leave.forEach(val => {
                    const el = form.querySelector(`input[name="other_reasons_to_leave[]"][value="${val}"]`);
                    if (el) el.checked = true;
                });
            }

            // Restore checkboxes: liked aspects
            if (Array.isArray(draft.liked_most_aspects)) {
                draft.liked_most_aspects.forEach(val => {
                    const el = form.querySelector(`input[name="liked_most_aspects[]"][value="${val}"]`);
                    if (el) el.checked = true;
                });
            }

            // Restore open to reapply & recommend
            if (draft.open_to_reapply) {
                const el = form.querySelector(`input[name="open_to_reapply"][value="${draft.open_to_reapply}"]`);
                if (el) el.checked = true;
            }
            if (draft.recommend_company) {
                const el = form.querySelector(`input[name="recommend_company"][value="${draft.recommend_company}"]`);
                if (el) el.checked = true;
            }

            // Restore text fields
            if (draft.enjoyed_most) {
                const el = form.querySelector('textarea[name="enjoyed_most"]');
                if (el && !el.value) el.value = draft.enjoyed_most;
            }
            if (draft.prevented_resignation) {
                const el = form.querySelector('textarea[name="prevented_resignation"]');
                if (el && !el.value) el.value = draft.prevented_resignation;
            }
            if (draft.other_comments) {
                const el = form.querySelector('textarea[name="other_comments"]');
                if (el && !el.value) el.value = draft.other_comments;
            }
            if (draft.team_member_signature) {
                const el = form.querySelector('input[name="team_member_signature"]');
                if (el && !el.value) el.value = draft.team_member_signature;
            }
            if (draft.team_member_signed_date) {
                const el = form.querySelector('input[name="team_member_signed_date"]');
                if (el && !el.value) el.value = draft.team_member_signed_date;
            }

            // Restore terms agreed: Auto-selected when declaration was accepted
            const declarationAccepted = {{ $survey->declaration_accepted ? 'true' : 'false' }};
            if (termsCheckbox) {
                if (declarationAccepted) {
                    termsCheckbox.checked = true;
                } else if (draft && draft.terms_agreed !== undefined) {
                    termsCheckbox.checked = !!draft.terms_agreed;
                }
            }

            syncCardClasses();
        } catch (e) {
            console.error('Draft restore error:', e);
        }
    }

    // Selecting sub-option auto-selects Option 2 ("Going oversees:")
    document.querySelectorAll('input[name="overseas_sub_option"]').forEach(subRadio => {
        subRadio.addEventListener('change', function () {
            if (overseasRadio) {
                overseasRadio.checked = true;
                syncCardClasses();
                validateForm();
                saveDraft();
            }
        });
    });

    // Uncheck sub-option when another main reason is selected
    document.querySelectorAll('input[name="main_reason_to_leave"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value !== 'Going oversees:') {
                document.querySelectorAll('input[name="overseas_sub_option"]').forEach(sub => sub.checked = false);
            }
            syncCardClasses();
            validateForm();
            saveDraft();
        });
    });

    // Listen to changes across form for auto-save and live validation
    form.addEventListener('change', function () {
        syncCardClasses();
        validateForm();
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveDraft, 200);
    });

    form.addEventListener('input', function (e) {
        if (e.target.tagName === 'TEXTAREA' || e.target.tagName === 'INPUT') {
            validateForm();
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(saveDraft, 500);
        }
    });

    // Clear saved draft once form is submitted successfully
    form.addEventListener('submit', function (e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
        localStorage.removeItem(STORAGE_KEY);
    });

    // Run restore, class sync, and validation check on initial render
    restoreDraft();
    syncCardClasses();
    validateForm();
});
</script>
</body>
</html>
