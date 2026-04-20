<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subscription Confirmed</title>
<style>
    body { margin:0; padding:0; background:#f1f5f9; font-family:'Inter','Helvetica Neue',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
    .wrap { max-width:580px; margin:40px auto; }
    .card { background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
    .header { background:linear-gradient(135deg,#4f46e5,#7c3aed); padding:36px 40px 32px; text-align:center; }
    .header-icon { width:56px; height:56px; background:rgba(255,255,255,0.2); border-radius:16px; margin:0 auto 16px; display:flex; align-items:center; justify-content:center; font-size:28px; }
    .header h1 { margin:0; color:#ffffff; font-size:24px; font-weight:700; letter-spacing:-0.5px; }
    .header p { margin:6px 0 0; color:rgba(255,255,255,0.75); font-size:14px; }
    .body { padding:36px 40px; }
    .greeting { font-size:16px; color:#1e293b; font-weight:600; margin:0 0 12px; }
    .text { font-size:14px; color:#475569; line-height:1.7; margin:0 0 24px; }
    .plan-box { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:20px 24px; margin:0 0 28px; }
    .plan-row { display:flex; justify-content:space-between; align-items:center; margin:0 0 12px; }
    .plan-row:last-child { margin:0; padding-top:12px; border-top:1px solid #e2e8f0; }
    .plan-label { font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; }
    .plan-value { font-size:14px; font-weight:600; color:#1e293b; }
    .plan-badge { display:inline-block; padding:3px 10px; background:#eef2ff; color:#4f46e5; border-radius:20px; font-size:12px; font-weight:700; }
    .feature-list { margin:0 0 28px; padding:0; list-style:none; }
    .feature-list li { font-size:14px; color:#475569; padding:5px 0; display:flex; align-items:center; gap:10px; }
    .feature-list li::before { content:"✓"; color:#10b981; font-weight:700; font-size:13px; flex-shrink:0; }
    .cta-wrap { text-align:center; margin:0 0 32px; }
    .cta-btn { display:inline-block; padding:13px 32px; background:#4f46e5; color:#ffffff; text-decoration:none; border-radius:10px; font-size:14px; font-weight:700; letter-spacing:0.01em; }
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
            <div class="header-icon">🎉</div>
            <h1>You're all set!</h1>
            <p>Your FaithStack subscription is now active</p>
        </div>

        <div class="body">
            <p class="greeting">Hi {{ $tenant->name }},</p>
            <p class="text">
                Thank you for upgrading to <strong>{{ $plan->name }}</strong>! Your subscription is confirmed and all features are now unlocked. We're excited to help your community grow online.
            </p>

            {{-- Plan summary --}}
            <div class="plan-box">
                <div class="plan-row">
                    <span class="plan-label">Plan</span>
                    <span class="plan-badge">{{ $plan->name }}</span>
                </div>
                <div class="plan-row">
                    <span class="plan-label">Status</span>
                    <span class="plan-value" style="color:#10b981;">✓ Active</span>
                </div>
                <div class="plan-row">
                    <span class="plan-label">Price</span>
                    <span class="plan-value">{{ $plan->formattedPrice() }}</span>
                </div>
                <div class="plan-row">
                    <span class="plan-label">Next Renewal</span>
                    <span class="plan-value">{{ now()->addMonth()->format('F j, Y') }}</span>
                </div>
            </div>

            {{-- Features --}}
            @if(count($plan->features ?? []) > 0)
            <p class="text" style="margin-bottom:12px;font-weight:600;color:#1e293b;">What's included in your plan:</p>
            <ul class="feature-list">
                @foreach($plan->features as $feature)
                <li>{{ $feature }}</li>
                @endforeach
            </ul>
            @endif

            {{-- CTA --}}
            <div class="cta-wrap">
                <a href="{{ 'https://' . $tenant->subdomain . '.' . config('app.base_domain', 'faithstack.com') . '/admin' }}"
                   class="cta-btn">
                    Go to Your Dashboard →
                </a>
            </div>

            <hr class="divider">

            <p class="text" style="margin:0;">
                If you have any questions about your subscription, reply to this email or contact us at
                <a href="mailto:hello@faithstack.com" style="color:#6366f1;">hello@faithstack.com</a>.
                We're here to help.
            </p>
        </div>

    </div>

    <div class="footer">
        <p>FaithStack · <a href="#">Unsubscribe</a> · <a href="#">Privacy Policy</a></p>
        <p>You're receiving this because you subscribed to a FaithStack plan.</p>
    </div>
</div>
</body>
</html>
