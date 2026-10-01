<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Exit Interview Portal — George Steuart Group</title>
    <link rel="icon" type="image/png" href="{{ asset('George_Steuart_Group_Logo.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #8C0026;
            --primary-dark: #520016;
            --primary-light: #B30031;
            --accent-gold: #c9993a;
            --accent-gold-light: #f0c060;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff;
        }

        .split-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ── Left Side: Simplified, Elegant Branding Panel ──────────── */
        .left-panel {
            flex: 1;
            background: 
                radial-gradient(circle at 20% 25%, rgba(201, 153, 58, 0.16) 0%, transparent 45%),
                radial-gradient(circle at 80% 80%, rgba(240, 192, 96, 0.12) 0%, transparent 45%),
                linear-gradient(145deg, #8C0026 0%, #66001C 50%, #420012 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 48px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 1px;
            background: linear-gradient(180deg, rgba(240, 192, 96, 0.4) 0%, rgba(140, 0, 38, 0.1) 100%);
        }

        .left-content {
            margin: auto 0;
            max-width: 440px;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 10px 24px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.8);
            margin-bottom: 24px;
        }

        .logo-img {
            height: 52px;
            max-width: 200px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(240, 192, 96, 0.15);
            color: #f0c060;
            border: 1px solid rgba(240, 192, 96, 0.35);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .hero-title {
            font-size: 34px;
            font-weight: 900;
            line-height: 1.15;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin: 0 0 10px;
        }

        .hero-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.55;
            margin: 0;
        }

        .left-footer {
            padding-top: 20px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.65);
        }

        .left-footer strong {
            color: #f0c060;
        }

        /* ── Right Side: Clean Login Form Panel ───────────────────────── */
        .right-panel {
            flex: 1;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 36px 32px;
            min-height: 100vh;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
        }

        .mobile-branding-header {
            display: none;
            text-align: center;
            margin-bottom: 24px;
        }

        /* ── Responsive Rules ────────────────────────────────────────── */
        @media (max-width: 900px) {
            .split-layout {
                flex-direction: column;
                min-height: 100vh;
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                min-height: 100vh;
                padding: 32px 18px 40px;
                background: #f8fafc;
                justify-content: center;
            }

            .form-container {
                background: #ffffff;
                border-radius: 20px;
                padding: 28px 22px;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
                border: 1px solid #e2e8f0;
            }

            .mobile-branding-header {
                display: block;
            }
        }

        @media (min-width: 901px) and (max-height: 780px) {
            .left-panel {
                padding: 32px 36px;
            }
            .hero-title {
                font-size: 28px;
            }
            .right-panel {
                padding: 24px 28px;
            }
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800">

    <div class="split-layout">
        <!-- ── LEFT SIDE: Brand & Hero Showcase (Minimal & Clean) ────── -->
        <div class="left-panel">
            <div class="left-content">
                <!-- Company Logo -->
                <div class="logo-wrap">
                    <img src="{{ asset('George_Steuart_Group_Logo.png') }}" alt="George Steuart Group" class="logo-img">
                </div>

                <!-- Tag & Title -->
                <div>
                    <div class="portal-badge">
                        <span>OFFICIAL HR PORTAL</span>
                    </div>
                    <h1 class="hero-title">
                        Exit Interview<br>Portal
                    </h1>
                    <p class="hero-subtitle">
                        George Steuart Group &bull; HR Intelligence Platform
                    </p>
                </div>
            </div>

            <!-- Left Footer -->
            <div class="left-footer">
                <strong>George Steuart &amp; Co.</strong> &bull; Established 1835
            </div>
        </div>

        <!-- ── RIGHT SIDE: Login Form Area ────────────────────────────── -->
        <div class="right-panel">
            <div class="form-container">
                <!-- Mobile Branding Header (Shown on phone / tablet) -->
                <div class="mobile-branding-header">
                    <div class="inline-flex items-center justify-center bg-white p-2.5 rounded-2xl shadow-sm border border-slate-100 mb-2.5">
                        <img src="{{ asset('George_Steuart_Group_Logo.png') }}" alt="George Steuart Group" class="h-10 w-auto object-contain">
                    </div>
                    <div>
                        <span class="inline-block bg-[#fdf2f4] text-[#8C0026] border border-[#fecdd3] text-[10px] font-bold tracking-widest uppercase px-2.5 py-0.5 rounded-full mb-1">
                            Official HR Portal
                        </span>
                    </div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Exit Interview Portal</h2>
                    <p class="text-xs text-slate-500 font-medium">George Steuart Group &bull; HR Intelligence Platform</p>
                </div>

                <!-- Page Content Slot (Login Form) -->
                {{ $slot }}

                <!-- Bottom Copyright -->
                <div class="mt-6 text-center text-xs text-slate-400 space-y-0.5">
                    <p>&copy; {{ date('Y') }} George Steuart Group. All rights reserved.</p>
                    <p class="text-[11px] text-slate-400/80">Confidential Human Resources System</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
