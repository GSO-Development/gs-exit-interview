@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
<style>
    .settings-nav-wrapper {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 6px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
    }
    .settings-nav-bar {
        display: flex;
        align-items: center;
        gap: 6px;
        overflow-x: auto;
        scrollbar-width: thin;
    }
    .settings-nav-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        border: none;
        background: transparent;
        color: #64748b;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
        text-decoration: none;
    }
    .settings-nav-item:hover {
        background: rgba(140, 0, 38, 0.05);
        color: #8C0026;
    }
    .settings-nav-item.active {
        background: linear-gradient(135deg, #8C0026 0%, #66001C 100%);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(140, 0, 38, 0.28);
    }
    .settings-nav-item .tab-icon {
        font-size: 16px;
    }
    .settings-nav-item .tab-badge {
        padding: 2px 7px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        background: #f1f5f9;
        color: #475569;
        transition: all 0.2s;
    }
    .settings-nav-item.active .tab-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }
    .settings-tab-pane {
        display: none;
        animation: fadeInTab 0.22s ease-out;
    }
    .settings-tab-pane.active {
        display: block;
    }
    @keyframes fadeInTab {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div style="max-width:1080px;margin:0 auto;display:flex;flex-direction:column;">

    <!-- Horizontal Navigation Bar -->
    <div class="settings-nav-wrapper">
        <div class="settings-nav-bar" role="tablist">
            <button type="button" class="settings-nav-item active" id="tab-btn-users" onclick="switchSettingsTab('users')" role="tab" aria-selected="true">
                <span class="tab-icon">👥</span>
                <span>Manage Users</span>
                <span class="tab-badge">{{ $hrManagers->count() }}</span>
            </button>
            <button type="button" class="settings-nav-item" id="tab-btn-companies" onclick="switchSettingsTab('companies')" role="tab" aria-selected="false">
                <span class="tab-icon">🏢</span>
                <span>Companies &amp; Groups</span>
                <span class="tab-badge">{{ $allCompanies->count() }}</span>
            </button>
            <button type="button" class="settings-nav-item" id="tab-btn-expiry" onclick="switchSettingsTab('expiry')" role="tab" aria-selected="false">
                <span class="tab-icon">⏳</span>
                <span>Change Expiry Date</span>
            </button>
            <button type="button" class="settings-nav-item" id="tab-btn-terms" onclick="switchSettingsTab('terms')" role="tab" aria-selected="false">
                <span class="tab-icon">📜</span>
                <span>Exit Survey Terms &amp; Conditions</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: Manage Users (Subsidiary HR Managers) -->
    <div id="pane-users" class="settings-tab-pane active">
        <div class="card">
            <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <span class="card-title" style="font-size:16px;font-weight:700;">👥 Subsidiary HR Managers</span>
                    <div style="font-size:12.5px;color:var(--text-muted);margin-top:2px;">
                        Manage subsidiary HR users, their access rights, and assign them to specific corporate subsidiaries.
                    </div>
                </div>
                <button class="btn btn-accent btn-sm" onclick="document.getElementById('addHrModal').style.display='flex'">
                    + Add HR Manager
                </button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Assigned Company</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hrManagers as $manager)
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:var(--text);display:flex;align-items:center;gap:8px;">
                                        <span>{{ $manager->name }}</span>
                                        @if($manager->auth_provider === 'microsoft')
                                            <span style="display:inline-flex;align-items:center;gap:4px;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:12px;padding:1px 7px;font-size:10.5px;font-weight:700;">
                                                <svg style="width:10px;height:10px" viewBox="0 0 21 21"><rect x="1" y="1" width="9" height="9" fill="#f25022"/><rect x="11" y="1" width="9" height="9" fill="#7fba00"/><rect x="1" y="11" width="9" height="9" fill="#00a4ef"/><rect x="11" y="11" width="9" height="9" fill="#ffb900"/></svg>
                                                Microsoft 365
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td style="font-size:13px;color:var(--text-muted)">{{ $manager->email }}</td>
                                <td>
                                    @if($manager->company)
                                        <span class="badge badge-info">{{ $manager->company->name }}</span>
                                    @else
                                        <span class="badge badge-danger">⚠ No company assigned</span>
                                    @endif
                                </td>
                                <td style="text-align:right;">
                                    <div style="display:inline-flex;gap:6px">
                                        <button class="btn btn-ghost btn-xs"
                                            onclick="openReassign({{ $manager->id }}, '{{ $manager->company_id }}')">
                                            🏢 Reassign
                                        </button>
                                        <form method="POST" action="{{ route('admin.settings.hr-managers.destroy', $manager) }}"
                                            onsubmit="return confirm('Delete this HR Manager account?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs">🗑 Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:var(--text-muted);padding:40px">
                                    No subsidiary HR managers yet. Click "+ Add HR Manager" above to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: Companies & Groups (Subsidiary Companies) -->
    <div id="pane-companies" class="settings-tab-pane">
        <div class="card">
            <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <span class="card-title" style="font-size:16px;font-weight:700;">🏢 Subsidiary Companies &amp; Groups</span>
                    <div style="font-size:12.5px;color:var(--text-muted);margin-top:2px;">
                        Manage George Steuart subsidiaries, corporate codes, active headcount benchmarks, and track exit counts.
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('addCompanyModal').style.display='flex'">
                    + Add Company
                </button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Company Code</th>
                            <th>Status</th>
                            <th style="text-align:center;">TOTAL HEADCOUNT</th>
                            <th style="text-align:center;">EXIT COUNT</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allCompanies as $comp)
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:var(--text);">{{ $comp->name }}</div>
                                </td>
                                <td>
                                    <span style="background:#fff0f3;color:#8C0026;border:1px solid #fecdd3;padding:3px 8px;border-radius:6px;font-size:11.5px;font-weight:700;letter-spacing:0.5px;">
                                        {{ $comp->code }}
                                    </span>
                                </td>
                                <td>
                                    @if($comp->is_active)
                                        <span style="background:#dcfce7;color:#15803d;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700;">
                                            ● Active
                                        </span>
                                    @else
                                        <span style="background:#f1f5f9;color:#64748b;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700;">
                                            ○ Inactive
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align:center;font-weight:700;color:#0f172a;font-size:13.5px;">
                                    {{ number_format($comp->headcount ?? 0) }}
                                </td>
                                <td style="text-align:center;">
                                    <span style="background:#fee2e2;color:#be123c;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:700;">
                                        {{ $comp->exit_surveys_count ?? $comp->exitSurveys()->count() }} Exits
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <div style="display:inline-flex;gap:6px;">
                                        <button type="button" class="btn btn-ghost btn-xs"
                                            onclick="openEditCompany({{ $comp->id }}, '{{ addslashes($comp->name) }}', '{{ addslashes($comp->code) }}', {{ $comp->headcount ?? 0 }}, {{ $comp->is_active ? 1 : 0 }})">
                                            ✏️ Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.settings.companies.destroy', $comp) }}"
                                            onsubmit="return confirm('Are you sure you want to delete {{ addslashes($comp->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs">🗑 Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--text-muted);padding:30px;">
                                    No companies found. Click "+ Add Company" above to add your first subsidiary.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: Change Expiry Date (Global Token Configuration) -->
    <div id="pane-expiry" class="settings-tab-pane">
        <div class="card">
            <div class="card-header">
                <span class="card-title" style="font-size:16px;font-weight:700;">⏳ Global Token Validity Configuration</span>
                <div style="font-size:12.5px;color:var(--text-muted);margin-top:2px;">
                    Define the default duration (in days) that exit interview links and passcodes remain active before expiration.
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf @method('PATCH')
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:end">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="default_token_validity_days">
                                Default Token Validity (Days) *
                            </label>
                            <input
                                type="number"
                                id="default_token_validity_days"
                                name="default_token_validity_days"
                                class="form-control"
                                value="{{ old('default_token_validity_days', $defaultDays) }}"
                                min="1" max="90" required
                            >
                            @error('default_token_validity_days')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <div style="font-size:12px;color:var(--text-muted);margin-top:6px">
                                Applied to all new exit interview invitations unless overridden per-invitation. Allowed range: 1–90 days.
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">💾 Save Expiry Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 4: Exit Survey Terms & Conditions -->
    <div id="pane-terms" class="settings-tab-pane">
        <div class="card">
            <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <span class="card-title" style="font-size:16px;font-weight:700;">📜 Exit Survey Terms &amp; Conditions</span>
                    <div style="font-size:12.5px;color:var(--text-muted);margin-top:2px;">
                        This legal text will be displayed in the modal popup when employees click "Terms and Conditions" before submitting the survey.
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label class="form-label" for="exit_survey_terms_and_conditions">
                            Terms &amp; Conditions Content (Text / Clauses) *
                        </label>
                        <textarea
                            id="exit_survey_terms_and_conditions"
                            name="exit_survey_terms_and_conditions"
                            class="form-control"
                            rows="12"
                            style="font-family: inherit; font-size: 13.5px; line-height: 1.6; resize: vertical;"
                            required
                        >{{ old('exit_survey_terms_and_conditions', $termsAndConditions) }}</textarea>
                        @error('exit_survey_terms_and_conditions')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div style="font-size:12px;color:var(--text-muted);margin-top:6px">
                            Enter clear clauses covering confidentiality, voluntary disclosure, non-retaliation, feedback usage, and data privacy.
                        </div>
                    </div>
                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">💾 Save Terms &amp; Conditions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- Add Company Modal -->
