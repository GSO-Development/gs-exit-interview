<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Exit Interview Portal') — George Steuart Group</title>
    <meta name="description" content="George Steuart Group — Exit Interview & Intelligence System">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #8C0026;
            --primary-light: #B30031;
            --primary-dark: #66001C;
            --accent: #c9993a;
            --accent-light: #f0c060;
            --bg: #f3f6f9;
            --surface: #ffffff;
            --sidebar-bg: #0c1322;
            --sidebar-text: #94a3b8;
            --text: #1a202c;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: 270px; min-height: 100vh; background: var(--sidebar-bg);
            display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50;
            box-shadow: 4px 0 24px rgba(0,0,0,0.25);
        }
        .sidebar-brand {
            padding: 24px 22px 20px;
            display: flex; align-items: center; gap: 12px;
        }
        .brand-icon-shield {
            width: 28px; height: 28px; color: #ef4444; flex-shrink: 0;
        }
        .brand-title-wrap {
            font-size: 19px; font-weight: 700; color: #ffffff; letter-spacing: -0.3px;
        }
        .sidebar-nav { flex: 1; padding: 12px 14px; overflow-y: auto; }
        .nav-section-label {
            font-size: 11px; font-weight: 700; letter-spacing: 1.2px; color: #64748b;
            text-transform: uppercase; padding: 14px 10px 8px;
        }
        .nav-link {
            display: flex; align-items: center; gap: 12px; padding: 12px 14px;
            color: #94a3b8; text-decoration: none; font-size: 14px; font-weight: 500;
            transition: all 0.18s ease-in-out; border-radius: 12px; margin: 3px 0;
        }
        .nav-link:hover { background: rgba(255,255,255,0.06); color: #f8fafc; }
        .nav-link.active {
            background: linear-gradient(135deg, #8C0026 0%, #66001C 100%); color: #ffffff; font-weight: 600;
            box-shadow: 0 2px 10px rgba(140, 0, 38, 0.4);
        }
        .nav-link.active .nav-svg-icon { color: #ffffff; }
        .nav-svg-icon { width: 19px; height: 19px; flex-shrink: 0; }
        .sidebar-footer {
            padding: 16px;
        }
        .user-card {
            background: #141d2f; border-radius: 14px; padding: 12px 14px;
            display: flex; align-items: center; gap: 12px;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .user-avatar-red {
            width: 38px; height: 38px; border-radius: 50%; background: #dc2626;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #ffffff; flex-shrink: 0;
        }
        .user-details { overflow: hidden; min-width: 0; flex: 1; }
        .user-details .name {
            font-size: 13px; font-weight: 600; color: #ffffff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .user-details .email {
            font-size: 11px; color: #8da2be; margin-top: 1px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .user-logout-btn {
            background: none; border: none; color: #94a3b8; cursor: pointer;
            padding: 4px; border-radius: 6px; display: flex; align-items: center;
            transition: color 0.2s;
        }
        .user-logout-btn:hover { color: #ef4444; }

        /* ── Main ── */
        .main { margin-left: 270px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar {
            background: var(--surface); border-bottom: 1px solid var(--border);
            padding: 0 32px; height: 64px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40; box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }
        .topbar-title { font-size: 18px; font-weight: 700; color: var(--primary); }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }

        /* ── Content ── */
        .content { padding: 32px; flex: 1; }

        /* ── Components ── */
        .btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
            border-radius: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer;
            border: none; text-decoration: none; transition: all 0.2s; white-space: nowrap;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-light); box-shadow: 0 4px 12px rgba(140,0,38,0.3); transform: translateY(-1px); }
        .btn-accent { background: var(--accent); color: #fff; }
        .btn-accent:hover { background: var(--accent-light); color: var(--text); }
        .btn-ghost { background: transparent; color: var(--text-muted); border: 1.5px solid var(--border); }
        .btn-ghost:hover { background: var(--bg); color: var(--text); }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 6px 14px; font-size: 12px; }
        .btn-xs { padding: 4px 10px; font-size: 11.5px; border-radius: 6px; }

        .card {
            background: var(--surface); border-radius: 12px; border: 1px solid var(--border);
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        .card-header {
            padding: 20px 24px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-size: 15px; font-weight: 700; color: var(--primary); }
        .card-body { padding: 24px; }

        /* KPI Cards */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
        .kpi-card {
            background: var(--surface); border-radius: 14px; padding: 24px;
            border: 1px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            position: relative; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;
        }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .kpi-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--kpi-color, var(--primary)), var(--kpi-color2, var(--primary-light)));
        }
        .kpi-icon { font-size: 28px; margin-bottom: 12px; }
        .kpi-value { font-size: 36px; font-weight: 800; color: var(--primary); line-height: 1; margin-bottom: 6px; }
        .kpi-label { font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px; }
        .kpi-sub { font-size: 12px; color: var(--text-muted); }
        .kpi-sub .up { color: var(--success); font-weight: 600; }
        .kpi-sub .warn { color: var(--warning); font-weight: 600; }

        /* Tables */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        thead th {
            background: #f8fafc; padding: 12px 16px; text-align: left;
            font-size: 11px; font-weight: 700; letter-spacing: 0.5px;
            text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--border);
        }
        tbody td { padding: 14px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #f8fafc; }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px;
            border-radius: 20px; font-size: 11px; font-weight: 600;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-muted { background: #f1f5f9; color: #64748b; }
        .badge-info { background: #dbeafe; color: #1e40af; }

        /* Alerts */
        .alert { padding: 14px 18px; border-radius: 10px; font-size: 13.5px; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* Star ratings */
        .stars { color: var(--accent); letter-spacing: 2px; font-size: 15px; }
        .stars-grey { color: #cbd5e1; }

        /* Tabs */
        .tabs { display: flex; gap: 0; border-bottom: 2px solid var(--border); margin-bottom: 24px; }
        .tab-btn {
            padding: 12px 24px; font-size: 13.5px; font-weight: 600; color: var(--text-muted);
            background: none; border: none; cursor: pointer; border-bottom: 2px solid transparent;
            margin-bottom: -2px; transition: all 0.2s; display: flex; align-items: center; gap: 8px;
        }
        .tab-btn:hover { color: var(--primary); }
        .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }

        /* Modal */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
            z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px;
        }
        .modal {
            background: var(--surface); border-radius: 16px; width: 100%; max-width: 640px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto;
        }
        .modal-header {
            padding: 24px 28px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; background: var(--surface); border-radius: 16px 16px 0 0; z-index: 10;
        }
        .modal-title { font-size: 17px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 10px; }
        .modal-body { padding: 28px; }
        .modal-footer { padding: 20px 28px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; }
        .modal-close { background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 20px; line-height: 1; padding: 4px; border-radius: 6px; transition: color 0.2s; }
        .modal-close:hover { color: var(--danger); }

        /* Forms */
        .form-group { margin-bottom: 20px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; letter-spacing: 0.3px; text-transform: uppercase; }
        .form-control {
            width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;
            font-size: 13.5px; font-family: 'Inter', sans-serif; color: var(--text);
            background: var(--surface); transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(42,82,152,0.1); }
        .form-control::placeholder { color: #94a3b8; }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 5px; }
        select.form-control { cursor: pointer; }

        /* Charts placeholder */
        .chart-area { background: linear-gradient(135deg, #f0f4f8, #e8eef4); border-radius: 8px; min-height: 180px; display: flex; align-items: center; justify-content: center; }

        /* Progress bars */
        .progress { background: #e2e8f0; border-radius: 10px; height: 8px; overflow: hidden; }
        .progress-bar { height: 100%; border-radius: 10px; background: linear-gradient(90deg, var(--primary), var(--primary-light)); transition: width 0.6s ease; }
        .progress-bar.accent { background: linear-gradient(90deg, var(--accent), #e8b44a); }
        .progress-bar.success { background: linear-gradient(90deg, #10b981, #34d399); }
        .progress-bar.danger { background: linear-gradient(90deg, #ef4444, #f87171); }

        /* Responsive */
        @media (max-width: 1200px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .kpi-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <svg class="brand-icon-shield" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <path d="M9 12l2 2 4-4"></path>
            </svg>
            <div class="brand-title-wrap">ExitPulse HR</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">MAIN MENU</div>

            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="nav-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <svg class="nav-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                    <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                </svg>
                <span>Reports & Insights</span>
            </a>

            <a href="{{ route('admin.surveys.index') }}" class="nav-link {{ request()->routeIs('admin.surveys.*') ? 'active' : '' }}">
                <svg class="nav-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <path d="M9 12h6"></path>
                    <path d="M9 16h6"></path>
                </svg>
                <span>Gather Data & Surveys</span>
            </a>

            <a href="{{ route('admin.templates.index') }}" class="nav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                <svg class="nav-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="3" y1="9" x2="21" y2="9"></line>
                    <line x1="9" y1="21" x2="9" y2="9"></line>
                </svg>
                <span>System Surveys</span>
            </a>

            @if(auth()->user()->hasRole('super_admin'))
            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <svg class="nav-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                <span>Settings</span>
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar-red">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="user-details">
                    <div class="name" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</div>
                    <div class="email" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin-left: auto;">
                    @csrf
                    <button type="submit" class="user-logout-btn" title="Sign out">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="main">
        <header class="topbar">
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
            <div class="topbar-actions">
                @yield('topbar-actions')
                <button type="button" class="btn btn-accent" id="globalSendSurveyBtn" onclick="openSendSurveyModal()" style="display:inline-flex;align-items:center;gap:8px;">
                    <span>✉️</span>
                    <span>Send New Survey</span>
                </button>
            </div>
        </header>

        <main class="content">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">⚠️ {{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Global Send Exit Survey Invitation Modal -->
    <div id="sendSurveyModal" class="modal-overlay" style="display:none" onclick="if(event.target===this) closeSendSurveyModal()">
        <div class="modal" style="max-width: 880px; width: 95%; max-height: 94vh;">
            <div class="modal-header" style="padding: 18px 24px 14px;">
                <div class="modal-title">
                    <span style="font-size:18px;">📤</span>
                    <span>Send Exit Survey Invitation</span>
                    <span id="modalStepBadge" style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:12px;background:#eef2f6;color:#475569;margin-left:8px;">Step 1 of 2: Details</span>
                </div>
                <button type="button" class="modal-close" onclick="closeSendSurveyModal()">✕</button>
            </div>
            <form method="POST" action="{{ route('admin.surveys.store') }}" id="sendSurveyForm">
                @csrf

                <!-- STEP 1: Enter Details -->
                <div id="surveyModalStep1">
                    <div class="modal-body" style="padding: 18px 24px 14px;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px 16px;">
                            <!-- 1. Full Name -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="global_employee_name" style="margin-bottom:4px;font-size:11.5px;">Employee Full Name *</label>
                                <input type="text" id="global_employee_name" name="employee_name" class="form-control" style="padding:8px 12px;font-size:13px;" placeholder="e.g. Kamal Perera" required value="{{ old('employee_name') }}">
                                @error('employee_name')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <!-- 2. Email Address with Live Duplicate Check -->
                            <div class="form-group" style="margin-bottom:0;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                                    <label class="form-label" for="global_employee_email" style="margin-bottom:0;font-size:11.5px;">Personal Email *</label>
                                    <span id="email_dup_spinner" style="display:none;font-size:11px;color:#8C0026;font-weight:600;">Checking...</span>
                                </div>
                                <input type="email" id="global_employee_email" name="employee_email" class="form-control" style="padding:8px 12px;font-size:13px;" placeholder="e.g. kamal.perera@gmail.com" required value="{{ old('employee_email') }}" autocomplete="off">
                                <div id="email_dup_feedback" style="display:none;margin-top:5px;font-size:11px;padding:6px 10px;border-radius:6px;line-height:1.35;"></div>
                                @error('employee_email')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <!-- 3. EPF / Staff ID with Live Duplicate Check -->
                            <div class="form-group" style="margin-bottom:0;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                                    <label class="form-label" for="global_employee_id" style="margin-bottom:0;font-size:11.5px;">EPF / Staff ID</label>
                                    <span id="empid_dup_spinner" style="display:none;font-size:11px;color:#8C0026;font-weight:600;">Checking...</span>
                                </div>
                                <input type="text" id="global_employee_id" name="employee_id" class="form-control" style="padding:8px 12px;font-size:13px;" placeholder="e.g. EMP-1042" value="{{ old('employee_id') }}" autocomplete="off">
                                <div id="empid_dup_feedback" style="display:none;margin-top:5px;font-size:11px;padding:6px 10px;border-radius:6px;line-height:1.35;"></div>
                            </div>

                            <!-- 4. Subsidiary Company -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="global_company_id" style="margin-bottom:4px;font-size:11.5px;">Subsidiary Company *</label>
                                <select id="global_company_id" name="company_id" class="form-control" style="padding:8px 12px;font-size:13px;" required>
                                    <option value="">Select Company</option>
                                    @php
                                        $globalCompanies = \App\Models\Company::where('is_active', true)
                                            ->when(auth()->check() && auth()->user()->accessibleCompanyIds() !== null, function($q) {
                                                $q->whereIn('id', auth()->user()->accessibleCompanyIds());
                                            })
                                            ->orderBy('name')
                                            ->get();
                                    @endphp
                                    @foreach($globalCompanies as $comp)
                                        <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <!-- 5. Department -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="global_department" style="margin-bottom:4px;font-size:11.5px;">Department *</label>
                                <input type="text" id="global_department" name="department" class="form-control" style="padding:8px 12px;font-size:13px;" placeholder="e.g. Engineering" required value="{{ old('department') }}">
                                @error('department')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <!-- 6. Designation -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="global_designation" style="margin-bottom:4px;font-size:11.5px;">Designation *</label>
                                <input type="text" id="global_designation" name="designation" class="form-control" style="padding:8px 12px;font-size:13px;" placeholder="e.g. Software Engineer" required value="{{ old('designation') }}">
                                @error('designation')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <!-- 7. Reporting Manager -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="global_reporting_manager" style="margin-bottom:4px;font-size:11.5px;">Reporting Manager</label>
                                <input type="text" id="global_reporting_manager" name="reporting_manager" class="form-control" style="padding:8px 12px;font-size:13px;" placeholder="e.g. Ruwan Jayasinghe" value="{{ old('reporting_manager') }}">
                            </div>

                            <!-- 8. Date of Joining -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="global_date_joined" style="margin-bottom:4px;font-size:11.5px;">Date of Joining</label>
                                <input type="date" id="global_date_joined" name="date_joined" class="form-control" style="padding:8px 12px;font-size:13px;" value="{{ old('date_joined') }}">
                            </div>

                            <!-- 9. Last Working Date -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="global_last_working_date" style="margin-bottom:4px;font-size:11.5px;">Last Working Date</label>
                                <input type="date" id="global_last_working_date" name="last_working_date" class="form-control" style="padding:8px 12px;font-size:13px;" value="{{ old('last_working_date') }}">
                            </div>

                            <!-- 10. Token Validity (Days) -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="global_token_validity_days" style="margin-bottom:4px;font-size:11.5px;">Token Validity (Days)</label>
                                <input type="number" id="global_token_validity_days" name="token_validity_days" class="form-control" style="padding:8px 12px;font-size:13px;" value="{{ old('token_validity_days', 14) }}" min="1" max="90">
                            </div>

                            <!-- Notice Tip (spans 2 columns) -->
                            <div style="grid-column: span 2; display: flex; align-items: flex-end;">
                                <div style="font-size:11.5px;color:#64748b;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;width:100%;line-height:1.4;">
                                    💡 <strong>Notice:</strong> Passcode and secure survey link are generated once confirmed. Previous records are automatically verified above.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 12px 24px;">
                        <button type="button" class="btn btn-ghost" onclick="closeSendSurveyModal()">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="goToSurveyReview()" style="display:inline-flex;align-items:center;gap:6px;">
                            <span>Next: Review Details</span>
                            <span>➔</span>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Review Details & Confirmation -->
                <div id="surveyModalStep2" style="display:none;">
                    <div class="modal-body" style="padding: 18px 24px 14px;">
                        <div id="sendSurveyErrorBanner" style="display:none;background:#fee2e2;border:1.5px solid #fca5a5;border-radius:10px;padding:10px 14px;color:#991b1b;font-size:12px;margin-bottom:14px;line-height:1.4;"></div>
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 14px;display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                            <span style="font-size:18px;">🔍</span>
                            <div style="font-size:12px;color:#1e40af;line-height:1.35;">
                                <strong>Please review details before issuing token.</strong> Verify the employee information and email address.
                            </div>
                        </div>

                        <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:16px 20px;display:grid;grid-template-columns:repeat(3, 1fr);gap:12px 16px;">
                            <div>
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Employee Full Name</div>
                                <div id="rev_name" style="font-size:13.5px;font-weight:700;color:#8C0026;margin-top:2px;">—</div>
                            </div>
                            <div>
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Personal Email</div>
                                <div id="rev_email" style="font-size:13px;font-weight:600;color:#0f172a;margin-top:2px;word-break:break-all;">—</div>
                            </div>
                            <div>
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">EPF / Staff ID</div>
                                <div id="rev_employee_id" style="font-size:13px;font-weight:600;color:#475569;margin-top:2px;">—</div>
                            </div>

                            <div>
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Subsidiary Company</div>
                                <div id="rev_company" style="font-size:13px;font-weight:600;color:#8C0026;margin-top:2px;">—</div>
                            </div>
                            <div>
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Department</div>
                                <div id="rev_department" style="font-size:13px;font-weight:600;color:#0f172a;margin-top:2px;">—</div>
                            </div>
                            <div>
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Designation</div>
                                <div id="rev_designation" style="font-size:13px;font-weight:600;color:#0f172a;margin-top:2px;">—</div>
                            </div>

                            <div>
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Reporting Manager</div>
                                <div id="rev_manager" style="font-size:13px;font-weight:500;color:#475569;margin-top:2px;">—</div>
                            </div>
                            <div>
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Date of Joining</div>
                                <div id="rev_joined" style="font-size:13px;font-weight:500;color:#475569;margin-top:2px;">—</div>
                            </div>
                            <div>
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Last Working Date</div>
                                <div id="rev_leaving" style="font-size:13px;font-weight:500;color:#475569;margin-top:2px;">—</div>
                            </div>

                            <div style="grid-column: span 3; border-top: 1px solid #e2e8f0; padding-top: 10px; display: flex; align-items: center; justify-content: space-between;">
                                <div style="font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Link Token Validity</div>
                                <div id="rev_validity" style="font-size:13px;font-weight:700;color:#059669;">—</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 12px 24px; display:flex; justify-content:space-between; align-items:center;">
                        <button type="button" class="btn btn-ghost" onclick="backToSurveyStep1()" style="display:inline-flex;align-items:center;gap:6px;">
                            <span>←</span>
                            <span>Back &amp; Edit</span>
                        </button>
                        <button type="submit" id="submitSendSurveyBtn" class="btn btn-accent" style="display:inline-flex;align-items:center;gap:8px;">
                            <span>✉️</span>
                            <span>Generate &amp; Send Link</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function goToSurveyReview() {
            var nameInput = document.getElementById('global_employee_name');
            var emailInput = document.getElementById('global_employee_email');
            var companySelect = document.getElementById('global_company_id');
            var deptInput = document.getElementById('global_department');
            var desigInput = document.getElementById('global_designation');

            if (!nameInput.checkValidity()) {
                nameInput.reportValidity();
                return;
            }
            if (!emailInput.checkValidity()) {
                emailInput.reportValidity();
                return;
            }
            if (!companySelect.checkValidity() || !companySelect.value) {
                companySelect.reportValidity();
                return;
            }
            if (!deptInput.checkValidity()) {
                deptInput.reportValidity();
                return;
            }
            if (!desigInput.checkValidity()) {
                desigInput.reportValidity();
                return;
            }

            // Populate Review Table
            document.getElementById('rev_name').textContent = nameInput.value.trim() || '—';
            document.getElementById('rev_email').textContent = emailInput.value.trim() || '—';
            
            var compText = companySelect.options[companySelect.selectedIndex] ? companySelect.options[companySelect.selectedIndex].text : '—';
            document.getElementById('rev_company').textContent = compText;
            document.getElementById('rev_department').textContent = deptInput.value.trim() || '—';
            document.getElementById('rev_designation').textContent = desigInput.value.trim() || '—';
            document.getElementById('rev_employee_id').textContent = document.getElementById('global_employee_id').value.trim() || 'Not specified';
            document.getElementById('rev_manager').textContent = document.getElementById('global_reporting_manager').value.trim() || 'Not specified';
            document.getElementById('rev_joined').textContent = document.getElementById('global_date_joined').value || 'Not specified';
            document.getElementById('rev_leaving').textContent = document.getElementById('global_last_working_date').value || 'Not specified';
            
            var days = document.getElementById('global_token_validity_days').value || '14';
            document.getElementById('rev_validity').textContent = days + ' Days (Active Link)';

            // Switch to Step 2
            document.getElementById('surveyModalStep1').style.display = 'none';
            document.getElementById('surveyModalStep2').style.display = 'block';
            
            var badge = document.getElementById('modalStepBadge');
            if (badge) {
                badge.textContent = 'Step 2 of 2: Review';
                badge.style.background = '#fef3c7';
                badge.style.color = '#92400e';
            }
        }

        function backToSurveyStep1() {
            document.getElementById('surveyModalStep2').style.display = 'none';
            document.getElementById('surveyModalStep1').style.display = 'block';
            
            var badge = document.getElementById('modalStepBadge');
            if (badge) {
                badge.textContent = 'Step 1 of 2: Details';
                badge.style.background = '#eef2f6';
                badge.style.color = '#475569';
            }
        }

        function openSendSurveyModal() {
            var modal = document.getElementById('sendSurveyModal');
            if (modal) {
                backToSurveyStep1();
                modal.style.display = 'flex';
                var firstInput = modal.querySelector('input[name="employee_name"]');
                if (firstInput) {
                    setTimeout(function() { firstInput.focus(); }, 60);
                }
            }
        }

        function closeSendSurveyModal() {
            var modal = document.getElementById('sendSurveyModal');
            if (modal) {
                modal.style.display = 'none';
                backToSurveyStep1();
                var ef = document.getElementById('email_dup_feedback');
                var mf = document.getElementById('empid_dup_feedback');
                if (ef) { ef.style.display = 'none'; ef.innerHTML = ''; }
                if (mf) { mf.style.display = 'none'; mf.innerHTML = ''; }
            }
        }

        window.openGlobalSendSurveyModal = openSendSurveyModal;
        window.closeGlobalSendSurveyModal = closeSendSurveyModal;

        // ── Real-time Duplicate Check for Email and Staff ID ─────────────────
        var checkDupTimerEmail = null;
        var checkDupTimerEmpId = null;

        function performDuplicateCheck(type, value, feedbackElem, spinnerElem) {
            if (!feedbackElem) return;

            if (!value || (type === 'email' && (value.length < 5 || value.indexOf('@') === -1)) || (type === 'employee_id' && value.length < 2)) {
                feedbackElem.style.display = 'none';
                feedbackElem.innerHTML = '';
                if (spinnerElem) spinnerElem.style.display = 'none';
                return;
            }

            if (spinnerElem) spinnerElem.style.display = 'inline';

            var checkUrl = "{{ route('admin.surveys.check-duplicate') }}?type=" + encodeURIComponent(type) + "&value=" + encodeURIComponent(value);

            fetch(checkUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (spinnerElem) spinnerElem.style.display = 'none';

                if (data && data.found) {
                    feedbackElem.style.display = 'block';
                    if (data.status === 'submitted') {
                        feedbackElem.style.background = '#fff1f2';
                        feedbackElem.style.border = '1.5px solid #fecdd3';
                        feedbackElem.style.color = '#9f1239';
                        feedbackElem.innerHTML = '⚠️ <strong>Survey Already Submitted:</strong><br>' + 
                            escapeHtml(data.details.employee_name) + ' (' + escapeHtml(data.details.company_name) + ') completed on ' + escapeHtml(data.details.date);
                    } else if (data.status === 'pending') {
                        feedbackElem.style.background = '#eff6ff';
                        feedbackElem.style.border = '1.5px solid #bfdbfe';
                        feedbackElem.style.color = '#1e40af';
                        feedbackElem.innerHTML = 'ℹ️ <strong>Active Invitation Exists:</strong><br>' + 
                            'Sent to ' + escapeHtml(data.details.employee_name) + ' on ' + escapeHtml(data.details.date) + ' (Expires ' + escapeHtml(data.details.expires_at) + ')';
                    } else {
                        feedbackElem.style.background = '#fefce8';
                        feedbackElem.style.border = '1.5px solid #fef08a';
                        feedbackElem.style.color = '#854d0e';
                        feedbackElem.innerHTML = '⌛ <strong>Previous Link ' + escapeHtml(data.details.status_label || data.status) + ':</strong><br>' + 
                            'Recorded for ' + escapeHtml(data.details.employee_name) + ' on ' + escapeHtml(data.details.date);
                    }
                } else {
                    feedbackElem.style.display = 'block';
                    feedbackElem.style.background = '#f0fdf4';
                    feedbackElem.style.border = '1px solid #bbf7d0';
                    feedbackElem.style.color = '#166534';
                    feedbackElem.innerHTML = '✓ No prior records found (' + (type === 'email' ? 'clean email' : 'clean ID') + ')';
                    
                    setTimeout(function() {
                        if (feedbackElem.innerHTML.indexOf('No prior records') !== -1) {
                            feedbackElem.style.display = 'none';
                        }
                    }, 3500);
                }
            })
            .catch(function(err) {
                if (spinnerElem) spinnerElem.style.display = 'none';
                console.error('Check duplicate error:', err);
            });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>"']/g, function(m) {
                return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[m];
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var emailInput = document.getElementById('global_employee_email');
            var emailFeedback = document.getElementById('email_dup_feedback');
            var emailSpinner = document.getElementById('email_dup_spinner');

            if (emailInput && emailFeedback) {
                emailInput.addEventListener('input', function() {
                    clearTimeout(checkDupTimerEmail);
                    var val = emailInput.value.trim();
                    checkDupTimerEmail = setTimeout(function() {
                        performDuplicateCheck('email', val, emailFeedback, emailSpinner);
                    }, 350);
                });
                emailInput.addEventListener('blur', function() {
                    clearTimeout(checkDupTimerEmail);
                    performDuplicateCheck('email', emailInput.value.trim(), emailFeedback, emailSpinner);
                });
            }

            var empIdInput = document.getElementById('global_employee_id');
            var empIdFeedback = document.getElementById('empid_dup_feedback');
            var empIdSpinner = document.getElementById('empid_dup_spinner');

            if (empIdInput && empIdFeedback) {
                empIdInput.addEventListener('input', function() {
                    clearTimeout(checkDupTimerEmpId);
                    var val = empIdInput.value.trim();
                    checkDupTimerEmpId = setTimeout(function() {
                        performDuplicateCheck('employee_id', val, empIdFeedback, empIdSpinner);
                    }, 350);
                });
                empIdInput.addEventListener('blur', function() {
                    clearTimeout(checkDupTimerEmpId);
                    performDuplicateCheck('employee_id', empIdInput.value.trim(), empIdFeedback, empIdSpinner);
                });
            }
        });

        // ── AJAX Form Submission for Send Survey ─────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('sendSurveyForm');
            var submitBtn = document.getElementById('submitSendSurveyBtn');
            var submitErrorBanner = document.getElementById('sendSurveyErrorBanner');

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span>⏳</span> <span>Generating &amp; Sending Link...</span>';
                    }
                    if (submitErrorBanner) {
                        submitErrorBanner.style.display = 'none';
                        submitErrorBanner.innerHTML = '';
                    }

                    var formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) {
                        if (!res.ok) {
                            return res.json().then(function(errData) {
                                throw errData;
                            });
                        }
                        return res.json();
                    })
                    .then(function(data) {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<span>✉️</span> <span>Generate &amp; Send Link</span>';
                        }

                        // Close the create survey modal
                        closeSendSurveyModal();

                        // Reset input form
                        form.reset();
                        backToSurveyStep1();

                        // Display the generated URL & Passcode Popup Modal!
                        if (data && data.survey_generated) {
                            showSurveyGeneratedModal(data.survey_generated);
                        }
                    })
                    .catch(function(err) {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<span>✉️</span> <span>Generate &amp; Send Link</span>';
                        }

                        var msg = 'Failed to generate survey link. Please check your inputs.';
                        if (err && err.message) {
                            msg = err.message;
                        } else if (err && err.errors) {
                            var errList = [];
                            for (var k in err.errors) {
                                if (err.errors[k]) {
                                    errList.push(err.errors[k].join(' '));
                                }
                            }
                            if (errList.length > 0) {
                                msg = errList.join('<br>');
                            }
                        }

                        if (submitErrorBanner) {
                            submitErrorBanner.style.display = 'block';
                            submitErrorBanner.innerHTML = '⚠️ <strong>Submission Error:</strong><br>' + msg;
                        } else {
                            alert('Error: ' + msg);
                        }
                    });
                });
            }
        });

        // ── Survey Generated Success Modal Functions ─────────────────────────
        function showSurveyGeneratedModal(data) {
            var modal = document.getElementById('surveyGeneratedModal');
            if (!modal) return;

            document.getElementById('genEmployeeName').textContent = data.employee_name || '—';
            document.getElementById('genEmployeeDetails').innerHTML = escapeHtml(data.employee_email || '') + ' &bull; ' + escapeHtml(data.company_name || 'George Steuart Group');
            document.getElementById('genValidityText').textContent = 'Valid for ' + (data.validity_days || 14) + ' days';
            document.getElementById('genSurveyLinkInput').value = data.survey_url || '';
            document.getElementById('genAccessCode').textContent = data.access_code || '';

            var mailNotice = document.getElementById('genMailNotice');
            if (mailNotice) {
                if (data.mail_sent) {
                    mailNotice.style.background = '#ecfdf5';
                    mailNotice.style.border = '1px solid #a7f3d0';
                    mailNotice.style.color = '#065f46';
                    mailNotice.innerHTML = '<span style="font-size:16px;">✉️</span> <span>An invitation email with this link and passcode was successfully sent to <strong>' + escapeHtml(data.employee_email) + '</strong>.</span>';
                } else {
                    mailNotice.style.background = '#fffbeb';
                    mailNotice.style.border = '1px solid #fde68a';
                    mailNotice.style.color = '#92400e';
                    mailNotice.innerHTML = '<span style="font-size:16px;">⚠️</span> <span>Email delivery could not be verified. You can copy the link and passcode above and share them directly with the candidate.</span>';
                }
            }

            modal.style.display = 'flex';
        }

        function closeSurveyGeneratedModal() {
            var modal = document.getElementById('surveyGeneratedModal');
            if (modal) {
                modal.style.display = 'none';
            }
            // If on surveys index page, reload to reflect new sent item in list
            if (window.location.pathname.indexOf('/admin/surveys') !== -1) {
                window.location.reload();
            }
        }

        function copyGenSurveyLink(btn) {
            var input = document.getElementById('genSurveyLinkInput');
            if (input) {
                input.select();
                navigator.clipboard.writeText(input.value).then(function() {
                    var originalText = btn.innerHTML;
                    btn.innerHTML = '✓ Copied!';
                    btn.style.background = '#10b981';
                    setTimeout(function() {
                        btn.innerHTML = originalText;
                        btn.style.background = '';
                    }, 2000);
                });
            }
        }

        function copyGenPasscode(btn) {
            var codeElem = document.getElementById('genAccessCode');
            var code = codeElem ? codeElem.textContent.trim() : '';
            if (!code) return;

            navigator.clipboard.writeText(code).then(function() {
                var originalText = btn.innerHTML;
                btn.innerHTML = '✓ Copied!';
                btn.style.background = '#10b981';
                setTimeout(function() {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                }, 2000);
            });
        }

        @if(isset($errors) && $errors->hasAny(['employee_name', 'employee_email', 'company_id', 'department', 'designation', 'employee_id', 'reporting_manager', 'date_joined', 'last_working_date', 'token_validity_days']))
            document.addEventListener('DOMContentLoaded', function() {
                openSendSurveyModal();
            });
        @endif
    </script>

    <!-- Survey Generated Success Modal (Always in DOM for instant AJAX popup) -->
    <div id="surveyGeneratedModal" class="modal-overlay" style="{{ session('survey_generated') ? 'display:flex;' : 'display:none;' }}" onclick="if(event.target===this) closeSurveyGeneratedModal()">
        <div class="modal" style="max-width: 580px; width: 95%;">
            <div class="modal-header" style="background:#f0fdf4;border-bottom:1px solid #bbf7d0;">
                <div class="modal-title" style="color:#166534;display:flex;align-items:center;gap:10px;">
                    <span style="font-size:22px;">🎉</span>
                    <span>Exit Survey Link &amp; Passcode Ready</span>
                </div>
                <button type="button" class="modal-close" onclick="closeSurveyGeneratedModal()">✕</button>
            </div>
            <div class="modal-body" style="padding:24px 28px;">
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin-bottom:20px;">
                    <div style="font-size:11px;color:#64748b;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Recipient</div>
                    <div id="genEmployeeName" style="font-size:16px;font-weight:700;color:#8C0026;margin-top:2px;">{{ session('survey_generated.employee_name') }}</div>
                    <div id="genEmployeeDetails" style="font-size:13px;color:#475569;margin-top:2px;">
                        @if(session('survey_generated'))
                            {{ session('survey_generated.employee_email') }} &bull; {{ session('survey_generated.company_name') }}
                        @endif
                    </div>
                </div>

                <!-- Link Block -->
                <div style="margin-bottom:20px;">
                    <label class="form-label" style="display:flex;justify-content:space-between;align-items:center;">
                        <span>🔗 Survey Access Link</span>
                        <span id="genValidityText" style="font-size:11px;color:#059669;font-weight:600;">
                            @if(session('survey_generated'))
                                Valid for {{ session('survey_generated.validity_days') }} days
                            @endif
                        </span>
                    </label>
                    <div style="display:flex;gap:8px;">
                        <input type="text" id="genSurveyLinkInput" class="form-control" readonly value="{{ session('survey_generated.survey_url') }}" style="font-family:monospace;font-size:12.5px;background:#f1f5f9;cursor:text;">
                        <button type="button" class="btn btn-primary" onclick="copyGenSurveyLink(this)" style="flex-shrink:0;">
                            📋 Copy Link
                        </button>
                    </div>
                </div>

                <!-- Passcode Block -->
                <div style="margin-bottom:24px;">
                    <label class="form-label">🔑 Confidential Access Passcode</label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <div id="genAccessCode" style="flex:1;background:#fef3c7;border:1.5px dashed #f59e0b;border-radius:8px;padding:10px 16px;font-family:monospace;font-size:22px;font-weight:800;color:#78350f;letter-spacing:3px;text-align:center;">
                            {{ session('survey_generated.access_code') }}
                        </div>
                        <button type="button" class="btn btn-accent" id="genPasscodeCopyBtn" onclick="copyGenPasscode(this)" style="flex-shrink:0;height:48px;">
                            📋 Copy Passcode
                        </button>
                    </div>
                    <div style="font-size:11.5px;color:#64748b;margin-top:6px;">
                        The employee will be required to enter this passcode before opening the exit interview form.
                    </div>
                </div>

                <div id="genMailNotice" style="border-radius:8px;padding:12px 16px;font-size:12.5px;display:flex;align-items:center;gap:10px;{{ session('survey_generated') ? (session('survey_generated.mail_sent') ? 'background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;' : 'background:#fffbeb;border:1px solid #fde68a;color:#92400e;') : '' }}">
                    @if(session('survey_generated'))
                        @if(session('survey_generated.mail_sent'))
                            <span style="font-size:16px;">✉️</span>
                            <span>An invitation email with this link and passcode was successfully sent to <strong>{{ session('survey_generated.employee_email') }}</strong>.</span>
                        @else
                            <span style="font-size:16px;">⚠️</span>
                            <span>Email delivery could not be verified. You can copy the link and passcode above and share them directly.</span>
                        @endif
                    @endif
                </div>
            </div>
            <div class="modal-footer" style="background:#f8fafc;">
                <button type="button" class="btn btn-primary" onclick="closeSurveyGeneratedModal()" style="width:100%;justify-content:center;">
                    ✓ Done
                </button>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
