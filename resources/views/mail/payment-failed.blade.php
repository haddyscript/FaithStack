<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Failed</title>
<style>
    body { margin:0; padding:0; background:#f1f5f9; font-family:'Inter','Helvetica Neue',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
    .wrap { max-width:580px; margin:40px auto; }
    .card { background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
    .header { background:linear-gradient(135deg,#dc2626,#ef4444); padding:36px 40px 32px; text-align:center; }
    .header-icon { font-size:40px; margin:0 auto 16px; display:block; }
    .header h1 { margin:0; color:#ffffff; font-size:24px; font-weight:700; letter-spacing:-0.5px; }
    .header p { margin:6px 0 0; color:rgba(255,255,255,0.85); font-size:14px; }
    .body { padding:36px 40px; }
    .greeting { font-size:16px; color:#1e293b; font-weight:600; margin:0 0 12px; }
    .text { font-size:14px; color:#475569; line-height:1.7; margin:0 0 24px; }
    .alert-box { background:#fef2f2; border:1.5px solid #fecaca; border-radius:12px; padding:20px 24px; margin:0 0 24px; }
    .alert-box p { margin:0; font-size:14px; color:#991b1b; line-height:1.6; }
    .info-box { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:20px 24px; margin:0 0 28px; }
    .info-row { display:flex; justify-content:space-between; align-items:center; margin:0 0 12px; }
    .info-row:last-child { margin:0; padding-top:12px; border-top:1px solid #e2e8f0; }
    .info-label { font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; }
    .info-value { font-size:14px; font-weight:600; color:#1e293b; }
    .steps { margin:0 0 28px; padding:0; list-style:none; }
    .steps li { font-size:14px; color:#475569; padding:8px 0 8px 28px; position:relative; }
    .steps li::before { content:"→"; position:absolute; left:0; top:8px; color:#dc2626; font-weight:700; }
    .cta-wrap { text-align:center; margin:0 0 32px; }
    .cta-btn { display:inline-block; padding:13px 32px; background:#dc2626; color:#ffffff; text-decoration:none; border-radius:10px; font-size:14px; font-weight:700; letter-spacing:0.01em; }
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
            <span class="header-icon">⚠️</span>
            <h1>Payment failed</h1>
            <p>We couldn't process your subscription payment</p>
        </div>

        <div class="body">
            <p class="greeting">Hi {{ $tenant->name }},</p>

            <div class="alert-box">
                <p>
                    We were unable to process the payment for your FaithStack subscription.
                    Your site will remain active for now, but please update your billing details
                    to avoid any service interruption.
                </p>
            </div>

            {{-- Account info --}}
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Organization</span>
                    <span class="info-value">{{ $tenant->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Billing Email</span>
                    <span class="info-value">{{ $tenant->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Failed On</span>
                    <span class="info-value">{{ now()->format('F j, Y') }}</span>
                </div>
            </div>

            <p class="text" style="font-weight:600;color:#1e293b;margin-bottom:12px;">Common reasons for failure:</p>
            <ul class="steps">
                <li>Card expired or cancelled</li>
                <li>Insufficient funds at time of charge</li>
                <li>Card issuer declined the transaction</li>
                <li>Billing address or CVV mismatch</li>
            </ul>

            {{-- CTA --}}
            <div class="cta-wrap">
                <a href="{{ 'https://' . $tenant->subdomain . '.' . config('app.base_domain', 'faithstack.com') . '/admin/billing' }}"
                   class="cta-btn">
                    Update Payment Method →
                </a>
            </div>

            <hr class="divider">

            <p class="text" style="margin:0;">
                If you believe this is an error or need assistance, contact us at
                <a href="mailto:hello@faithstack.com" style="color:#6366f1;">hello@faithstack.com</a>
                and we'll sort it out right away.
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
