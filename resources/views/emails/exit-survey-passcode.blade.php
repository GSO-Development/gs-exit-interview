<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confidential Access Passcode — {{ $survey->company->name ?? 'George Steuart Group' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fdf2f4;
            margin: 0;
            padding: 0;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 24px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
            border: 1px solid #f1e3e7;
        }
        .header {
            background: linear-gradient(135deg, #8C0026 0%, #520016 100%);
            padding: 36px 32px 30px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #c9993a, #f0c060);
        }
        .header .label {
            font-size: 11px;
            letter-spacing: 2px;
            color: #f0c060;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
        }
        .body {
            padding: 36px 32px 32px;
        }
        .name {
            font-size: 17px;
            font-weight: 700;
            color: #8C0026;
            margin-bottom: 18px;
        }
        .body p {
            font-size: 14.5px;
            color: #475569;
            line-height: 1.7;
            margin: 0 0 16px 0;
        }
        
        /* Passcode Highlight Box */
        .passcode-box {
            background: #fffbeb;
            border: 2px dashed #f59e0b;
            border-radius: 14px;
            padding: 24px 20px;
            text-align: center;
            margin: 24px 0;
        }
        .passcode-title {
            font-size: 12px;
            font-weight: 800;
            color: #92400e;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .passcode-code {
            font-size: 32px;
            font-weight: 800;
            color: #78350f;
            letter-spacing: 6px;
            font-family: Consolas, 'Courier New', monospace;
            padding: 10px 22px;
            display: inline-block;
            background: #ffffff;
            border: 2px solid #fde68a;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);
        }
        .passcode-note {
            font-size: 13px;
            color: #b45309;
            margin-top: 12px;
            line-height: 1.5;
            font-weight: 500;
        }

        /* Days Expiration Validity Box */
        .validity-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 2px solid #86efac;
            border-radius: 14px;
            padding: 20px 20px;
            text-align: center;
            margin: 0 0 24px;
        }
        .validity-badge {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 8px;
        }
        .validity-days-highlight {
            font-size: 28px;
            font-weight: 900;
            color: #15803d;
            letter-spacing: 0.5px;
            margin: 4px 0;
            line-height: 1.2;
        }
        .validity-subtext {
            font-size: 13px;
            color: #166534;
            margin-top: 6px;
        }

        /* Details info box */
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #8C0026;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13.5px;
            padding: 4px 0;
        }
        .info-row:not(:last-child) {
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 6px;
        }
        .info-label {
            color: #64748b;
            font-weight: 600;
        }
        .info-value {
            color: #0f172a;
            font-weight: 700;
            text-align: right;
        }

        .notice {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.6;
            text-align: center;
            margin: 0 auto;
            max-width: 520px;
        }
        .footer {
            background: #520016;
            padding: 24px 32px;
            text-align: center;
            color: #cbd5e1;
            font-size: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .footer .brand {
            color: #f0c060;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
            font-size: 12.5px;
        }

        @media only screen and (max-width: 480px) {
            .body { padding: 24px 18px; }
            .header { padding: 28px 18px 24px; }
            .passcode-code { font-size: 24px; letter-spacing: 4px; padding: 6px 12px; }
            .validity-days-highlight { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="label">George Steuart Group</div>
            <h1>Confidential Access Passcode</h1>
        </div>

        <div class="body">
            <div class="name">Dear {{ $survey->employee_name }},</div>

            <p>
                In accordance with our strict security and data protection policy, this email contains your confidential Access Passcode.
            </p>

            <p>
                Please open the survey link sent to you in a separate e-mail with the subject ‘Exit Interview Invitation’ and enter the passcode provided below, on the verification screen to unlock your questionnaire.
            </p>

            <!-- Passcode Highlight -->
            <div class="passcode-box">
                <div class="passcode-title">YOUR CONFIDENTIAL PASSCODE</div>
                <div class="passcode-code">{{ $survey->access_code }}</div>
                <div class="passcode-note">
                    Enter this exact code into the verification portal.
                </div>
            </div>

            <!-- Passcode Validity Period -->
            <div class="validity-box">
                <div class="validity-badge">PASSCODE VALIDITY PERIOD</div>
                <div class="validity-days-highlight">
                    {{ $survey->daysRemaining() }} {{ \Illuminate\Support\Str::plural('Day', $survey->daysRemaining()) }} Remaining
                </div>
                <div class="validity-subtext">
                    This passcode is uniquely tied to your exit interview questionnaire session.
                </div>
            </div>

            <!-- Organization & Team Member Info -->
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Organization</span>
                    <span class="info-value">{{ $survey->company->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Team Member No</span>
                    <span class="info-value">{{ $survey->employee_id ?: '—' }}</span>
                </div>
            </div>

            <p class="notice">
                🔒 <strong>Strictly Confidential &amp; Single-Use:</strong> This passcode is uniquely paired with your survey link. Do not share this code or forward this email to anyone.
            </p>
        </div>

        <div class="footer">
            <div class="brand">GEORGE STEUART GROUP</div>
            HR Intelligence &amp; People Analytics Platform
        </div>
    </div>
</body>
</html>
