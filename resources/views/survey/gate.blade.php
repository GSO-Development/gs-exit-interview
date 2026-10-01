<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confidential Exit Interview Access — {{ $survey->company->name ?? 'George Steuart Group' }}</title>
    <meta name="description" content="George Steuart Group Confidential Exit Interview Passcode Authentication">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8C0026;
            --primary-light: #B30031;
            --primary-dark: #66001C;
            --accent: #c9993a;
            --surface: #ffffff;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at 10% 15%, rgba(140, 0, 38, 0.08) 0%, transparent 45%),
                        radial-gradient(circle at 90% 75%, rgba(140, 0, 38, 0.06) 0%, transparent 45%),
                        #f8fafc;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .gate-card {
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.1);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .gate-header {
            background: linear-gradient(135deg, #8C0026 0%, #520016 100%);
            padding: 36px 32px 30px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }

        .gate-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #c9993a, #f0c060);
        }

        .gate-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 8px 18px;
            border-radius: 14px;
            margin: 0 auto 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            max-width: 90%;
        }

        .gate-logo {
            height: 44px;
            max-width: 170px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .gate-company {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #f0c060;
            margin-bottom: 6px;
        }

        .gate-title {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .gate-subtitle {
            font-size: 13px;
            color: #94a3b8;
        }

        .gate-body {
            padding: 36px 32px 32px;
        }

        .recipient-badge {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff0f3;
            color: #8C0026;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            border: 1px solid #fecdd3;
        }

        .recipient-info {
            overflow: hidden;
            flex: 1;
        }

        .recipient-name {
            font-size: 14px;
            font-weight: 700;
            color: #8C0026;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .recipient-sub {
            font-size: 12px;
            color: #64748b;
            margin-top: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        .passcode-input {
            width: 100%;
            padding: 14px 18px;
            font-family: monospace;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 4px;
            text-align: center;
            text-transform: uppercase;
            border: 2px solid #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .passcode-input:focus {
            outline: none;
            border-color: #8C0026;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(140, 0, 38, 0.12);
        }

        .passcode-input::placeholder {
            letter-spacing: 2px;
            font-size: 14px;
            font-weight: 500;
            color: #94a3b8;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #8C0026 0%, #B30031 100%);
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(140, 0, 38, 0.25);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(140, 0, 38, 0.35);
            background: linear-gradient(135deg, #66001C 0%, #8C0026 100%);
        }

        .gate-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 11.5px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .expiry-notice {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #fffbeb;
            color: #b45309;
            font-weight: 600;
            border-radius: 16px;
            font-size: 11px;
            margin-top: 12px;
        }

        /* ── Mobile Responsive Styles ───────────────────────────────── */
        @media (max-width: 520px) {
            body {
                padding: 16px 12px;
            }

            .gate-card {
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            }

            .gate-header {
                padding: 28px 18px 22px;
            }

            .gate-logo-wrap {
                padding: 6px 14px;
                margin-bottom: 12px;
                border-radius: 12px;
            }

            .gate-logo {
                height: 38px;
                max-width: 140px;
            }

            .gate-company {
                font-size: 10px;
                letter-spacing: 1.5px;
            }

            .gate-title {
                font-size: 18px;
            }

            .gate-subtitle {
                font-size: 12px;
            }

            .gate-body {
                padding: 24px 18px 24px;
            }

            .recipient-badge {
                padding: 10px 12px;
                margin-bottom: 20px;
                gap: 10px;
            }

            .avatar-circle {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }

            .recipient-name {
                font-size: 13.5px;
            }

            .recipient-sub {
                font-size: 11.5px;
            }

            .passcode-input {
                font-size: 18px;
                letter-spacing: 3px;
                padding: 12px 14px;
            }

            .passcode-input::placeholder {
                font-size: 12.5px;
                letter-spacing: 1.5px;
            }

            .btn-submit {
                padding: 13px 18px;
                font-size: 14px;
                min-height: 48px;
            }
        }

        @media (max-width: 380px) {
            body {
                padding: 10px 8px;
            }

            .gate-header {
                padding: 22px 14px 18px;
            }

            .gate-body {
                padding: 20px 14px 20px;
            }

            .passcode-input {
                font-size: 15px;
                letter-spacing: 2px;
                padding: 10px 8px;
            }

            .passcode-input::placeholder {
                font-size: 11px;
                letter-spacing: 1px;
            }

            .btn-submit {
                font-size: 13.5px;
            }
        }
    </style>
</head>
<body>

<div class="gate-card">
    <div class="gate-header">
        <div class="gate-logo-wrap">
            <img src="{{ asset('George_Steuart_Group_Logo.png') }}" alt="{{ $survey->company->name ?? 'George Steuart Group' }}" class="gate-logo">
        </div>
        <div class="gate-company">{{ $survey->company->name ?? 'George Steuart Group' }}</div>
        <h1 class="gate-title">Exit Interview Portal</h1>
        <p class="gate-subtitle">Confidential Access Verification</p>
    </div>

    <div class="gate-body">
        <div class="recipient-badge">
            <div class="avatar-circle">
                {{ strtoupper(substr($survey->employee_name, 0, 2)) }}
            </div>
            <div class="recipient-info">
                <div class="recipient-name">{{ $survey->employee_name }}</div>
                <div class="recipient-sub">{{ $survey->designation }} &bull; {{ $survey->department }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('survey.auth', $survey->token) }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="passcode">Enter Access Passcode</label>
                <input type="text" 
                       id="passcode" 
                       name="passcode" 
                       class="passcode-input" 
                       placeholder="9-CHARACTER PASSCODE" 
                       maxlength="20"
                       required 
                       autofocus
                       autocomplete="off"
                       value="{{ old('passcode') }}">

                @error('passcode')
                    <div class="error-message">
                        <span>⚠️</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <span>Unlock Exit Survey</span>
                <span>➔</span>
            </button>
        </form>

        <div class="gate-footer">
            <div>
                Please check the separate passcode email sent to <strong>{{ $survey->employee_email }}</strong> for your confidential passcode.
            </div>
            <div class="expiry-notice">
                <span>⏳</span>
                <span>Valid for {{ $survey->daysRemaining() }} more days (until {{ $survey->expires_at->format('M d, Y') }})</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
