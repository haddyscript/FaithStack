<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome to FaithStack</title>
<style>
    body { margin:0; padding:0; background:#f1f5f9; font-family:'Inter','Helvetica Neue',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
    .wrap { max-width:580px; margin:40px auto; }
    .card { background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
    .header { background:linear-gradient(135deg,#4f46e5,#7c3aed); padding:36px 40px 32px; text-align:center; }
    .header-icon { font-size:40px; margin:0 auto 16px; display:block; }
    .header h1 { margin:0; color:#ffffff; font-size:24px; font-weight:700; letter-spacing:-0.5px; }
    .header p { margin:6px 0 0; color:rgba(255,255,255,0.75); font-size:14px; }
    .body { padding:36px 40px; }
    .greeting { font-size:16px; color:#1e293b; font-weight:600; margin:0 0 12px; }
    .text { font-size:14px; color:#475569; line-height:1.7; margin:0 0 24px; }
    .info-box { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:20px 24px; margin:0 0 28px; }
    .info-row { display:flex; justify-content:space-between; align-items:center; margin:0 0 12px; }
    .info-row:last-child { margin:0; padding-top:12px; border-top:1px solid #e2e8f0; }
    .info-label { font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; }
    .info-value { font-size:14px; font-weight:600; color:#1e293b; }
    .info-badge { display:inline-block; padding:3px 10px; background:#eef2ff; color:#4f46e5; border-radius:20px; font-size:12px; font-weight:700; }
    .steps { margin:0 0 28px; padding:0; list-style:none; counter-reset:steps; }
    .steps li { font-size:14px; color:#475569; padding:8px 0 8px 36px; position:relative; border-bottom:1px solid #f1f5f9; counter-increment:steps; }
    .steps li:last-child { border-bottom:none; }
    .steps li::before { content:counter(steps); position:absolute; left:0; top:8px; width:22px; height:22px; background:#4f46e5; color:#fff; border-radius:50%; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; text-align:center; line-height:22px; }
    .cta-wrap { text-align:center; margin:0 0 32px; }
    .cta-btn { display:inline-block; padding:13px 32px; background:#4f46e5; color:#ffffff; text-decoration:none; border-radius:10px; font-size:14px; font-weight:700; letter-spacing:0.01em; }
    .cta-secondary { display:inline-block; margin-top:10px; font-size:13px; color:#6366f1; text-decoration:none; }
    .divider { border:none; border-top:1px solid #f1f5f9; margin:0 0 24px; }
    .footer { padding:0 40px 36px; text-align:center; }
    .footer p { font-size:12px; color:#94a3b8; margin:0 0 6px; line-height:1.6; }
    .footer a { color:#6366f1; text-decoration:none; }
</style>
</head>
<body>
<div class="wrap">
    <div class="card">

        <div class="header">
            <span class="header-icon">🙏</span>
            <h1>Welcome to FaithStack!</h1>
            <p>Your organization's site is live and ready</p>
        </div>

        <div class="body">
            <p class="greeting">Hi {{ $tenant->name }},</p>
            <p class="text">
                You're all set! Your FaithStack site has been created on the
                <strong>{{ $plan->name }}</strong> plan.
                @if($plan->effectiveTrialDays() > 0)
                    You have a <strong>{{ $plan->effectiveTrialDays() }}-day free trial</strong> — no credit card needed to get started.
                @endif
                Here's everything you need to know to get up and running.
            </p>

            {{-- Site details --}}
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Organization</span>
                    <span class="info-value">{{ $tenant->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Plan</span>
                    <span class="info-badge">{{ $plan->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Your Site URL</span>
                    <span class="info-value">{{ $tenant->subdomain }}.{{ config('app.base_domain', 'faithstack.com') }}</span>
                </div>
                @if($plan->effectiveTrialDays() > 0)
                <div class="info-row">
                    <span class="info-label">Trial Ends</span>
                    <span class="info-value">{{ now()->addDays($plan->effectiveTrialDays())->format('F j, Y') }}</span>
                </div>
                @endif
            </div>

            {{-- Getting started steps --}}
            <p class="text" style="margin-bottom:12px;font-weight:600;color:#1e293b;">Get started in 3 steps:</p>
            <ol class="steps">
                <li>Log in to your admin dashboard and customize your branding (logo, colors)</li>
                <li>Choose a theme and create your first page</li>
                <li>Set up your donation form so supporters can give online</li>
            </ol>

            {{-- CTA --}}
            <div class="cta-wrap">
                <a href="{{ 'https://' . $tenant->subdomain . '.' . config('app.base_domain', 'faithstack.com') . '/admin' }}"
                   class="cta-btn">
                    Go to Your Dashboard →
                </a>
                <br>
                <a href="{{ 'https://' . $tenant->subdomain . '.' . config('app.base_domain', 'faithstack.com') }}"
                   class="cta-secondary">
                    View your live site
                </a>
            </div>

            <hr class="divider">

            <p class="text" style="margin:0;">
                Need help getting started? Reply to this email or reach us at
                <a href="mailto:hello@faithstack.com" style="color:#6366f1;">hello@faithstack.com</a>.
                We're here every step of the way.
            </p>
        </div>

    </div>

    <div class="footer">
        <p>FaithStack · <a href="#">Unsubscribe</a> · <a href="#">Privacy Policy</a></p>
        <p>You're receiving this because you created a FaithStack account.</p>
    </div>
</div>
</body>
</html>
