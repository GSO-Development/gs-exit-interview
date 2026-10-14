<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Team Member Exit Interview Declaration — {{ $survey->company->name ?? 'George Steuart Group' }}</title>
    <meta name="description" content="George Steuart Group Team Member Exit Interview Declaration">
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
            max-width: 680px;
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
            padding: 32px 32px 32px;
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

        .declaration-heading {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }

        .declaration-text {
            background: #fffdf7;
            border: 1px solid #f1e3c2;
            border-left: 4px solid var(--accent);
            border-radius: 12px;
            padding: 20px 22px;
            margin-bottom: 24px;
        }

        .declaration-text p {
            font-size: 14.5px;
            line-height: 1.7;
            color: #1e293b;
        }

        .declaration-text p + p {
            margin-top: 14px;
        }

        .confirm-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 18px;
            border: 2px solid #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            cursor: pointer;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .confirm-row:hover {
            border-color: #94a3b8;
        }

        .confirm-row.is-checked {
            border-color: var(--primary);
            background: #fff7f9;
        }

        .confirm-row:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(140, 0, 38, 0.12);
        }

        .confirm-checkbox {
            width: 20px;
            height: 20px;
            margin-top: 1px;
            flex-shrink: 0;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .confirm-checkbox:focus-visible {
            outline: 3px solid var(--primary);
            outline-offset: 2px;
        }

        .confirm-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            line-height: 1.5;
            cursor: pointer;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-message[hidden] {
            display: none;
        }

        .btn-submit {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #8C0026 0%, #B30031 100%);
            color: #ffffff;
            font-family: inherit;
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

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(140, 0, 38, 0.35);
            background: linear-gradient(135deg, #66001C 0%, #8C0026 100%);
        }

        .btn-submit:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 3px;
        }

        .btn-submit:disabled {
            background: #cbd5e1;
            color: #64748b;
            box-shadow: none;
            cursor: not-allowed;
        }

        .btn-submit.is-loading {
            background: linear-gradient(135deg, #8C0026 0%, #B30031 100%);
            color: #ffffff;
            cursor: progress;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .spinner[hidden] {
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
        }

        @media (prefers-reduced-motion: reduce) {
            .gate-card, .spinner { animation: none; }
            .btn-submit, .confirm-row { transition: none; }
        }

        /* ── Mobile Responsive Styles ───────────────────────────────── */
        @media (max-width: 520px) {
            body {
                padding: 16px 12px;
                align-items: flex-start;
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

            .declaration-text {
                padding: 16px 16px;
            }

            .declaration-text p {
                font-size: 13.5px;
            }

            .confirm-row {
                padding: 14px 14px;
            }

            .confirm-label {
                font-size: 13.5px;
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

            .btn-submit {
                font-size: 13.5px;
            }
        }
    </style>
</head>
<body>

<main class="gate-card">
    <header class="gate-header">
        <div class="gate-logo-wrap">
            <img src="{{ asset('George_Steuart_Group_Logo.png') }}" alt="{{ $survey->company->name ?? 'George Steuart Group' }}" class="gate-logo">
        </div>
        <div class="gate-company">{{ $survey->company->name ?? 'George Steuart Group' }}</div>
        <h1 class="gate-title">Team Member Exit Interview Declaration</h1>
        <p class="gate-subtitle">Please read and confirm before continuing</p>
    </header>

    <div class="gate-body">
        <div class="recipient-badge">
            <div class="avatar-circle" aria-hidden="true">
                {{ strtoupper(substr($survey->employee_name, 0, 2)) }}
            </div>
            <div class="recipient-info">
                <div class="recipient-name">{{ $survey->employee_name }}</div>
                <div class="recipient-sub">{{ $survey->designation }} &bull; {{ $survey->department }}</div>
            </div>
        </div>

        <form id="declarationForm" method="POST" action="{{ route('survey.declaration', $survey->token) }}" novalidate>
            @csrf

            <h2 class="declaration-heading" id="declarationHeading">Declaration</h2>
            <div class="declaration-text" aria-labelledby="declarationHeading">
                <p>I confirm that I am completing this online Exit Interview voluntarily and that the responses provided are based on my own experiences, observations, thoughts and views during my employment with the Company.</p>
                <p>I undertake to provide my responses honestly, accurately and in good faith, without undue influence, direction or intervention from any other party. I understand that the purpose of the Exit Interview is to obtain genuine feedback and that my responses should reflect my own views and experiences.</p>
                <p>I further confirm that I have not knowingly provided any false or misleading information and understand that the information provided may be used by the Company for employment-related, organisational improvement and people-management purposes.</p>
            </div>

            <label class="confirm-row" id="confirmRow" for="declarationCheckbox">
                <input type="checkbox"
                       id="declarationCheckbox"
                       name="declaration"
                       value="1"
                       class="confirm-checkbox"
                       required
                       aria-describedby="declarationError">
                <span class="confirm-label">I confirm that I have read and agree with the above declaration</span>
            </label>

            <button type="submit" id="declarationContinueBtn" class="btn-submit" disabled aria-disabled="true">
                <span class="spinner" id="declarationSpinner" hidden aria-hidden="true"></span>
                <span id="declarationBtnText">Continue</span>
                <span id="declarationBtnArrow" aria-hidden="true">➔</span>
            </button>

            <div class="error-message" id="declarationError" role="alert" aria-live="assertive" @error('declaration') @else hidden @enderror>
                <span aria-hidden="true">⚠️</span>
                <span id="declarationErrorText">@error('declaration'){{ $message }}@enderror</span>
            </div>
        </form>

        <div class="gate-footer">
            <div class="expiry-notice">
                <span aria-hidden="true">⏳</span>
                <span>Valid for {{ $survey->daysRemaining() }} more days (until {{ $survey->expires_at->format('M d, Y') }})</span>
            </div>
        </div>
    </div>
</main>

<script>
    (function () {
        const form = document.getElementById('declarationForm');
        const checkbox = document.getElementById('declarationCheckbox');
        const row = document.getElementById('confirmRow');
        const button = document.getElementById('declarationContinueBtn');
        const spinner = document.getElementById('declarationSpinner');
        const buttonText = document.getElementById('declarationBtnText');
        const arrow = document.getElementById('declarationBtnArrow');
        const errorBox = document.getElementById('declarationError');
        const errorText = document.getElementById('declarationErrorText');
        let isSaving = false;

        function syncButton() {
            const enabled = checkbox.checked && ! isSaving;
            button.disabled = ! enabled;
            button.setAttribute('aria-disabled', enabled ? 'false' : 'true');
            row.classList.toggle('is-checked', checkbox.checked);
        }

        function setLoading(loading) {
            isSaving = loading;
            spinner.hidden = ! loading;
            arrow.hidden = loading;
            buttonText.textContent = loading ? 'Saving…' : 'Continue';
            button.classList.toggle('is-loading', loading);
            button.setAttribute('aria-busy', loading ? 'true' : 'false');
            checkbox.disabled = loading;
            syncButton();
        }

        function showError(message) {
            errorText.textContent = message;
            errorBox.hidden = false;
        }

        checkbox.addEventListener('change', function () {
            errorBox.hidden = true;
            syncButton();
        });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (! checkbox.checked || isSaving) {
                return;
            }

            errorBox.hidden = true;
            setLoading(true);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ declaration: true }),
                });

                const data = await response.json().catch(function () { return {}; });

                if (response.ok && data.redirect) {
                    window.location.assign(data.redirect);
                    return;
                }

                if (response.status === 419) {
                    showError('Your page has expired. Please refresh the page and try again.');
                } else {
                    showError(data.message || 'We could not save your declaration. Please try again.');
                }
            } catch (error) {
                showError('A network error occurred. Please check your connection and try again.');
            }

            setLoading(false);
        });

        syncButton();
    })();
</script>

</body>
</html>
