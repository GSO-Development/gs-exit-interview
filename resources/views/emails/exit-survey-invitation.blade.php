<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Interview Invitation — {{ $survey->company->name ?? 'George Steuart Group' }}</title>
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
            margin: 0 0 6px 0;
        }
        .header .subtitle {
            font-size: 13.5px;
            color: #f1f5f9;
            font-weight: 500;
            margin: 0;
            opacity: 0.95;
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
        .signoff {
            margin: 20px 0 24px 0;
            font-size: 14.5px;
            color: #334155;
            line-height: 1.6;
        }
        .signoff strong {
            color: #8C0026;
        }
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
        .security-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 28px;
            text-align: center;
        }
        .security-title {
            font-size: 11.5px;
            font-weight: 800;
            color: #166534;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .security-text {
            font-size: 13.5px;
            color: #15803d;
            line-height: 1.55;
        }
        .btn-wrapper {
            text-align: center;
            margin: 0 0 24px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #8C0026 0%, #B30031 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 15px 32px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(140, 0, 38, 0.25);
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="label">George Steuart Group</div>
            <h1>Exit Interview Invitation</h1>
            <div class="subtitle">Online Exit Interview – Your Feedback Matters</div>
        </div>

        <div class="body">
            <div class="name">Dear {{ $survey->employee_name }},</div>

            <p>
                As part of our exit process, we kindly invite you to complete this Online Exit Interview Questionnaire. We value your honest feedback and encourage you to share your views, experiences and suggestions openly, in relation to your tenure with the company.
            </p>

            <p>
                Your responses will be treated with confidentiality and will help us identify opportunities for improvement and further strengthen our team member experience and practices. Please be assured that your honest and constructive feedback will be used solely for its intended purpose, treated with respect and impartiality, and will not be used to disadvantage you in any way.
            </p>

            <p>
                We sincerely appreciate you taking the time to complete the questionnaire and sharing your valuable feedback with us.
            </p>

            <div class="signoff">
                Best Regards,<br>
                <strong>Group Human Resources</strong>
            </div>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Company</span>
                    <span class="info-value">{{ $survey->company->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Team Member No</span>
                    <span class="info-value">{{ $survey->employee_id ?: '—' }}</span>
                </div>
            </div>

            <div class="security-box">
                <div class="security-title">🔐 TWO-STEP SECURITY VERIFICATION</div>
                <div class="security-text">
                    For security and data protection purposes, your Access Passcode has been sent to this same email address, in a separate email.
                </div>
            </div>

            <div class="btn-wrapper">
                <a href="{{ route('survey.show', $survey->token) }}" class="btn">
                    Start Exit Interview Questionnaire ➔
                </a>
            </div>

            <p class="notice">
                🔒 This is a secure single-use survey link. Please enter such confidential Access Passcode to unlock the questionnaire.
            </p>
        </div>

        <div class="footer">
            <div class="brand">GEORGE STEUART GROUP</div>
            HR Intelligence & People Analytics Platform
        </div>
    </div>
</body>
</html>
