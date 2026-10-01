@extends('layouts.admin')

@section('title', 'Surveys')
@section('page-title', 'Surveys & Submissions')



@section('content')

<!-- Tabs -->
<div class="tabs">
    <button class="tab-btn {{ $tab === 'completed' ? 'active' : '' }}"
        onclick="window.location='{{ route('admin.surveys.index', ['tab' => 'completed']) }}'">
        ✅ Completed Submissions ({{ $completed->total() }})
    </button>
    <button class="tab-btn {{ $tab === 'sent' ? 'active' : '' }}"
        onclick="window.location='{{ route('admin.surveys.index', ['tab' => 'sent']) }}'">
        📤 Sent Invitations &amp; Tokens ({{ $sent->total() }})
    </button>
    <button class="tab-btn {{ $tab === 'expired' ? 'active' : '' }}"
        onclick="window.location='{{ route('admin.surveys.index', ['tab' => 'expired']) }}'">
        ⌛ Expired &amp; Revoked ({{ $expired->total() }})
    </button>
    <button class="tab-btn {{ $tab === 'system' ? 'active' : '' }}"
        onclick="window.location='{{ route('admin.surveys.index', ['tab' => 'system']) }}'">
        ⚙️ System Generated ({{ $system->total() }})
    </button>
</div>

<!-- Search & Filters -->
<div class="card" style="margin-bottom:24px">
    <div class="card-body" style="padding:16px 24px">
        <form method="GET" action="{{ route('admin.surveys.index') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div style="flex:1;min-width:200px">
                <input type="text" name="search" class="form-control" placeholder="🔍  Search by name or EPF..." value="{{ request('search') }}" style="margin:0">
            </div>
            @if(!in_array($tab, ['sent', 'expired']))
                <select name="department" class="form-control" style="width:200px;margin:0">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            @endif
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            @if(request('search') || request('department'))
                <a href="{{ route('admin.surveys.index', ['tab' => $tab]) }}" class="btn btn-ghost btn-sm">Clear</a>
            @endif
            @if(!in_array($tab, ['sent', 'expired']))
                <a href="{{ route('admin.surveys.index', array_merge(request()->query(), ['export' => 'csv'])) }}" class="btn btn-ghost btn-sm" style="margin-left:auto">📥 Export CSV</a>
            @endif
        </form>
    </div>
</div>

<!-- 1. Completed Submissions Tab (Online Employee Submissions) -->
@if($tab === 'completed')
    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Company</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Overall Rating</th>
                        <th>Submitted</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completed as $survey)
                        @php
                            $modalData = [
                                'id' => $survey->id,
                                'name' => $survey->employee_name,
                                'email' => $survey->employee_email,
                                'employee_id' => $survey->employee_id ?? '—',
                                'designation' => $survey->designation,
                                'company' => $survey->company->name ?? '—',
                                'company_code' => $survey->company->code ?? '',
                                'department' => $survey->department,
                                'section_division' => $survey->section_division ?? $survey->department,
                                'supervisor_name' => $survey->supervisor_name ?? $survey->reporting_manager ?? '—',
                                'date_joined' => $survey->date_joined?->format('Y-m-d') ?? '—',
                                'date_of_resignation' => $survey->date_of_resignation?->format('Y-m-d') ?? $survey->last_working_date?->format('Y-m-d') ?? '—',
                                'tenure' => $survey->tenureYears() ?? '—',
                                'submitted_at' => $survey->submitted_at?->format('Y-m-d H:i') ?? '—',
                                'is_system' => false,
                                'overall_satisfaction' => $survey->overallSatisfaction(),
                                'pdf_url' => route('admin.surveys.pdf', $survey),
                                'word_url' => route('admin.surveys.word', $survey),
                                'show_url' => route('admin.surveys.show', $survey),
                                'response' => $survey->response ? [
                                    'area_ratings' => $survey->response->area_ratings,
                                    'main_reason' => $survey->response->main_reason_to_leave ?? $survey->response->primary_resignation_reason,
                                    'overseas_option' => $survey->response->overseas_sub_option,
                                    'other_reasons' => $survey->response->other_reasons_to_leave ?? $survey->response->resignation_factors,
                                    'liked_aspects' => $survey->response->liked_most_aspects,
                                    'enjoyed_most' => $survey->response->enjoyed_most,
                                    'prevented_resignation' => $survey->response->prevented_resignation ?? $survey->response->resignation_elaboration,
                                    'recommend_company' => $survey->response->recommend_company ?? $survey->response->would_recommend,
                                    'open_to_reapply' => $survey->response->open_to_reapply ?? $survey->response->would_return,
                                    'other_comments' => $survey->response->other_comments ?? $survey->response->improvement_suggestions,
                                    'team_member_signature' => $survey->response->team_member_signature,
                                    'team_member_signed_date' => $survey->response->team_member_signed_date,
                                    'reviewed_director_hr' => $survey->response->reviewed_director_hr,
                                    'reviewed_director_hr_date' => $survey->response->reviewed_director_hr_date,
                                    'reviewed_company_head' => $survey->response->reviewed_company_head,
                                    'reviewed_company_head_date' => $survey->response->reviewed_company_head_date,
                                    'reviewed_group_chairman' => $survey->response->reviewed_group_chairman,
                                    'reviewed_group_chairman_date' => $survey->response->reviewed_group_chairman_date,
                                ] : null,
                            ];
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight:600">{{ $survey->employee_name }}</div>
                                <div style="font-size:11.5px;color:var(--text-muted)">{{ $survey->employee_id ?? '—' }}</div>
                                <span style="background:#dcfce7;color:#15803d;font-size:10px;padding:2px 6px;border-radius:4px;font-weight:700;display:inline-block;margin-top:3px;">👤 Online Form</span>
                            </td>
                            <td><span class="badge badge-info">{{ $survey->company->name }}</span></td>
                            <td>{{ $survey->designation }}</td>
                            <td style="color:var(--text-muted)">{{ $survey->department }}</td>
                            <td>
                                @php $rating = $survey->overallSatisfaction(); @endphp
                                @if($rating)
                                    <span style="color:var(--accent);font-weight:700">⭐ {{ $rating }} / 5.0</span>
                                @else
                                    <span class="badge badge-muted">Pending</span>
                                @endif
                            </td>
                            <td style="font-size:12.5px;color:var(--text-muted)">
                                {{ $survey->submitted_at?->format('Y-m-d') }}<br>
                                <span style="font-size:11px">{{ $survey->submitted_at?->diffForHumans() }}</span>
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex;gap:5px;flex-wrap:wrap;justify-content:flex-end;">
                                    <button type="button" class="btn btn-ghost btn-xs"
                                        data-survey="{{ base64_encode(json_encode($modalData)) }}"
                                        onclick="openSurveyQuickView(this)">
                                        👁 View
                                    </button>
                                    <a href="{{ route('admin.surveys.pdf', $survey) }}" class="btn btn-ghost btn-xs" target="_blank">📥 PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--text-muted);padding:50px">
                                No online completed submissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($completed->hasPages())
            <div style="padding:16px 24px;border-top:1px solid var(--border)">
                {{ $completed->links() }}
            </div>
        @endif
    </div>

