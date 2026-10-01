<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #fdf2f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { background: linear-gradient(135deg, #8C0026, #B30031); padding: 40px 40px 32px; text-align: center; }
        .header .label { font-size: 11px; letter-spacing: 2px; color: #f0c060; text-transform: uppercase; margin-bottom: 8px; }
        .header h1 { font-size: 22px; font-weight: 800; color: #fff; margin: 0; }
        .body { padding: 40px; }
        .body p { font-size: 14px; color: #475569; line-height: 1.7; margin-bottom: 16px; }
        .name { font-size: 18px; font-weight: 700; color: #8C0026; margin-bottom: 24px; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 28px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        .info-label { color: #94a3b8; font-weight: 600; }
        .info-value { color: #1e293b; font-weight: 600; }
        .btn { display: block; text-align: center; background: linear-gradient(135deg, #8C0026, #B30031); color: #fff !important; text-decoration: none; padding: 16px 32px; border-radius: 10px; font-size: 15px; font-weight: 700; margin: 0 auto 28px; max-width: 300px; }
        .notice { font-size: 12px; color: #94a3b8; line-height: 1.6; text-align: center; }
        .footer { background: #520016; padding: 24px 40px; text-align: center; color: #e2e8f0; font-size: 12px; }
        .footer .brand { color: #f0c060; font-weight: 700; letter-spacing: 1px; margin-bottom: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="label">George Steuart Group</div>
            <h1>Exit Interview Invitation</h1>
        </div>
        <div class="body">
            <div class="name">Dear {{ $survey->employee_name }},</div>
            <p>As part of our commitment to continuous improvement and employee well-being, we kindly invite you to complete a confidential <strong>Exit Interview Questionnaire</strong>.</p>
            <p>Your honest feedback is invaluable to us. This information will be used to improve our workplace and create a better environment for future employees.</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Company</span>
                    <span class="info-value">{{ $survey->company->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Your Designation</span>
                    <span class="info-value">{{ $survey->designation }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Survey Expires</span>
                    <span class="info-value">{{ $survey->expires_at->format('F d, Y') }} ({{ $survey->daysRemaining() }} days)</span>
                </div>
            </div>

            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px 20px;margin-bottom:28px;text-align:center;">
                <div style="font-size:12px;font-weight:700;color:#166534;letter-spacing:1px;text-transform:uppercase;margin-bottom:4px;">🔐 Two-Step Security Verification</div>
                <div style="font-size:13.5px;color:#15803d;line-height:1.5;">
                    For your confidentiality and data protection, your <strong>Access Passcode</strong> has been dispatched in a separate email to this same address.
                </div>
            </div>

            <a href="{{ route('survey.show', $survey->token) }}" class="btn">
                📝 Start Exit Interview
            </a>

            <p class="notice">
                🔒 This is a secure single-use survey link. It is valid for <strong>{{ $survey->daysRemaining() }} days</strong> and will expire upon submission.<br>
                Please enter the confidential passcode from the separate email to unlock the questionnaire.<br><br>
                If you did not expect this email or have questions, please contact <a href="mailto:{{ config('mail.from.address') }}" style="color:#8C0026;">HR</a>.
            </p>
        </div>
        <div class="footer">
            <div class="brand">GEORGE STEUART GROUP</div>
            HR Intelligence & People Analytics Platform
        </div>
    </div>
</body>
</html>
