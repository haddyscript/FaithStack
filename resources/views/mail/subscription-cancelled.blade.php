<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subscription Cancelled</title>
<style>
    body { margin:0; padding:0; background:#f1f5f9; font-family:'Inter','Helvetica Neue',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
    .wrap { max-width:580px; margin:40px auto; }
    .card { background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
    .header { background:linear-gradient(135deg,#475569,#64748b); padding:36px 40px 32px; text-align:center; }
    .header-icon { font-size:40px; margin:0 auto 16px; display:block; }
    .header h1 { margin:0; color:#ffffff; font-size:24px; font-weight:700; letter-spacing:-0.5px; }
    .header p { margin:6px 0 0; color:rgba(255,255,255,0.80); font-size:14px; }
    .body { padding:36px 40px; }
    .greeting { font-size:16px; color:#1e293b; font-weight:600; margin:0 0 12px; }
    .text { font-size:14px; color:#475569; line-height:1.7; margin:0 0 24px; }
    .info-box { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:20px 24px; margin:0 0 28px; }
    .info-row { display:flex; justify-content:space-between; align-items:center; margin:0 0 12px; }
    .info-row:last-child { margin:0; padding-top:12px; border-top:1px solid #e2e8f0; }
    .info-label { font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; }
    .info-value { font-size:14px; font-weight:600; color:#1e293b; }
    .cancelled-badge { display:inline-block; padding:3px 10px; background:#f1f5f9; color:#64748b; border-radius:20px; font-size:12px; font-weight:700; }
    .feature-list { margin:0 0 28px; padding:0; list-style:none; }
    .feature-list li { font-size:14px; color:#475569; padding:6px 0 6px 28px; position:relative; }
    .feature-list li::before { content:"✗"; position:absolute; left:0; top:6px; color:#94a3b8; font-weight:700; }
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
            <span class="header-icon">😔</span>
            <h1>Subscription cancelled</h1>
            <p>We're sorry to see you go</p>
        </div>

        <div class="body">
            <p class="greeting">Hi {{ $tenant->name }},</p>
            <p class="text">
                Your FaithStack subscription has been cancelled. We've processed your request and your
                account will remain accessible until the end of your current billing period.
            </p>

            {{-- Account details --}}
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Organization</span>
                    <span class="info-value">{{ $tenant->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="cancelled-badge">Cancelled</span>
                </div>
                @if($tenant->subscription_ends_at)
                <div class="info-row">
                    <span class="info-label">Access Until</span>
                    <span class="info-value">{{ $tenant->subscription_ends_at->format('F j, Y') }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Cancelled On</span>
                    <span class="info-value">{{ now()->format('F j, Y') }}</span>
                </div>
            </div>

            <p class="text" style="font-weight:600;color:#1e293b;margin-bottom:12px;">After your access expires, the following will be disabled:</p>
            <ul class="feature-list">
                <li>Your public-facing website and pages</li>
                <li>Online donation forms</li>
                <li>Admin dashboard access</li>
                <li>Your subdomain ({{ $tenant->subdomain }}.{{ config('app.base_domain', 'faithstack.com') }})</li>
            </ul>

            <p class="text">
                Changed your mind? You can reactivate your subscription at any time and your site will be restored immediately.
            </p>

            {{-- Reactivate CTA --}}
            <div class="cta-wrap">
                <a href="{{ 'https://' . $tenant->subdomain . '.' . config('app.base_domain', 'faithstack.com') . '/admin/billing' }}"
                   class="cta-btn">
                    Reactivate My Subscription
                </a>
                <br>
                <a href="mailto:hello@faithstack.com" class="cta-secondary">
                    Contact us with feedback
                </a>
            </div>

            <hr class="divider">

            <p class="text" style="margin:0;">
                We'd love to know how we can improve. Reply to this email with any feedback —
                your input directly shapes the future of FaithStack.
            </p>
        </div>

    </div>

    <div class="footer">
        <p>FaithStack · <a href="#">Privacy Policy</a></p>
        <p>You're receiving this as the admin of {{ $tenant->name }} on FaithStack.</p>
    </div>
</div>
</body>
</html>