<!-- 2. System Generated Submissions Tab (Template Page Submissions) -->
@elseif($tab === 'system')
    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Company</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Overall Rating</th>
                        <th>Recorded Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($system as $survey)
                        @php
                            $modalData = [
                                'id' => $survey->id,
                                'name' => $survey->employee_name,
                                'email' => $survey->employee_email,
                                'employee_id' => $survey->employee_id ?? '—',
                                'designation' => $survey->designation,
                                'company' => $survey->company->name ?? '—',
                                'company_code' => $survey->company->code ?? '',
                                'department' => $survey->department,
                                'section_division' => $survey->section_division ?? $survey->department,
                                'supervisor_name' => $survey->supervisor_name ?? $survey->reporting_manager ?? '—',
                                'date_joined' => $survey->date_joined?->format('Y-m-d') ?? '—',
                                'date_of_resignation' => $survey->date_of_resignation?->format('Y-m-d') ?? $survey->last_working_date?->format('Y-m-d') ?? '—',
                                'tenure' => $survey->tenureYears() ?? '—',
                                'submitted_at' => $survey->submitted_at?->format('Y-m-d H:i') ?? '—',
                                'is_system' => true,
                                'overall_satisfaction' => $survey->overallSatisfaction(),
                                'pdf_url' => route('admin.surveys.pdf', $survey),
                                'word_url' => route('admin.surveys.word', $survey),
                                'show_url' => route('admin.surveys.show', $survey),
                                'response' => $survey->response ? [
                                    'area_ratings' => $survey->response->area_ratings,
                                    'main_reason' => $survey->response->main_reason_to_leave ?? $survey->response->primary_resignation_reason,
                                    'overseas_option' => $survey->response->overseas_sub_option,
                                    'other_reasons' => $survey->response->other_reasons_to_leave ?? $survey->response->resignation_factors,
                                    'liked_aspects' => $survey->response->liked_most_aspects,
                                    'enjoyed_most' => $survey->response->enjoyed_most,
                                    'prevented_resignation' => $survey->response->prevented_resignation ?? $survey->response->resignation_elaboration,
                                    'recommend_company' => $survey->response->recommend_company ?? $survey->response->would_recommend,
                                    'open_to_reapply' => $survey->response->open_to_reapply ?? $survey->response->would_return,
                                    'other_comments' => $survey->response->other_comments ?? $survey->response->improvement_suggestions,
                                    'team_member_signature' => $survey->response->team_member_signature,
                                    'team_member_signed_date' => $survey->response->team_member_signed_date,
                                    'reviewed_director_hr' => $survey->response->reviewed_director_hr,
                                    'reviewed_director_hr_date' => $survey->response->reviewed_director_hr_date,
                                    'reviewed_company_head' => $survey->response->reviewed_company_head,
                                    'reviewed_company_head_date' => $survey->response->reviewed_company_head_date,
                                    'reviewed_group_chairman' => $survey->response->reviewed_group_chairman,
                                    'reviewed_group_chairman_date' => $survey->response->reviewed_group_chairman_date,
                                ] : null,
                            ];
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight:600">{{ $survey->employee_name }}</div>
                                <div style="font-size:11.5px;color:var(--text-muted)">{{ $survey->employee_id ?? '—' }}</div>
                                <span style="background:#e0e7ff;color:#3730a3;font-size:10px;padding:2px 6px;border-radius:4px;font-weight:700;display:inline-block;margin-top:3px;">⚙️ System Generated</span>
                            </td>
                            <td><span class="badge badge-info">{{ $survey->company->name }}</span></td>
                            <td>{{ $survey->designation }}</td>
                            <td style="color:var(--text-muted)">{{ $survey->department }}</td>
                            <td>
                                @php $rating = $survey->overallSatisfaction(); @endphp
                                @if($rating)
                                    <span style="color:var(--accent);font-weight:700">⭐ {{ $rating }} / 5.0</span>
                                @else
                                    <span class="badge badge-muted">Pending</span>
                                @endif
                            </td>
                            <td style="font-size:12.5px;color:var(--text-muted)">
                                {{ $survey->submitted_at?->format('Y-m-d') }}<br>
                                <span style="font-size:11px">{{ $survey->submitted_at?->diffForHumans() }}</span>
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex;gap:5px;flex-wrap:wrap;justify-content:flex-end;">
                                    <button type="button" class="btn btn-ghost btn-xs"
                                        data-survey="{{ base64_encode(json_encode($modalData)) }}"
                                        onclick="openSurveyQuickView(this)">
                                        👁 View
                                    </button>
                                    <a href="{{ route('admin.surveys.pdf', $survey) }}" class="btn btn-ghost btn-xs" target="_blank">📥 PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--text-muted);padding:50px">
                                No system-generated exit interview records found. Use the Template Form to enter data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($system->hasPages())
            <div style="padding:16px 24px;border-top:1px solid var(--border)">
                {{ $system->links() }}
            </div>
        @endif
    </div>