<div id="addCompanyModal" class="modal-overlay" style="display:none" onclick="if(event.target===this) this.style.display='none'">
    <div class="modal" style="max-width:500px;">
        <div class="modal-header">
            <div class="modal-title">🏢 Add Subsidiary Company</div>
            <button class="modal-close" onclick="document.getElementById('addCompanyModal').style.display='none'">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.settings.companies.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="add_comp_name">Company Name *</label>
                    <input type="text" id="add_comp_name" name="name" class="form-control"
                        placeholder="e.g. GS Technology Services" required value="{{ old('name') }}">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="add_comp_code">Company Code *</label>
                        <input type="text" id="add_comp_code" name="code" class="form-control"
                            placeholder="e.g. GSTech" required value="{{ old('code') }}" style="text-transform:uppercase;">
                        @error('code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="add_comp_headcount">Total Headcount *</label>
                        <input type="number" id="add_comp_headcount" name="headcount" class="form-control"
                            placeholder="e.g. 150" min="0" required value="{{ old('headcount', 0) }}">
                        @error('headcount')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:13.5px;font-weight:600;color:var(--text);">
                        <input type="checkbox" name="is_active" value="1" checked style="width:18px;height:18px;accent-color:var(--primary);">
                        <span>Active (Available for survey invitations & forms)</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('addCompanyModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">✅ Add Company</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Company Modal -->
<div id="editCompanyModal" class="modal-overlay" style="display:none" onclick="if(event.target===this) this.style.display='none'">
    <div class="modal" style="max-width:500px;">
        <div class="modal-header">
            <div class="modal-title">✏️ Edit Subsidiary Company</div>
            <button class="modal-close" onclick="document.getElementById('editCompanyModal').style.display='none'">✕</button>
        </div>
        <form method="POST" id="editCompanyForm">
            @csrf @method('PATCH')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="edit_comp_name">Company Name *</label>
                    <input type="text" id="edit_comp_name" name="name" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="edit_comp_code">Company Code *</label>
                        <input type="text" id="edit_comp_code" name="code" class="form-control" required style="text-transform:uppercase;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="edit_comp_headcount">Total Headcount *</label>
                        <input type="number" id="edit_comp_headcount" name="headcount" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="edit_comp_status">Status *</label>
                    <select id="edit_comp_status" name="is_active" class="form-control" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('editCompanyModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Add HR Manager Modal -->
<div id="addHrModal" class="modal-overlay" style="display:none" onclick="if(event.target===this) this.style.display='none'">
    <div class="modal" style="max-width:540px;">
        <div class="modal-header">
            <div class="modal-title">👤 Add Subsidiary HR Manager</div>
            <button class="modal-close" onclick="document.getElementById('addHrModal').style.display='none'">✕</button>
        </div>

        <!-- Mode Toggle (System User vs Microsoft User) -->
        <div style="padding:14px 20px 0;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
            <div style="display:flex;gap:6px;background:#e2e8f0;padding:4px;border-radius:10px;">
                <button type="button" id="btnModeSystem" onclick="switchHrModalMode('system')"
                    style="flex:1;display:flex;align-items:center;justify-content:center;gap:7px;padding:9px 12px;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;background:#8C0026;color:#ffffff;transition:all 0.2s;">
                    <span>👤</span>
                    <span>System User</span>
                </button>
                <button type="button" id="btnModeMicrosoft" onclick="switchHrModalMode('microsoft')"
                    style="flex:1;display:flex;align-items:center;justify-content:center;gap:7px;padding:9px 12px;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;background:transparent;color:#475569;transition:all 0.2s;">
                    <svg style="width:14px;height:14px;flex-shrink:0;" viewBox="0 0 21 21">
                        <rect x="1" y="1" width="9" height="9" fill="#f25022"/>
                        <rect x="11" y="1" width="9" height="9" fill="#7fba00"/>
                        <rect x="1" y="11" width="9" height="9" fill="#00a4ef"/>
                        <rect x="11" y="11" width="9" height="9" fill="#ffb900"/>
                    </svg>
                    <span>Microsoft User</span>
                </button>
            </div>
            <div id="modeDescription" style="font-size:12px;color:#64748b;margin:10px 2px 12px;line-height:1.4;">
                Standard local user account with credentials stored in system.
            </div>
        </div>

        <!-- MODE 1: Standard System User Form -->
        <div id="containerModeSystem">
            <form method="POST" action="{{ route('admin.settings.hr-managers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="hr_name">Full Name *</label>
                        <input type="text" id="hr_name" name="name" class="form-control"
                            placeholder="e.g. Nimal Perera" required value="{{ old('name') }}">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="hr_email">Email Address *</label>
                        <input type="email" id="hr_email" name="email" class="form-control"
                            placeholder="e.g. nimal@gsoptimize.lk" required value="{{ old('email') }}">
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="hr_company">Assigned Subsidiary *</label>
                        <select id="hr_company" name="company_id" class="form-control" required>
                            <option value="">— Select Company —</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }} ({{ $company->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="hr_password">Password *</label>
                            <input type="password" id="hr_password" name="password" class="form-control" required>
                            @error('password')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="hr_password_confirmation">Confirm Password *</label>
                            <input type="password" id="hr_password_confirmation" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('addHrModal').style.display='none'">Cancel</button>
                    <button type="submit" class="btn btn-accent">✅ Create Account</button>
                </div>
            </form>
        </div>

        <!-- MODE 2: Microsoft User Search & Import -->
        <div id="containerModeMicrosoft" style="display:none;">
            <!-- Step A: Search for tenant employee -->
            <div id="msSearchStep" class="modal-body" style="padding-bottom:12px;">
                <div class="form-group" style="margin-bottom:14px;">
                    <label class="form-label" for="azure_search_input">
                        Search Employee in Microsoft 365 Tenant *
                    </label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;">🔍</span>
                        <input type="text" id="azure_search_input" class="form-control"
                            style="padding-left:36px;padding-right:36px;"
                            placeholder="Type employee name or email (e.g. lakshan, aaron)..."
                            autocomplete="off">
                        <span id="azure_search_spinner" style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:14px;">⏳</span>
                    </div>
                    <div style="font-size:11.5px;color:#64748b;margin-top:5px;">
                        Connects directly to George Steuart Azure Tenant via Microsoft Graph API.
                    </div>
                </div>

                <!-- Live Search Results Dropdown/Box -->
                <div id="azure_results_box" style="max-height:220px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc;padding:6px;display:none;">
                    <!-- Items rendered dynamically via JS -->
                </div>

                <div id="azure_search_empty" style="display:none;text-align:center;padding:18px;color:#94a3b8;font-size:12.5px;">
                    No employees matching your search in the Microsoft 365 tenant.
                </div>
            </div>

            <!-- Step B: Selected Microsoft User details and Company assignment -->
            <div id="msSelectedStep" style="display:none;">
                <form method="POST" action="{{ route('admin.settings.hr-managers.store-microsoft') }}">
                    @csrf
                    <input type="hidden" id="ms_user_name" name="name">
                    <input type="hidden" id="ms_user_email" name="email">
                    <input type="hidden" id="ms_user_azure_id" name="azure_id">

                    <div class="modal-body" style="padding-top:14px;">
                        <!-- Selected Profile Badge Card -->
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:14px;margin-bottom:18px;display:flex;align-items:center;gap:12px;">
                            <div id="ms_avatar_badge" style="width:42px;height:42px;border-radius:50%;background:#1d4ed8;color:#ffffff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;flex-shrink:0;">
                                MS
                            </div>
                            <div style="flex:1;overflow:hidden;">
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <span id="ms_display_name" style="font-weight:700;color:#1e3a8a;font-size:14.5px;">Name</span>
                                    <span style="display:inline-flex;align-items:center;gap:3px;background:#ffffff;border:1px solid #93c5fd;border-radius:10px;padding:1px 6px;font-size:10px;font-weight:700;color:#1e40af;">
                                        <svg style="width:8px;height:8px" viewBox="0 0 21 21"><rect x="1" y="1" width="9" height="9" fill="#f25022"/><rect x="11" y="1" width="9" height="9" fill="#7fba00"/><rect x="1" y="11" width="9" height="9" fill="#00a4ef"/><rect x="11" y="11" width="9" height="9" fill="#ffb900"/></svg>
                                        M365
                                    </span>
                                </div>
                                <div id="ms_display_email" style="font-size:12.5px;color:#3b82f6;margin-top:1px;">email@domain.com</div>
                                <div id="ms_display_job" style="font-size:11.5px;color:#64748b;margin-top:2px;">Department / Title</div>
                            </div>
                            <button type="button" onclick="clearSelectedAzureUser()" style="background:none;border:none;color:#ef4444;font-size:12px;font-weight:600;cursor:pointer;text-decoration:underline;">
                                Change
                            </button>
                        </div>

                        <!-- Assign Subsidiary -->
                        <div class="form-group">
                            <label class="form-label" for="ms_company_id">Assign Subsidiary Company *</label>
                            <select id="ms_company_id" name="company_id" class="form-control" required>
                                <option value="">— Select Subsidiary Company —</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->name }} ({{ $company->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- SSO info alert -->
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:10px 14px;font-size:12px;color:#166534;line-height:1.45;display:flex;align-items:start;gap:8px;">
                            <span>🔐</span>
                            <div>
                                <strong>Single Sign-On (SSO):</strong> This user will log into the Exit Interview Portal directly using their Microsoft 365 work credentials via the <em>"Sign in with Microsoft"</em> button on the login screen.
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-ghost" onclick="clearSelectedAzureUser()">Back to Search</button>
                        <button type="submit" class="btn btn-accent" style="background:#0078d4;border-color:#0078d4;">
                            <svg style="width:14px;height:14px;display:inline-block;vertical-align:middle;margin-right:5px;" viewBox="0 0 21 21">
                                <rect x="1" y="1" width="9" height="9" fill="#f25022"/>
                                <rect x="11" y="1" width="9" height="9" fill="#7fba00"/>
                                <rect x="1" y="11" width="9" height="9" fill="#00a4ef"/>
                                <rect x="11" y="11" width="9" height="9" fill="#ffb900"/>
                            </svg>
                            Add as HR Manager
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reassign Company Modal -->
<div id="reassignModal" class="modal-overlay" style="display:none" onclick="if(event.target===this) this.style.display='none'">
    <div class="modal" style="max-width:420px">
        <div class="modal-header">
            <div class="modal-title">🏢 Reassign Company</div>
            <button class="modal-close" onclick="document.getElementById('reassignModal').style.display='none'">✕</button>
        </div>
        <form method="POST" id="reassignForm">
            @csrf @method('PATCH')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="reassign_company">New Assigned Company *</label>
                    <select id="reassign_company" name="company_id" class="form-control" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }} ({{ $company->code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('reassignModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function switchSettingsTab(tabName) {
    const validTabs = ['users', 'companies', 'expiry', 'terms'];
    if (!validTabs.includes(tabName)) {
        tabName = 'users';
    }

    // Hide all panes
    validTabs.forEach(function(t) {
        const pane = document.getElementById('pane-' + t);
        const btn = document.getElementById('tab-btn-' + t);
        if (pane) pane.classList.remove('active');
        if (btn) {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
        }
    });

    // Show active pane
    const activePane = document.getElementById('pane-' + tabName);
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    if (activePane) activePane.classList.add('active');
    if (activeBtn) {
        activeBtn.classList.add('active');
        activeBtn.setAttribute('aria-selected', 'true');
    }

    // Save state
    try {
        sessionStorage.setItem('gs_settings_active_tab', tabName);
        history.replaceState(null, null, '#' + tabName);
    } catch(e) {}
}

document.addEventListener('DOMContentLoaded', function() {
    let initialTab = 'users';
    const hash = window.location.hash.replace('#', '');
    const validTabs = ['users', 'companies', 'expiry', 'terms'];

    @if(isset($errors) && ($errors->has('name') || $errors->has('email') || $errors->has('password') || $errors->has('company_id')))
        initialTab = 'users';
    @elseif(isset($errors) && ($errors->has('code') || $errors->has('headcount')))
        initialTab = 'companies';
    @elseif(isset($errors) && $errors->has('default_token_validity_days'))
        initialTab = 'expiry';
    @elseif(isset($errors) && $errors->has('exit_survey_terms_and_conditions'))
        initialTab = 'terms';
    @else
        if (validTabs.includes(hash)) {
            initialTab = hash;
        } else {
            try {
                const saved = sessionStorage.getItem('gs_settings_active_tab');
                if (validTabs.includes(saved)) {
                    initialTab = saved;
                }
            } catch(e) {}
        }
    @endif

    switchSettingsTab(initialTab);
});

function openEditCompany(id, name, code, headcount, isActive) {
    const form = document.getElementById('editCompanyForm');
    form.action = `/admin/settings/companies/${id}`;
    document.getElementById('edit_comp_name').value = name;
    document.getElementById('edit_comp_code').value = code;
    document.getElementById('edit_comp_headcount').value = headcount;
    document.getElementById('edit_comp_status').value = isActive ? '1' : '0';
    document.getElementById('editCompanyModal').style.display = 'flex';
}

function openReassign(userId, currentCompanyId) {
    const form = document.getElementById('reassignForm');
    form.action = `/admin/settings/hr-managers/${userId}`;
    document.getElementById('reassign_company').value = currentCompanyId;
    document.getElementById('reassignModal').style.display = 'flex';
}

// ── Mode Switcher for Add HR Modal (System vs Microsoft) ─────────────
function switchHrModalMode(mode) {
    const btnSystem = document.getElementById('btnModeSystem');
    const btnMicrosoft = document.getElementById('btnModeMicrosoft');
    const containerSystem = document.getElementById('containerModeSystem');
    const containerMicrosoft = document.getElementById('containerModeMicrosoft');
    const modeDesc = document.getElementById('modeDescription');

    if (mode === 'microsoft') {
        btnSystem.style.background = 'transparent';
        btnSystem.style.color = '#475569';
        btnMicrosoft.style.background = '#0078d4';
        btnMicrosoft.style.color = '#ffffff';
        containerSystem.style.display = 'none';
        containerMicrosoft.style.display = 'block';
        modeDesc.innerHTML = 'Search and import corporate employees from your <strong>Microsoft 365 Azure Tenant</strong>. They will use Microsoft Single Sign-On (SSO).';
        
        // Trigger initial focus and list if empty
        const searchInput = document.getElementById('azure_search_input');
        if (searchInput) {
            setTimeout(() => {
                searchInput.focus();
                if (!searchInput.value.trim()) {
                    triggerAzureSearch('');
                }
            }, 60);
        }
    } else {
        btnSystem.style.background = '#8C0026';
        btnSystem.style.color = '#ffffff';
        btnMicrosoft.style.background = 'transparent';
        btnMicrosoft.style.color = '#475569';
        containerSystem.style.display = 'block';
        containerMicrosoft.style.display = 'none';
        modeDesc.textContent = 'Standard local user account with credentials stored in system.';
    }
}

// ── Microsoft Tenant User Search via Graph API ───────────────────────
let azureSearchTimer = null;

function triggerAzureSearch(query) {
    const spinner = document.getElementById('azure_search_spinner');
    const resultsBox = document.getElementById('azure_results_box');
    const emptyNotice = document.getElementById('azure_search_empty');

    if (spinner) spinner.style.display = 'inline';
    if (emptyNotice) emptyNotice.style.display = 'none';

    fetch(`{{ route('admin.settings.azure-users.search') }}?query=${encodeURIComponent(query)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (spinner) spinner.style.display = 'none';
        if (!resultsBox) return;

        resultsBox.innerHTML = '';
        const users = data.users || [];

        if (users.length === 0) {
            resultsBox.style.display = 'none';
            if (emptyNotice) emptyNotice.style.display = 'block';
            return;
        }

        if (emptyNotice) emptyNotice.style.display = 'none';
        resultsBox.style.display = 'block';

        users.forEach(u => {
            const item = document.createElement('div');
            item.style.cssText = 'padding:10px 12px;border-radius:8px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;margin-bottom:4px;background:#ffffff;border:1px solid #e2e8f0;transition:all 0.15s;';
            item.onmouseover = () => { item.style.borderColor = '#93c5fd'; item.style.background = '#f0f9ff'; };
            item.onmouseout = () => { item.style.borderColor = '#e2e8f0'; item.style.background = '#ffffff'; };

            const initials = (u.name || 'MS').substring(0, 2).toUpperCase();

            let badgeHtml = '';
            if (u.is_already_added) {
                badgeHtml = '<span style="background:#f1f5f9;color:#64748b;font-size:11px;padding:3px 8px;border-radius:12px;font-weight:600;">Already Added</span>';
            } else {
                badgeHtml = '<span style="background:#dbeafe;color:#1e40af;font-size:11px;padding:3px 8px;border-radius:12px;font-weight:700;">+ Select</span>';
            }

            item.innerHTML = `
                <div style="display:flex;align-items:center;gap:10px;overflow:hidden;flex:1;">
                    <div style="width:34px;height:34px;border-radius:50%;background:#0078d4;color:#ffffff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">
                        ${initials}
                    </div>
                    <div style="overflow:hidden;">
                        <div style="font-weight:700;color:#0f172a;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            ${escapeHtml(u.name)}
                        </div>
                        <div style="font-size:12px;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            ${escapeHtml(u.email || u.userPrincipalName)} &bull; <span style="color:#94a3b8;">${escapeHtml(u.department || u.job_title || 'Employee')}</span>
                        </div>
                    </div>
                </div>
                <div>${badgeHtml}</div>
            `;

            item.onclick = () => selectAzureUser(u);
            resultsBox.appendChild(item);
        });
    })
    .catch(err => {
        if (spinner) spinner.style.display = 'none';
        console.error('Azure search error:', err);
    });
}

function selectAzureUser(u) {
    document.getElementById('ms_user_name').value = u.name || '';
    document.getElementById('ms_user_email').value = u.email || u.userPrincipalName || '';
    document.getElementById('ms_user_azure_id').value = u.id || '';

    document.getElementById('ms_display_name').textContent = u.name || 'Microsoft User';
    document.getElementById('ms_display_email').textContent = u.email || u.userPrincipalName || '';
    document.getElementById('ms_display_job').textContent = (u.job_title || 'Employee') + (u.department ? ' • ' + u.department : '');
    document.getElementById('ms_avatar_badge').textContent = (u.name || 'MS').substring(0, 2).toUpperCase();

    // Show Step B (Selected card) and hide Step A (Search)
    document.getElementById('msSearchStep').style.display = 'none';
    document.getElementById('msSelectedStep').style.display = 'block';
}

function clearSelectedAzureUser() {
    document.getElementById('ms_user_name').value = '';
    document.getElementById('ms_user_email').value = '';
    document.getElementById('ms_user_azure_id').value = '';

    document.getElementById('msSelectedStep').style.display = 'none';
    document.getElementById('msSearchStep').style.display = 'block';

    const searchInput = document.getElementById('azure_search_input');
    if (searchInput) {
        searchInput.focus();
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"']/g, function(m) {
        return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[m];
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('azure_search_input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(azureSearchTimer);
            const q = searchInput.value.trim();
            azureSearchTimer = setTimeout(() => {
                triggerAzureSearch(q);
            }, 300);
        });
    }
});
</script>
@endpush
