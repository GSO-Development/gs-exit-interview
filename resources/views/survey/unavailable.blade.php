<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Unavailable — George Steuart Group</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #8C0026, #66001C); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #fff; border-radius: 20px; padding: 56px 48px; max-width: 480px; width: 100%; text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,0.3); }
        .unavailable-logo-wrap {
            margin-bottom: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            background: #ffffff;
            border-radius: 12px;
            animation: bounce 0.6s ease;
        }
        .unavailable-logo {
            max-height: 72px;
            max-width: 180px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        @keyframes bounce { 0% { transform: scale(0.6); opacity: 0; } 70% { transform: scale(1.05); } 100% { transform: scale(1); opacity: 1; } }
        h1 { font-size: 24px; font-weight: 800; color: #8C0026; margin-bottom: 8px; }
        p { font-size: 14px; color: #64748b; line-height: 1.7; }
        .status { display: inline-block; margin-top: 20px; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700;
            background: {{ $survey->status === 'submitted' ? '#d1fae5' : '#fee2e2' }};
            color: {{ $survey->status === 'submitted' ? '#065f46' : '#991b1b' }};
        }

        /* Dustbin & Letter Falling Animation */
        .dustbin-anim-container {
            position: relative;
            width: 145px;
            height: 135px;
            margin: 18px auto 16px auto;
        }

        .dustbin-svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }

        /* Falling Letter Animation */
        .anim-letter {
            animation: dropLetter 3.2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            transform-origin: 80px 28px;
        }

        @keyframes dropLetter {
            0% {
                transform: translateY(-10px) rotate(-6deg);
                opacity: 0;
            }
            14% {
                transform: translateY(0px) rotate(-4deg);
                opacity: 1;
            }
            28% {
                transform: translateY(4px) rotate(3deg);
                opacity: 1;
            }
            44% {
                transform: translateY(24px) rotate(-8deg) scale(0.95);
                opacity: 1;
            }
            60% {
                transform: translateY(68px) rotate(4deg) scale(0.85);
                opacity: 0.9;
            }
            68% {
                transform: translateY(78px) rotate(0deg) scale(0.75);
                opacity: 0;
            }
            100% {
                transform: translateY(-10px) rotate(-6deg);
                opacity: 0;
            }
        }

        /* Bin Lid Animation: Hinged on Left */
        .anim-bin-lid {
            animation: openCloseLid 3.2s cubic-bezier(0.34, 1.56, 0.64, 1) infinite;
            transform-origin: 50px 72px;
        }

        @keyframes openCloseLid {
            0%, 14% {
                transform: rotate(0deg);
            }
            26%, 56% {
                transform: rotate(-48deg);
            }
            65% {
                transform: rotate(4deg);
            }
            71% {
                transform: rotate(-2deg);
            }
            76%, 100% {
                transform: rotate(0deg);
            }
        }

        /* Bin Body subtle bounce on impact */
        .anim-bin-body {
            animation: binSquash 3.2s ease infinite;
            transform-origin: 80px 130px;
        }

        @keyframes binSquash {
            0%, 64% {
                transform: scale(1, 1);
            }
            67% {
                transform: scale(1.04, 0.96);
            }
            73% {
                transform: scale(0.98, 1.02);
            }
            78%, 100% {
                transform: scale(1, 1);
            }
        }

        /* Impact puff when lid snaps shut */
        .anim-impact-puff {
            animation: puffParticles 3.2s ease infinite;
            transform-origin: 80px 72px;
        }

        @keyframes puffParticles {
            0%, 64% {
                opacity: 0;
                transform: scale(0.6);
            }
            67% {
                opacity: 0.8;
                transform: scale(1.2);
            }
            75%, 100% {
                opacity: 0;
                transform: scale(1.5);
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="unavailable-logo-wrap">
            <img src="{{ asset('George_Steuart_Group_Logo.png') }}" alt="George Steuart Group" class="unavailable-logo">
        </div>
        <h1>
            @if($survey->status === 'submitted') Survey Already Submitted
            @elseif($survey->status === 'expired') Survey Link Expired
            @else Survey Link Unavailable
            @endif
        </h1>

        @if($survey->status !== 'submitted')
        <!-- Animated Dustbin & Falling Letter -->
        <div class="dustbin-anim-container" aria-hidden="true">
            <svg class="dustbin-svg" viewBox="0 0 160 145" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <filter id="softShadow" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="#8C0026" flood-opacity="0.12" />
                    </filter>
                    <linearGradient id="binBodyGrad" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="45%" stop-color="#ffffff" />
                        <stop offset="100%" stop-color="#e2e8f0" />
                    </linearGradient>
                    <linearGradient id="lidMetalGrad" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#e2e8f0" />
                        <stop offset="50%" stop-color="#ffffff" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>

                <!-- Ground Shadow -->
                <ellipse cx="80" cy="136" rx="36" ry="6" fill="#0f172a" opacity="0.08" />

                <!-- Layer 1: Dark Interior Cavity of Bin -->
                <g class="anim-bin-body">
                    <ellipse cx="80" cy="74" rx="28" ry="6.5" fill="#1e293b" />
                </g>

                <!-- Layer 2: Animated Envelope (Falling from above into cavity) -->
                <g class="anim-letter">
                    <g filter="url(#softShadow)">
                        <!-- Envelope Body -->
                        <rect x="62" y="16" width="36" height="24" rx="3.5" fill="#ffffff" stroke="#8C0026" stroke-width="1.6" />
                        <!-- Envelope Flap -->
                        <path d="M 62 17 L 80 29 L 98 17" fill="#fff5f5" stroke="#8C0026" stroke-width="1.4" stroke-linejoin="round" />
                        <!-- GS Red Wax Seal -->
                        <circle cx="80" cy="28.5" r="4.2" fill="#8C0026" />
                        <circle cx="80" cy="28.5" r="2" fill="#fda4af" />
                        <!-- Subtle letter address lines -->
                        <line x1="68" y1="33.5" x2="74" y2="33.5" stroke="#cbd5e1" stroke-width="1.4" stroke-linecap="round" />
                        <line x1="86" y1="33.5" x2="92" y2="33.5" stroke="#cbd5e1" stroke-width="1.4" stroke-linecap="round" />
                    </g>
                </g>

                <!-- Layer 3: Front Wall of Dustbin (Covers envelope as it enters) -->
                <g class="anim-bin-body" filter="url(#softShadow)">
                    <!-- Bin Body Tapered Shell -->
                    <path d="M 50 73 L 56 127 C 56 131 60 134 65 134 L 95 134 C 100 134 104 131 104 127 L 110 73 Z" 
                          fill="url(#binBodyGrad)" stroke="#94a3b8" stroke-width="2" stroke-linejoin="round" />
                    <!-- Bin Rim Collar -->
                    <ellipse cx="80" cy="73" rx="30" ry="5.8" fill="url(#lidMetalGrad)" stroke="#94a3b8" stroke-width="1.8" />
                    <!-- Vertical Ribs for Modern Can Texture -->
                    <line x1="69" y1="83" x2="71" y2="124" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" />
                    <line x1="80" y1="83" x2="80" y2="125" stroke="#cbd5e1" stroke-width="2.2" stroke-linecap="round" />
                    <line x1="91" y1="83" x2="89" y2="124" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" />
                    <!-- Deactivated Badge on Bin -->
                    <circle cx="80" cy="103" r="8" fill="#fee2e2" stroke="#fca5a5" stroke-width="1" />
                    <path d="M 77 100 L 83 106 M 83 100 L 77 106" stroke="#dc2626" stroke-width="1.6" stroke-linecap="round" />
                </g>

                <!-- Impact Particles on Lid Slam -->
                <g class="anim-impact-puff">
                    <circle cx="51" cy="71" r="2.2" fill="#94a3b8" />
                    <circle cx="109" cy="71" r="2.2" fill="#94a3b8" />
                </g>

                <!-- Layer 4: Bin Lid (Hinged at Left Corner cx=50, cy=72) -->
                <g class="anim-bin-lid" filter="url(#softShadow)">
                    <!-- Lid Bottom Rim -->
                    <ellipse cx="80" cy="71" rx="32" ry="5.2" fill="url(#lidMetalGrad)" stroke="#64748b" stroke-width="2" />
                    <!-- Lid Dome Base -->
                    <path d="M 56 70 C 58 63 67 61 80 61 C 93 61 102 63 104 70 Z" fill="url(#lidMetalGrad)" stroke="#64748b" stroke-width="1.8" />
                    <!-- Lid Handle -->
                    <path d="M 73 61 L 73 55 C 73 53 75 52 77 52 L 83 52 C 85 52 87 53 87 55 L 87 61" 
                          fill="none" stroke="#475569" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                </g>
            </svg>
        </div>
        @else
        <div style="font-size: 54px; margin: 16px 0 10px 0; animation: bounce 0.6s ease;">✅</div>
        @endif
        <p>
            @if($survey->status === 'submitted')
                This exit interview has already been completed and submitted. Each link can only be used once.
            @elseif($survey->status === 'expired')
                This survey link has expired. Please contact your HR department if you still need to complete the exit interview.
            @else
                This link has been deactivated by your HR department. Please contact HR if you believe this is an error.
            @endif
        </p>
        <div class="status">Status: {{ strtoupper($survey->status) }}</div>
    </div>
</body>
</html>