<!-- 3. Sent Invitations Tab -->
@elseif($tab === 'sent')
    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Recipient</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>ACCESS LINK &amp; PASSWORD</th>
                        <th>Expires</th>
                        <th>Sent</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sent as $survey)
                        <tr>
                            <td>
                                <div style="font-weight:600">{{ $survey->employee_name }}</div>
                                <div style="font-size:11.5px;color:var(--text-muted)">{{ $survey->designation }}</div>
                            </td>
                            <td style="font-size:12.5px">{{ $survey->employee_email }}</td>
                            <td><span class="badge badge-info">{{ $survey->company->name }}</span></td>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:5px;min-width:230px;">
                                    <!-- Passcode Box with Copy -->
                                    <div style="display:flex;align-items:center;justify-content:space-between;background:#fff0f3;border:1px solid #fecdd3;border-radius:6px;padding:3px 8px;">
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <span style="font-size:12px;">🔑</span>
                                            <span style="font-family:monospace;font-size:12.5px;font-weight:800;letter-spacing:1px;color:#8C0026;">
                                                {{ $survey->access_code ?: 'NO PASSCODE' }}
                                            </span>
                                        </div>
                                        @if($survey->access_code)
                                            <button type="button" class="btn btn-ghost btn-xs" style="padding:2px 7px;font-size:11px;color:#8C0026;border-color:#fca5a5;height:22px;"
                                                onclick="copyToClipboard('{{ $survey->access_code }}', this, 'Passcode')" title="Copy Passcode">
                                                📋 Copy Pass
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Access Link Box with Copy -->
                                    <div style="display:flex;align-items:center;justify-content:space-between;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:3px 8px;">
                                        <div style="display:flex;align-items:center;gap:6px;overflow:hidden;margin-right:6px;">
                                            <span style="font-size:12px;">🔗</span>
                                            <span style="font-family:monospace;font-size:11px;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:130px;" title="{{ route('survey.show', $survey->token) }}">
                                                ...{{ substr($survey->token, -12) }}
                                            </span>
                                        </div>
                                        <button type="button" class="btn btn-ghost btn-xs" style="padding:2px 7px;font-size:11px;height:22px;"
                                            onclick="copyToClipboard('{{ route('survey.show', $survey->token) }}', this, 'Survey Link')" title="Copy Full Link">
                                            📋 Copy Link
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12.5px;color:var(--text-muted)">
                                @if($survey->status === 'pending')
                                    {{ $survey->expires_at->diffForHumans() }}
                                @else
                                    —
                                @endif
                            </td>
                            <td style="font-size:12px;color:var(--text-muted)">{{ $survey->created_at->format('Y-m-d') }}</td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex;gap:5px;flex-wrap:wrap;justify-content:flex-end;">
                                    @if($survey->status === 'pending')
                                        <form method="POST" action="{{ route('admin.surveys.resend', $survey) }}" style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-ghost btn-xs">🔄 Resend</button>
                                        </form>
                                        <button type="button" class="btn btn-ghost btn-xs" onclick="copyToClipboard('{{ route('survey.show', $survey->token) }}', this, 'Survey Link')">📋 Copy Link</button>
                                        <button type="button" class="btn btn-danger btn-xs"
                                                onclick="openRevokeModal('{{ $survey->id }}', '{{ addslashes($survey->employee_name) }}', '{{ addslashes($survey->employee_id ?: 'ID N/A') }}', '{{ addslashes($survey->company->name) }}', '{{ route('admin.surveys.revoke', $survey) }}')">
                                            ⛔ Revoke
                                        </button>
                                    @elseif($survey->status === 'expired' || $survey->status === 'revoked')
                                        <form method="POST" action="{{ route('admin.surveys.renew', $survey) }}" style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-ghost btn-xs">🔄 Renew</button>
                                        </form>
                                    @else
                                        <span style="color:var(--text-muted);font-size:12px">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--text-muted);padding:50px">
                                No pending exit interview invitations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sent->hasPages())
            <div style="padding:16px 24px;border-top:1px solid var(--border)">
                {{ $sent->links() }}
            </div>
        @endif
    </div>

