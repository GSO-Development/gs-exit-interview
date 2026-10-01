<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Submitted — George Steuart Group</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #8C0026, #66001C); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .success-card {
            background: #fff; border-radius: 20px; padding: 56px 48px; max-width: 520px; width: 100%;
            text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }
        .success-logo-wrap {
            margin-bottom: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            background: #ffffff;
            border-radius: 12px;
            animation: bounce 0.6s ease;
        }
        .success-logo {
            max-height: 72px;
            max-width: 180px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        @keyframes bounce { 0% { transform: scale(0.6); opacity: 0; } 70% { transform: scale(1.05); } 100% { transform: scale(1); opacity: 1; } }
        h1 { font-size: 28px; font-weight: 800; color: #8C0026; margin-bottom: 12px; }
        .message { font-size: 15px; color: #64748b; line-height: 1.7; margin-bottom: 24px; }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }
        .notice { font-size: 12.5px; color: #94a3b8; line-height: 1.6; display: flex; gap: 8px; align-items: flex-start; text-align: left; }
        .brand { margin-top: 32px; font-size: 11px; font-weight: 700; letter-spacing: 2px; color: #c9993a; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-logo-wrap">
            <img src="{{ asset('George_Steuart_Group_Logo.png') }}" alt="George Steuart Group" class="success-logo">
        </div>
        <h1>Thank You!</h1>
        <p class="message">
            Your exit interview questionnaire has been submitted successfully.<br><br>
            We sincerely appreciate your feedback and your contributions to the organization.
            We wish you every success in your future career!
        </p>
        <hr class="divider">
        <div class="notice">
            🔒
            <span>For confidentiality and security, this one-time link has now been <strong>permanently closed</strong>. No further access to this questionnaire is possible.</span>
        </div>
        <div class="brand">George Steuart Group — HR Intelligence</div>
    </div>
</body>
</html>
