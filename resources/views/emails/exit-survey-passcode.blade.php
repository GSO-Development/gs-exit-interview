<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confidential Passcode</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #fdf2f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 24px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07); }
        .header { background: linear-gradient(135deg, #8C0026, #520016); padding: 36px 32px 30px; text-align: center; }
        .header .label { font-size: 11px; letter-spacing: 2px; color: #f0c060; text-transform: uppercase; margin-bottom: 6px; font-weight: 700; }
        .header h1 { font-size: 22px; font-weight: 800; color: #ffffff; margin: 0; }
        .body { padding: 36px 32px 32px; }
        .body p { font-size: 14.5px; color: #475569; line-height: 1.7; margin-bottom: 16px; }
        .name { font-size: 18px; font-weight: 700; color: #8C0026; margin-bottom: 20px; }
        
        /* Passcode Highlight Box */
        .passcode-box {
            background: #fffbeb;
            border: 2px dashed #f59e0b;
            border-radius: 14px;
            padding: 24px 20px;
            text-align: center;
            margin: 20px 0 24px;
        }
        .passcode-title {
            font-size: 11.5px;
            font-weight: 700;
            color: #92400e;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .passcode-code {
            font-size: 32px;
            font-weight: 800;
            color: #78350f;
            letter-spacing: 6px;
            font-family: Consolas, 'Courier New', monospace;
            padding: 8px 18px;
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
        }

        /* Large Days Expiration Countdown Box */
        .validity-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 2px solid #86efac;
            border-radius: 14px;
            padding: 22px 20px;
            text-align: center;
            margin: 0 0 26px;
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
            font-size: 30px;
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
        .validity-subtext strong {
            color: #14532d;
        }

        /* Details info */
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 13px;
        }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 6px; }
        .info-row:last-child { margin-bottom: 0; }
        .info-label { color: #94a3b8; font-weight: 600; }
        .info-value { color: #1e293b; font-weight: 600; }

        .notice { font-size: 12px; color: #94a3b8; line-height: 1.6; text-align: center; }
        .footer { background: #520016; padding: 22px 30px; text-align: center; color: #e2e8f0; font-size: 12px; }
        .footer .brand { color: #f0c060; font-weight: 700; letter-spacing: 1px; margin-bottom: 4px; }

        @media only screen and (max-width: 480px) {
            .body { padding: 24px 18px; }
            .header { padding: 28px 18px 24px; }
            .passcode-code { font-size: 24px; letter-spacing: 4px; padding: 6px 12px; }
            .validity-days-highlight { font-size: 26px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="label">{{ $survey->company->name ?? 'George Steuart Group' }}</div>
            <h1>Confidential Access Passcode</h1>
        </div>
        <div class="body">
            <div class="name">Dear {{ $survey->employee_name }},</div>
            <p>
                In accordance with our strict confidentiality and data protection policy, this email contains your private <strong>Access Passcode</strong>.
            </p>
            <p>
                Please open the survey link sent in your separate <strong>Exit Interview Invitation</strong> email and enter this passcode on the verification screen to unlock your questionnaire.
            </p>

            <!-- Passcode Highlight -->
            <div class="passcode-box">
                <div class="passcode-title">🔑 Your Confidential Passcode</div>
                <div class="passcode-code">{{ $survey->access_code }}</div>
                <div class="passcode-note">
                    Copy or enter this exact code into the verification portal.
                </div>
            </div>

            <!-- Prominent Days Remaining / Expiration Countdown Box -->
            <div class="validity-box">
                <div class="validity-badge">⏳ Passcode Validity Period</div>
                <div class="validity-days-highlight">
                    {{ $survey->daysRemaining() }} {{ \Illuminate\Support\Str::plural('Day', $survey->daysRemaining()) }} Remaining
                </div>
                <div class="validity-subtext">
                    This passcode will expire on <strong>{{ $survey->expires_at->format('F d, Y') }}</strong> (at {{ $survey->expires_at->format('h:i A') }}).
                </div>
            </div>

            <!-- Meta details -->
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Organization</span>
                    <span class="info-value">{{ $survey->company->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Recipient Email</span>
                    <span class="info-value">{{ $survey->employee_email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Expiration Date</span>
                    <span class="info-value">{{ $survey->expires_at->format('M d, Y') }}</span>
                </div>
            </div>

            <p class="notice">
                🔒 <strong>Strictly Confidential &amp; Single-Use:</strong> This passcode is uniquely paired with your survey token. Do not share this code or forward this email to anyone.<br><br>
                If you did not receive the invitation email containing your survey link, please check your spam folder or contact <a href="mailto:{{ config('mail.from.address') }}" style="color:#8C0026;font-weight:600;">HR Support</a>.
            </p>
        </div>
        <div class="footer">
            <div class="brand">GEORGE STEUART GROUP</div>
            HR Intelligence &amp; People Analytics Platform
        </div>
    </div>
</body>
</html>