<!-- 4. Expired & Revoked Invitations Tab -->
@elseif($tab === 'expired')
    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Email</th>
                        <th>Subsidiary</th>
                        <th>Access Passcode / Token</th>
                        <th>Status</th>
                        <th>Expired / Revoked Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expired as $survey)
                        <tr>
                            <td>
                                <div style="font-weight:700;color:var(--text)">{{ $survey->employee_name }}</div>
                                <div style="font-size:11.5px;color:var(--text-muted)">ID: {{ $survey->employee_id ?: 'N/A' }}</div>
                            </td>
                            <td style="font-size:12.5px">{{ $survey->employee_email }}</td>
                            <td><span class="badge badge-info">{{ $survey->company->name }}</span></td>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:5px;min-width:210px;">
                                    <div style="display:flex;align-items:center;gap:6px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:3px 8px;">
                                        <span style="font-size:12px;">🔑</span>
                                        <span style="font-family:monospace;font-size:12px;font-weight:700;color:#64748b;">
                                            {{ $survey->access_code ?: 'NO PASSCODE' }}
                                        </span>
                                        <span style="margin-left:auto;font-size:10.5px;color:#94a3b8;font-style:italic;">(Deactivated)</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($survey->status === 'revoked')
                                    <span class="badge" style="font-weight:700;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;">
                                        ⛔ Revoked
                                    </span>
                                @else
                                    <span class="badge" style="font-weight:700;background:#fef3c7;color:#d97706;border:1px solid #fcd34d;">
                                        ⌛ Expired
                                    </span>
                                @endif
                            </td>
                            <td style="font-size:12px;color:var(--text-muted)">
                                @if($survey->status === 'revoked')
                                    {{ $survey->updated_at->format('Y-m-d H:i') }}
                                @elseif($survey->expires_at)
                                    {{ $survey->expires_at->format('Y-m-d H:i') }}
                                @else
                                    {{ $survey->updated_at->format('Y-m-d H:i') }}
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <form method="POST" action="{{ route('admin.surveys.renew', $survey) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-xs" style="background:#8C0026;border-color:#8C0026;color:#ffffff;display:inline-flex;align-items:center;gap:4px;" title="Issue fresh token and reactivate link for 14 days">
                                        🔄 Renew &amp; Reactivate
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--text-muted);padding:50px">
                                No expired or revoked invitations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expired->hasPages())
            <div style="padding:16px 24px;border-top:1px solid var(--border)">
                {{ $expired->links() }}
            </div>
        @endif
    </div>
@endif

