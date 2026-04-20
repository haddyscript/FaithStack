<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subscription Expiring Soon</title>
<style>
    body { margin:0; padding:0; background:#f1f5f9; font-family:'Inter','Helvetica Neue',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
    .wrap { max-width:580px; margin:40px auto; }
    .card { background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
    .header { background:linear-gradient(135deg,#d97706,#f59e0b); padding:36px 40px 32px; text-align:center; }
    .header-icon { font-size:40px; margin:0 auto 16px; display:block; }
    .header h1 { margin:0; color:#ffffff; font-size:24px; font-weight:700; letter-spacing:-0.5px; }
    .header p { margin:6px 0 0; color:rgba(255,255,255,0.85); font-size:14px; }
    .body { padding:36px 40px; }
    .greeting { font-size:16px; color:#1e293b; font-weight:600; margin:0 0 12px; }
    .text { font-size:14px; color:#475569; line-height:1.7; margin:0 0 24px; }
    .countdown-box { text-align:center; background:#fffbeb; border:1.5px solid #fde68a; border-radius:12px; padding:24px; margin:0 0 24px; }
    .countdown-label { font-size:12px; font-weight:700; color:#d97706; text-transform:uppercase; letter-spacing:0.08em; margin:0 0 6px; }
    .countdown-value { font-size:52px; font-weight:800; color:#92400e; letter-spacing:-2px; margin:0; line-height:1; }
    .countdown-unit { font-size:16px; font-weight:600; color:#78350f; margin:4px 0 0; }
    .info-box { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:20px 24px; margin:0 0 28px; }
    .info-row { display:flex; justify-content:space-between; align-items:center; margin:0 0 12px; }
    .info-row:last-child { margin:0; padding-top:12px; border-top:1px solid #e2e8f0; }
    .info-label { font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; }
    .info-value { font-size:14px; font-weight:600; color:#1e293b; }
    .warn-badge { display:inline-block; padding:3px 10px; background:#fef3c7; color:#d97706; border-radius:20px; font-size:12px; font-weight:700; }
    .cta-wrap { text-align:center; margin:0 0 32px; }
    .cta-btn { display:inline-block; padding:13px 32px; background:#d97706; color:#ffffff; text-decoration:none; border-radius:10px; font-size:14px; font-weight:700; letter-spacing:0.01em; }
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
            <span class="header-icon">⏳</span>
            <h1>Your subscription is expiring soon</h1>
            <p>Action required to keep your site online</p>
        </div>

        <div class="body">
            <p class="greeting">Hi {{ $tenant->name }},</p>
            <p class="text">
                Your FaithStack subscription is expiring in <strong>{{ $daysRemaining }} day{{ $daysRemaining !== 1 ? 's' : '' }}</strong>.
                To avoid any interruption to your site and donation forms, please renew before the expiry date.
            </p>

            {{-- Countdown --}}
            <div class="countdown-box">
                <p class="countdown-label">Days Remaining</p>
                <p class="countdown-value">{{ $daysRemaining }}</p>
                <p class="countdown-unit">{{ $daysRemaining === 1 ? 'day' : 'days' }} until expiry</p>
            </div>

            {{-- Details --}}
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Organization</span>
                    <span class="info-value">{{ $tenant->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="warn-badge">Expiring Soon</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Expiry Date</span>
                    <span class="info-value">{{ $tenant->subscription_ends_at?->format('F j, Y') ?? 'N/A' }}</span>
                </div>
            </div>

            <p class="text">
                After expiry, your site will show a maintenance page and your donation forms will be disabled.
                <strong>Renew now</strong> to keep everything running without interruption.
            </p>

            {{-- CTA --}}
            <div class="cta-wrap">
                <a href="{{ 'https://' . $tenant->subdomain . '.' . config('app.base_domain', 'faithstack.com') . '/admin/billing' }}"
                   class="cta-btn">
                    Renew My Subscription →
                </a>
            </div>

            <hr class="divider">

            <p class="text" style="margin:0;">
                Questions about your plan? Contact us at
                <a href="mailto:hello@faithstack.com" style="color:#6366f1;">hello@faithstack.com</a> — we're happy to help.
            </p>
        </div>

    </div>

    <div class="footer">
        <p>FaithStack · <a href="#">Unsubscribe</a> · <a href="#">Privacy Policy</a></p>
        <p>You're receiving this as the admin of {{ $tenant->name }} on FaithStack.</p>
    </div>
</div>
</body>
</html>
