@extends('layouts.admin')

@section('title', 'Dossier — ' . $survey->employee_name)
@section('page-title', 'Executive Dossier')

@section('topbar-actions')
    <a href="{{ route('admin.surveys.index') }}" class="btn btn-ghost btn-sm">← Back to Surveys</a>
    <a href="{{ route('admin.surveys.pdf', $survey) }}" class="btn btn-primary btn-sm" target="_blank">📥 Download PDF</a>
    <a href="{{ route('admin.surveys.word', $survey) }}" class="btn btn-ghost btn-sm">📄 Download Word</a>
@endsection

@section('content')

<div style="max-width:900px;margin:0 auto">

    <!-- Header Card -->
    <div class="card" style="margin-bottom:24px;overflow:hidden">
        <div style="background:linear-gradient(135deg,var(--primary) 0%,var(--primary-light) 100%);padding:32px;color:#fff;position:relative">
            <div style="position:absolute;top:0;right:0;width:200px;height:100%;background:linear-gradient(135deg,transparent,rgba(255,255,255,0.05));pointer-events:none"></div>
            <div style="font-size:11px;font-weight:700;letter-spacing:2px;opacity:0.7;text-transform:uppercase;margin-bottom:8px">
                Official Executive Exit Interview Dossier
                @if($survey->status === 'system_generated' || $survey->submission_source === 'system_generated')
                    <span style="background:rgba(255,255,255,0.25);color:#fff;padding:2px 8px;border-radius:12px;font-size:10.5px;letter-spacing:0.5px;margin-left:8px;">⚙️ System Generated Entry</span>
                @else
                    <span style="background:rgba(255,255,255,0.25);color:#fff;padding:2px 8px;border-radius:12px;font-size:10.5px;letter-spacing:0.5px;margin-left:8px;">👤 Employee Submission</span>
                @endif
            </div>
            <div style="font-size:28px;font-weight:800;margin-bottom:4px">{{ $survey->employee_name }}</div>
            <div style="font-size:15px;opacity:0.85;margin-bottom:16px">{{ $survey->designation }} — {{ $survey->company->name }}</div>
            <div style="display:flex;gap:20px;flex-wrap:wrap">
                <div>
                    <div style="font-size:11px;opacity:0.6;text-transform:uppercase;letter-spacing:1px">Department</div>
                    <div style="font-weight:600">{{ $survey->department }}</div>
                </div>
                <div>
                    <div style="font-size:11px;opacity:0.6;text-transform:uppercase;letter-spacing:1px">Tenure</div>
                    <div style="font-weight:600">{{ $survey->tenureYears() ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;opacity:0.6;text-transform:uppercase;letter-spacing:1px">EPF / Staff ID</div>
                    <div style="font-weight:600">{{ $survey->employee_id ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;opacity:0.6;text-transform:uppercase;letter-spacing:1px">Submitted</div>
                    <div style="font-weight:600">{{ $survey->submitted_at?->format('Y-m-d') ?? '—' }}</div>
                </div>
                @if($survey->overallSatisfaction())
                    <div style="margin-left:auto">
                        <div style="font-size:11px;opacity:0.6;text-transform:uppercase;letter-spacing:1px">Overall Satisfaction</div>
                        <div style="font-size:24px;font-weight:800;color:var(--accent-light)">⭐ {{ $survey->overallSatisfaction() }} / 5.0</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Employment Details -->
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px">
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px">Reporting Supervisor</div>
                    <div style="font-weight:600;color:var(--text)">{{ $survey->supervisor_name ?? $survey->reporting_manager ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px">Section / Division</div>
                    <div style="font-weight:600;color:var(--text)">{{ $survey->section_division ?? $survey->department ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px">Date of Joining</div>
                    <div style="font-weight:600;color:var(--text)">{{ $survey->date_joined?->format('Y-m-d') ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px">Date of Resignation</div>
                    <div style="font-weight:600;color:var(--text)">{{ $survey->date_of_resignation?->format('Y-m-d') ?? $survey->last_working_date?->format('Y-m-d') ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($response = $survey->response)

        @php
            $octAreas = [
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
            $scaleLabels = [
                5 => 'Very Satisfied',
                4 => 'Satisfied',
                3 => 'Neutral',
                2 => 'Dissatisfied',
                1 => 'Very Dissatisfied',
            ];
        @endphp

        <!-- 1. Experience Assessment Matrix (20 Specific Areas) -->
        @if(!empty($response->area_ratings) && is_array($response->area_ratings))
            <div class="card" style="margin-bottom:24px">
                <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="card-title">📊 Experience Assessment Matrix (20 Specific Areas)</span>
                    <span style="font-size:13px;font-weight:700;color:var(--primary);">
                        Average Rating: ⭐ {{ $survey->overallSatisfaction() }} / 5.0
                    </span>
                </div>
                <div class="table-container">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:2px solid var(--border);">
                                <th style="width:40px;text-align:center;">#</th>
                                <th>Specific Area / Factor</th>
                                <th style="width:120px;text-align:center;">Score</th>
                                <th style="width:180px;">Rating Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($octAreas as $num => $areaTitle)
                                @php $score = $response->area_ratings[$num] ?? null; @endphp
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="text-align:center;font-weight:700;color:var(--text-muted);font-size:12px;">{{ $num }}</td>
                                    <td style="font-weight:600;color:var(--text);font-size:13px;">{{ $areaTitle }}</td>
                                    <td style="text-align:center;">
                                        @if($score)
                                            <span style="font-weight:800;font-size:14px;color:{{ $score >= 4 ? '#16a34a' : ($score == 3 ? '#d97706' : '#dc2626') }};">
                                                {{ $score }} / 5
                                            </span>
                                        @else
                                            <span style="color:var(--text-muted);">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($score)
                                            <div style="display:flex;align-items:center;gap:6px;">
                                                <span style="color:#f59e0b;font-size:13px;">
                                                    @for($s = 1; $s <= 5; $s++)
                                                        {{ $s <= $score ? '★' : '☆' }}
                                                    @endfor
                                                </span>
                                                <span style="font-size:11.5px;color:var(--text-muted);font-weight:600;">
                                                    {{ $scaleLabels[$score] ?? '' }}
                                                </span>
                                            </div>
                                        @else
                                            <span style="color:var(--text-muted);">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- 2. Resignation Reasons & Contributing Factors -->
        <div class="card" style="margin-bottom:24px">
            <div class="card-header">
                <span class="card-title">🎯 Reasons for Leaving Company</span>
            </div>
            <div class="card-body">
                <div style="margin-bottom:18px">
                    <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px">Main Reason to Leave</div>
                    <div style="font-size:14px;font-weight:600;color:var(--primary);padding:12px 16px;background:#f0f4f8;border-radius:8px;border-left:4px solid var(--primary-light);display:flex;align-items:center;justify-content:space-between;">
                        <span>{{ $response->main_reason_to_leave ?? $response->primary_resignation_reason ?? '—' }}</span>
                        @if($response->overseas_sub_option)
                            <span class="badge badge-info" style="font-size:12px;">🌍 {{ $response->overseas_sub_option }}</span>
                        @endif
                    </div>
                </div>

                @php
                    $otherReasons = $response->other_reasons_to_leave ?? $response->resignation_factors;
                @endphp
                @if(!empty($otherReasons))
                    <div style="margin-bottom:18px">
                        <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">Other Reasons / Contributing Factors</div>
                        <div style="display:flex;flex-wrap:wrap;gap:8px">
                            @foreach((array)$otherReasons as $factor)
                                <span class="badge badge-warning" style="font-size:12.5px;padding:6px 12px;">{{ $factor }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($response->liked_most_aspects))
                    <div style="margin-bottom:6px">
                        <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">What Was Liked Most About Work Experience</div>
                        <div style="display:flex;flex-wrap:wrap;gap:8px">
                            @foreach((array)$response->liked_most_aspects as $aspect)
                                <span class="badge badge-info" style="font-size:12px;padding:5px 11px;">👍 {{ $aspect }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. Qualitative & Retention Feedback -->
        <div class="card" style="margin-bottom:24px">
            <div class="card-header"><span class="card-title">💬 Qualitative Feedback &amp; Retention Insights</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--success);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">✅ What did you enjoy the most?</div>
                        <div style="font-size:13.5px;line-height:1.7;color:var(--text);padding:14px 16px;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0;min-height:75px">
                            {{ $response->enjoyed_most ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:700;color:#e11d48;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">🛡️ What could have prevented resignation?</div>
                        <div style="font-size:13.5px;line-height:1.7;color:var(--text);padding:14px 16px;background:#fff1f2;border-radius:8px;border:1px solid #fecdd3;min-height:75px">
                            {{ $response->prevented_resignation ?? $response->resignation_elaboration ?? '—' }}
                        </div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;padding:16px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;">
                    <div>
                        <div style="font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Recommend company as a potential employer?</div>
                        @php $recVal = $response->recommend_company ?? $response->would_recommend; @endphp
                        <span class="badge {{ strtolower($recVal) === 'yes' ? 'badge-success' : 'badge-danger' }}" style="font-size:13px;padding:6px 14px;">
                            {{ strtolower($recVal) === 'yes' ? '✅ Yes (Recommends)' : '❌ No' }}
                        </span>
                    </div>
                    <div>
                        <div style="font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Open to reapply for future opportunities?</div>
                        @php $reapplyVal = $response->open_to_reapply ?? $response->would_return; @endphp
                        <span class="badge {{ strtolower($reapplyVal) === 'yes' ? 'badge-success' : 'badge-danger' }}" style="font-size:13px;padding:6px 14px;">
                            {{ strtolower($reapplyVal) === 'yes' ? '✅ Yes (Would Reapply)' : '❌ No' }}
                        </span>
                    </div>
                </div>

                @if($response->other_comments || $response->improvement_suggestions)
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">📝 Additional Comments / Suggestions</div>
                        <div style="font-size:13.5px;line-height:1.7;color:var(--text);padding:14px 16px;background:#ffffff;border-radius:8px;border:1px solid var(--border)">
                            {{ $response->other_comments ?? $response->improvement_suggestions }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 4. Sign-offs & Administrative Reviews -->
        <div class="card" style="margin-bottom:24px">
            <div class="card-header"><span class="card-title">✍️ Signatures &amp; Approvals</span></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:20px;">
                    <div style="padding:14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                        <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px;">Team Member Signature</div>
                        <div style="font-weight:700;font-size:13.5px;color:var(--text);font-family:cursive,sans-serif;">{{ $response->team_member_signature ?? $survey->employee_name }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);margin-top:2px;">Date: {{ $response->team_member_signed_date ?? $survey->submitted_at?->format('Y-m-d') ?? '—' }}</div>
                    </div>
                    <div style="padding:14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                        <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px;">Director - Group HR</div>
                        <div style="font-weight:600;font-size:13px;color:var(--text);">{{ $response->reviewed_director_hr ?? 'Pending Review' }}</div>
                        @if($response->reviewed_director_hr_date)
                            <div style="font-size:11.5px;color:var(--text-muted);margin-top:2px;">Date: {{ $response->reviewed_director_hr_date }}</div>
                        @endif
                    </div>
                    <div style="padding:14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                        <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px;">Head of Subsidiary</div>
                        <div style="font-weight:600;font-size:13px;color:var(--text);">{{ $response->reviewed_company_head ?? 'Pending Review' }}</div>
                        @if($response->reviewed_company_head_date)
                            <div style="font-size:11.5px;color:var(--text-muted);margin-top:2px;">Date: {{ $response->reviewed_company_head_date }}</div>
                        @endif
                    </div>
                    <div style="padding:14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                        <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px;">Group Chairman</div>
                        <div style="font-weight:600;font-size:13px;color:var(--text);">{{ $response->reviewed_group_chairman ?? 'Pending Review' }}</div>
                        @if($response->reviewed_group_chairman_date)
                            <div style="font-size:11.5px;color:var(--text-muted);margin-top:2px;">Date: {{ $response->reviewed_group_chairman_date }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="card" style="text-align:center;padding:60px">
            <div style="font-size:48px;margin-bottom:16px">⏳</div>
            <div style="font-size:16px;font-weight:600;color:var(--text-muted)">Response Not Yet Submitted</div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:8px">The employee has not yet completed the exit questionnaire.</div>
        </div>
    @endif

</div>
@endsection