<!-- Quick View Popup Modal -->
<div id="surveyQuickViewModal" class="modal-overlay" style="display:none" onclick="if(event.target===this) closeSurveyQuickView()">
    <div class="modal" style="max-width:880px; width:95%; max-height:88vh; display:flex; flex-direction:column;">
        <div class="modal-header" style="position:sticky; top:0; z-index:20; background:#ffffff; border-bottom:1.5px solid #e2e8f0; padding:18px 24px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="font-size:22px;">📋</div>
                <div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span id="modalEmployeeName" style="font-size:18px; font-weight:800; color:#0f172a;"></span>
                        <span id="modalSourceBadge" style="font-size:11px; font-weight:700; padding:2px 8px; border-radius:12px;"></span>
                    </div>
                    <div style="font-size:12.5px; color:#64748b; margin-top:2px;">
                        <span id="modalDesignation"></span> • <span id="modalCompany" style="font-weight:600; color:#1e293b;"></span>
                    </div>
                </div>
            </div>
            <button class="modal-close" onclick="closeSurveyQuickView()">✕</button>
        </div>

        <div class="modal-body" style="overflow-y:auto; padding:22px 26px; display:flex; flex-direction:column; gap:22px;">
            <!-- Particulars Card -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px;">
                <div style="font-size:11.5px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px;">
                    Candidate Particulars &amp; Timeline
                </div>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; font-size:13px;">
                    <div>
                        <div style="color:#64748b; font-size:11px; font-weight:600;">EPF / Staff ID</div>
                        <div id="modalStaffId" style="font-weight:700; color:#0f172a;"></div>
                    </div>
                    <div>
                        <div style="color:#64748b; font-size:11px; font-weight:600;">Department / Section</div>
                        <div id="modalDepartment" style="font-weight:600; color:#0f172a;"></div>
                    </div>
                    <div>
                        <div style="color:#64748b; font-size:11px; font-weight:600;">Reporting Supervisor</div>
                        <div id="modalSupervisor" style="font-weight:600; color:#0f172a;"></div>
                    </div>
                    <div>
                        <div style="color:#64748b; font-size:11px; font-weight:600;">Date Joined</div>
                        <div id="modalDateJoined" style="font-weight:600; color:#0f172a;"></div>
                    </div>
                    <div>
                        <div style="color:#64748b; font-size:11px; font-weight:600;">Date of Resignation</div>
                        <div id="modalDateResigned" style="font-weight:600; color:#0f172a;"></div>
                    </div>
                    <div>
                        <div style="color:#64748b; font-size:11px; font-weight:600;">Tenure Length</div>
                        <div id="modalTenure" style="font-weight:700; color:#0f172a;"></div>
                    </div>
                    <div>
                        <div style="color:#64748b; font-size:11px; font-weight:600;">Overall Rating</div>
                        <div id="modalOverallRating" style="font-weight:800; color:#d97706; font-size:14px;"></div>
                    </div>
                    <div>
                        <div style="color:#64748b; font-size:11px; font-weight:600;">Recorded Date</div>
                        <div id="modalRecordedAt" style="font-weight:600; color:#0f172a;"></div>
                    </div>
                </div>
            </div>

            <!-- Experience Assessment Matrix Section -->
            <div id="modalMatrixSection" style="display:none;">
                <div style="font-size:13.5px; font-weight:700; color:#0f172a; margin-bottom:10px; display:flex; align-items:center; justify-content:space-between;">
                    <span>📊 Experience Assessment Matrix (20 Specific Areas)</span>
                    <span id="modalMatrixAvg" style="font-size:12px; color:#d97706; font-weight:700;"></span>
                </div>
                <div style="border:1px solid #e2e8f0; border-radius:10px; overflow:hidden;">
                    <table style="width:100%; border-collapse:collapse; font-size:12.5px;">
                        <thead>
                            <tr style="background:#f1f5f9; border-bottom:1.5px solid #e2e8f0;">
                                <th style="padding:8px 12px; width:35px; text-align:center;">#</th>
                                <th style="padding:8px 12px; text-align:left;">Area / Factor</th>
                                <th style="padding:8px 12px; width:70px; text-align:center;">Score</th>
                                <th style="padding:8px 12px; width:130px; text-align:left;">Rating</th>
                            </tr>
                        </thead>
                        <tbody id="modalMatrixBody"></tbody>
                    </table>
                </div>
            </div>

            <!-- Resignation Reasons -->
            <div style="border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px;">
                <div style="font-size:13.5px; font-weight:700; color:#0f172a; margin-bottom:12px;">
                    🎯 Reasons for Leaving &amp; Work Experience Liked
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-size:11.5px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:4px;">Main Reason to Leave</div>
                    <div id="modalMainReason" style="font-size:13.5px; font-weight:600; color:#1e293b; padding:10px 14px; background:#f0fdf4; border-radius:8px; border-left:4px solid #16a34a;"></div>
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-size:11.5px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:6px;">Other Contributing Factors</div>
                    <div id="modalOtherReasons" style="display:flex; flex-wrap:wrap; gap:6px;"></div>
                </div>
                <div>
                    <div style="font-size:11.5px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:6px;">Liked Most About Work Experience</div>
                    <div id="modalLikedAspects" style="display:flex; flex-wrap:wrap; gap:6px;"></div>
                </div>
            </div>

            <!-- Qualitative Feedback -->
            <div style="border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px;">
                <div style="font-size:13.5px; font-weight:700; color:#0f172a; margin-bottom:12px;">
                    💬 Qualitative Feedback &amp; Retention Insights
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:14px;">
                    <div>
                        <div style="font-size:11.5px; font-weight:700; color:#166534; text-transform:uppercase; margin-bottom:4px;">✅ Enjoyed Most</div>
                        <div id="modalEnjoyedMost" style="font-size:12.5px; line-height:1.6; padding:10px 12px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; min-height:55px;"></div>
                    </div>
                    <div>
                        <div style="font-size:11.5px; font-weight:700; color:#991b1b; text-transform:uppercase; margin-bottom:4px;">🛡️ Prevented Resignation</div>
                        <div id="modalPreventedResignation" style="font-size:12.5px; line-height:1.6; padding:10px 12px; background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; min-height:55px;"></div>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:14px; padding:12px; background:#f8fafc; border-radius:8px;">
                    <div>
                        <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Recommend Company?</div>
                        <div id="modalRecommend" style="margin-top:4px;"></div>
                    </div>
                    <div>
                        <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Open to Reapply?</div>
                        <div id="modalReapply" style="margin-top:4px;"></div>
                    </div>
                </div>
                <div>
                    <div style="font-size:11.5px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:4px;">Additional Comments</div>
                    <div id="modalOtherComments" style="font-size:12.5px; line-height:1.6; padding:10px 12px; background:#ffffff; border:1px solid #e2e8f0; border-radius:8px;"></div>
                </div>
            </div>

            <!-- Signatures -->
            <div style="border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px;">
                <div style="font-size:13.5px; font-weight:700; color:#0f172a; margin-bottom:12px;">
                    ✍️ Signatures &amp; Approvals
                </div>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px; font-size:12px;">
                    <div style="padding:10px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
                        <div style="font-size:10.5px; font-weight:700; color:#64748b;">Team Member Signature</div>
                        <div id="modalSigMember" style="font-weight:700; color:#0f172a; margin-top:2px;"></div>
                        <div id="modalSigMemberDate" style="color:#64748b; font-size:11px;"></div>
                    </div>
                    <div style="padding:10px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
                        <div style="font-size:10.5px; font-weight:700; color:#64748b;">Director - Group HR</div>
                        <div id="modalSigHR" style="font-weight:600; color:#0f172a; margin-top:2px;"></div>
                        <div id="modalSigHRDate" style="color:#64748b; font-size:11px;"></div>
                    </div>
                    <div style="padding:10px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
                        <div style="font-size:10.5px; font-weight:700; color:#64748b;">Head of Subsidiary</div>
                        <div id="modalSigHead" style="font-weight:600; color:#0f172a; margin-top:2px;"></div>
                        <div id="modalSigHeadDate" style="color:#64748b; font-size:11px;"></div>
                    </div>
                    <div style="padding:10px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
                        <div style="font-size:10.5px; font-weight:700; color:#64748b;">Group Chairman</div>
                        <div id="modalSigChairman" style="font-weight:600; color:#0f172a; margin-top:2px;"></div>
                        <div id="modalSigChairmanDate" style="color:#64748b; font-size:11px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer" style="padding:14px 24px; border-top:1.5px solid #e2e8f0; background:#ffffff; justify-content:space-between; align-items:center;">
            <div>
                <a id="modalFullDossierLink" href="#" class="btn btn-ghost btn-sm" target="_blank">
                    🔗 Open Full Dossier
                </a>
            </div>
            <div style="display:flex; gap:8px;">
                <a id="modalPdfLink" href="#" class="btn btn-secondary btn-sm" target="_blank">
                    📥 Print / View PDF
                </a>
                <button type="button" class="btn btn-primary btn-sm" onclick="closeSurveyQuickView()">
                    Done
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Revoke Confirmation Modal Popup -->
<div id="revokeConfirmModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border-radius: 16px; max-width: 480px; width: 100%; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); border: 1px solid #e2e8f0; overflow: hidden; animation: scaleUp 0.15s ease-out;">
        <!-- Modal Header -->
        <div style="display: flex; align-items: flex-start; gap: 14px; padding: 22px 24px 16px; position: relative;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div style="flex: 1; padding-right: 24px;">
                <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.3;">Revoke Survey Invitation?</h3>
                <p style="font-size: 12.5px; color: #64748b; margin: 3px 0 0; line-height: 1.4;">
                    Deactivating this invitation will immediately block the employee from opening or submitting this exit survey.
                </p>
            </div>
            <button type="button" onclick="closeRevokeModal()" style="position: absolute; top: 16px; right: 18px; background: none; border: none; font-size: 22px; color: #94a3b8; cursor: pointer; line-height: 1; padding: 4px;">&times;</button>
        </div>

        <!-- Employee Info Details -->
        <div style="margin: 0 24px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; flex-direction: column; gap: 6px;">
            <div style="display: flex; justify-content: space-between; font-size: 12.5px;">
                <span style="color: #64748b; font-weight: 500;">Employee Name:</span>
                <strong id="revokeEmpName" style="color: #0f172a; font-weight: 700;">—</strong>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12.5px;">
                <span style="color: #64748b; font-weight: 500;">ID &amp; Subsidiary:</span>
                <span id="revokeEmpMeta" style="color: #334155; font-weight: 600;">—</span>
            </div>
        </div>

        <!-- Impact Warning Notice -->
        <div style="margin: 0 24px 20px; padding: 12px 14px; background: #FFF1F2; border: 1px solid #FECDD3; border-left: 4px solid #E11D48; border-radius: 8px; font-size: 12px; color: #881337; line-height: 1.5;">
            ⚠️ <strong>Action Impact:</strong> The access link and unique token will become invalid immediately. If the employee visits the survey URL, they will receive a message that access has been revoked. You can renew it later if needed.
        </div>

        <!-- Modal Actions Footer -->
        <form id="revokeActionForm" method="POST" action="">
            @csrf
            <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeRevokeModal()" style="font-weight: 600;">
                    Cancel
                </button>
                <button type="submit" class="btn btn-danger btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.25);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                    </svg>
                    Yes, Revoke Access
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const octAreaLabelsJs = {
    1: 'Welcome and orientation',
    2: 'Training to perform the job',
    3: 'Opportunities for development and career advancement',
    4: 'Type/scope of work that was expected to do',
    5: 'Satisfaction with the workload assigned',
    6: 'Resources made available to perform the job',
    7: 'Basic salary offered',
    8: 'Benefits offered',
    9: 'Direction and guidance from the immediate supervisor',
    10: 'Approachability of the immediate supervisor',
    11: 'My ideas and concerns were given a good hearing',
    12: 'Teamwork and collaboration within the department',
    13: 'Teamwork and collaboration with other departments',
    14: 'Open communication between management and the team',
    15: 'Rewards and recognition for my work performance',
    16: 'Ability to take leave/ off for my personal commitments',
    17: 'Having work-life balance',
    18: 'Feeling respected and valued as a unique person',
    19: 'Overall work environment and existence of ‘Api Culture’',
    20: 'Approachability and supportiveness of Group HR'
};

const scaleLabelsJs = {
    5: 'Very Satisfied',
    4: 'Satisfied',
    3: 'Neutral',
    2: 'Dissatisfied',
    1: 'Very Dissatisfied'
};

function openSurveyQuickView(btn) {
    const raw = btn.getAttribute('data-survey');
    if (!raw) return;

    let survey;
    try {
        survey = JSON.parse(decodeURIComponent(escape(atob(raw))));
    } catch (e) {
        try {
            survey = JSON.parse(raw);
        } catch (e2) {
            console.error('Failed to parse survey data:', e2);
            return;
        }
    }

    document.getElementById('modalEmployeeName').textContent = survey.name;
    document.getElementById('modalStaffId').textContent = survey.employee_id || '—';
    document.getElementById('modalDesignation').textContent = survey.designation || '—';
    document.getElementById('modalCompany').textContent = survey.company + (survey.company_code ? ` (${survey.company_code})` : '');
    document.getElementById('modalDepartment').textContent = survey.department + (survey.section_division && survey.section_division !== survey.department ? ` / ${survey.section_division}` : '');
    document.getElementById('modalSupervisor').textContent = survey.supervisor_name || '—';
    document.getElementById('modalDateJoined').textContent = survey.date_joined || '—';
    document.getElementById('modalDateResigned').textContent = survey.date_of_resignation || '—';
    document.getElementById('modalTenure').textContent = survey.tenure || '—';
    document.getElementById('modalRecordedAt').textContent = survey.submitted_at || '—';

    // Source badge
    const badge = document.getElementById('modalSourceBadge');
    if (survey.is_system) {
        badge.textContent = '⚙️ System Generated';
        badge.style.background = '#e0e7ff';
        badge.style.color = '#3730a3';
    } else {
        badge.textContent = '👤 Online Form';
        badge.style.background = '#dcfce7';
        badge.style.color = '#15803d';
    }

    // Overall rating
    document.getElementById('modalOverallRating').textContent = survey.overall_satisfaction ? `⭐ ${survey.overall_satisfaction} / 5.0` : '—';

    // Matrix section
    const matrixSec = document.getElementById('modalMatrixSection');
    const matrixBody = document.getElementById('modalMatrixBody');
    matrixBody.innerHTML = '';

    const resp = survey.response || {};
    if (resp.area_ratings && typeof resp.area_ratings === 'object') {
        matrixSec.style.display = 'block';
        document.getElementById('modalMatrixAvg').textContent = survey.overall_satisfaction ? `Average: ⭐ ${survey.overall_satisfaction} / 5.0` : '';

        for (let i = 1; i <= 20; i++) {
            const score = resp.area_ratings[i] || resp.area_ratings[String(i)];
            const row = document.createElement('tr');
            row.style.borderBottom = '1px solid #f1f5f9';

            let starHtml = '—';
            let color = '#64748b';
            if (score) {
                const s = parseInt(score);
                if (s >= 4) color = '#16a34a';
                else if (s === 3) color = '#d97706';
                else color = '#dc2626';

                starHtml = `<span style="color:#f59e0b;font-size:12px;">${'★'.repeat(s)}${'☆'.repeat(5 - s)}</span> <span style="font-weight:600;font-size:11px;color:#64748b;">${scaleLabelsJs[s] || ''}</span>`;
            }

            row.innerHTML = `
                <td style="padding:6px 12px; text-align:center; font-weight:700; color:#64748b;">${i}</td>
                <td style="padding:6px 12px; font-weight:600; color:#1e293b;">${octAreaLabelsJs[i] || 'Area ' + i}</td>
                <td style="padding:6px 12px; text-align:center; font-weight:800; color:${color};">${score ? score + ' / 5' : '—'}</td>
                <td style="padding:6px 12px;">${starHtml}</td>
            `;
            matrixBody.appendChild(row);
        }
    } else {
        matrixSec.style.display = 'none';
    }

    // Reasons
    let mainReasonText = resp.main_reason || '—';
    if (resp.overseas_option) {
        mainReasonText += ` <span style="background:#e0e7ff;color:#3730a3;font-size:11px;padding:2px 7px;border-radius:6px;font-weight:700;margin-left:6px;">🌍 ${resp.overseas_option}</span>`;
    }
    document.getElementById('modalMainReason').innerHTML = mainReasonText;

    // Other reasons badges
    const otherContainer = document.getElementById('modalOtherReasons');
    otherContainer.innerHTML = '';
    const otherArr = Array.isArray(resp.other_reasons) ? resp.other_reasons : [];
    if (otherArr.length > 0) {
        otherArr.forEach(reason => {
            const span = document.createElement('span');
            span.style.cssText = 'background:#fef3c7;color:#92400e;font-size:11.5px;padding:4px 10px;border-radius:6px;font-weight:600;';
            span.textContent = reason;
            otherContainer.appendChild(span);
        });
    } else {
        otherContainer.innerHTML = '<span style="color:#94a3b8;font-size:12px;">None specified</span>';
    }

    // Liked aspects badges
    const likedContainer = document.getElementById('modalLikedAspects');
    likedContainer.innerHTML = '';
    const likedArr = Array.isArray(resp.liked_aspects) ? resp.liked_aspects : [];
    if (likedArr.length > 0) {
        likedArr.forEach(item => {
            const span = document.createElement('span');
            span.style.cssText = 'background:#e0f2fe;color:#0369a1;font-size:11.5px;padding:4px 10px;border-radius:6px;font-weight:600;';
            span.textContent = '👍 ' + item;
            likedContainer.appendChild(span);
        });
    } else {
        likedContainer.innerHTML = '<span style="color:#94a3b8;font-size:12px;">None specified</span>';
    }

    // Qualitative
    document.getElementById('modalEnjoyedMost').textContent = resp.enjoyed_most || '—';
    document.getElementById('modalPreventedResignation').textContent = resp.prevented_resignation || '—';

    // Recommend badge
    const recEl = document.getElementById('modalRecommend');
    const recVal = (resp.recommend_company || '').toLowerCase();
    if (recVal === 'yes') {
        recEl.innerHTML = '<span style="background:#dcfce7;color:#15803d;padding:3px 9px;border-radius:12px;font-size:11.5px;font-weight:700;">✅ Yes (Recommends)</span>';
    } else if (recVal === 'no') {
        recEl.innerHTML = '<span style="background:#fee2e2;color:#b91c1c;padding:3px 9px;border-radius:12px;font-size:11.5px;font-weight:700;">❌ No</span>';
    } else {
        recEl.textContent = '—';
    }

    // Reapply badge
    const reapEl = document.getElementById('modalReapply');
    const reapVal = (resp.open_to_reapply || '').toLowerCase();
    if (reapVal === 'yes') {
        reapEl.innerHTML = '<span style="background:#dcfce7;color:#15803d;padding:3px 9px;border-radius:12px;font-size:11.5px;font-weight:700;">✅ Yes (Would Reapply)</span>';
    } else if (reapVal === 'no') {
        reapEl.innerHTML = '<span style="background:#fee2e2;color:#b91c1c;padding:3px 9px;border-radius:12px;font-size:11.5px;font-weight:700;">❌ No</span>';
    } else {
        reapEl.textContent = '—';
    }

    document.getElementById('modalOtherComments').textContent = resp.other_comments || 'No additional comments provided.';

    // Signatures
    document.getElementById('modalSigMember').textContent = resp.team_member_signature || survey.name;
    document.getElementById('modalSigMemberDate').textContent = resp.team_member_signed_date ? `Date: ${resp.team_member_signed_date}` : '';

    document.getElementById('modalSigHR').textContent = resp.reviewed_director_hr || 'Pending Review';
    document.getElementById('modalSigHRDate').textContent = resp.reviewed_director_hr_date ? `Date: ${resp.reviewed_director_hr_date}` : '';

    document.getElementById('modalSigHead').textContent = resp.reviewed_company_head || 'Pending Review';
    document.getElementById('modalSigHeadDate').textContent = resp.reviewed_company_head_date ? `Date: ${resp.reviewed_company_head_date}` : '';

    document.getElementById('modalSigChairman').textContent = resp.reviewed_group_chairman || 'Pending Review';
    document.getElementById('modalSigChairmanDate').textContent = resp.reviewed_group_chairman_date ? `Date: ${resp.reviewed_group_chairman_date}` : '';

    // Action links
    document.getElementById('modalFullDossierLink').href = survey.show_url;
    document.getElementById('modalPdfLink').href = survey.pdf_url;

    document.getElementById('surveyQuickViewModal').style.display = 'flex';
}

function closeSurveyQuickView() {
    document.getElementById('surveyQuickViewModal').style.display = 'none';
}

function copyToClipboard(text, btn, label) {
    if (!navigator.clipboard) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            if (btn) showCopiedFeedback(btn);
        } catch (err) {}
        document.body.removeChild(textArea);
        return;
    }

    navigator.clipboard.writeText(text).then(function() {
        if (btn) {
            showCopiedFeedback(btn);
        } else {
            alert((label || 'Text') + ' copied to clipboard!');
        }
    });
}

function showCopiedFeedback(btn) {
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '✓ Copied!';
    btn.style.background = '#10b981';
    btn.style.color = '#ffffff';
    btn.style.borderColor = '#10b981';
    setTimeout(function() {
        btn.innerHTML = originalHtml;
        btn.style.background = '';
        btn.style.color = '';
        btn.style.borderColor = '';
    }, 2000);
}

function copyLink(url, btn) {
    copyToClipboard(url, btn, 'Survey link');
}

function openRevokeModal(surveyId, empName, empId, companyName, actionUrl) {
    document.getElementById('revokeEmpName').textContent = empName;
    document.getElementById('revokeEmpMeta').textContent = empId + ' • ' + companyName;
    document.getElementById('revokeActionForm').action = actionUrl;
    const modal = document.getElementById('revokeConfirmModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeRevokeModal() {
    const modal = document.getElementById('revokeConfirmModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close revoke modal on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('revokeConfirmModal');
    if (e.target === modal) {
        closeRevokeModal();
    }
});

// Close revoke modal on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRevokeModal();
    }
});
</script>
@endpush
