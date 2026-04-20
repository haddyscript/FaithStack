<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Donation Received</title>
<style>
    body { margin:0; padding:0; background:#f1f5f9; font-family:'Inter','Helvetica Neue',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
    .wrap { max-width:580px; margin:40px auto; }
    .card { background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
    .header { background:linear-gradient(135deg,#0369a1,#0ea5e9); padding:36px 40px 32px; text-align:center; }
    .header-icon { font-size:40px; margin:0 auto 16px; display:block; }
    .header h1 { margin:0; color:#ffffff; font-size:24px; font-weight:700; letter-spacing:-0.5px; }
    .header p { margin:6px 0 0; color:rgba(255,255,255,0.80); font-size:14px; }
    .body { padding:36px 40px; }
    .text { font-size:14px; color:#475569; line-height:1.7; margin:0 0 24px; }
    .amount-box { text-align:center; background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:24px; margin:0 0 24px; }
    .amount-label { font-size:12px; font-weight:700; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.08em; margin:0 0 6px; }
    .amount-value { font-size:42px; font-weight:800; color:#1e3a8a; letter-spacing:-1px; margin:0; }
    .amount-freq { font-size:13px; color:#6b7280; margin:4px 0 0; }
    .info-box { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:20px 24px; margin:0 0 28px; }
    .info-row { display:flex; justify-content:space-between; align-items:center; margin:0 0 12px; }
    .info-row:last-child { margin:0; padding-top:12px; border-top:1px solid #e2e8f0; }
    .info-label { font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; }
    .info-value { font-size:14px; font-weight:600; color:#1e293b; }
    .cta-wrap { text-align:center; margin:0 0 32px; }
    .cta-btn { display:inline-block; padding:13px 32px; background:#0369a1; color:#ffffff; text-decoration:none; border-radius:10px; font-size:14px; font-weight:700; letter-spacing:0.01em; }
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
            <span class="header-icon">🔔</span>
            <h1>New donation received!</h1>
            <p>{{ $tenant->name }} just received a new gift</p>
        </div>

        <div class="body">
            <p class="text">
                Great news — <strong>{{ $donation->full_name }}</strong> just made a donation to your organization.
                Here are the details:
            </p>

            {{-- Amount highlight --}}
            <div class="amount-box">
                <p class="amount-label">Donation Amount</p>
                <p class="amount-value">${{ number_format((float) $donation->amount, 2) }}</p>
                <p class="amount-freq">
                    @if($donation->frequency === 'monthly') Monthly recurring
                    @elseif($donation->frequency === 'yearly') Annual recurring
                    @else One-time gift
                    @endif
                </p>
            </div>

            {{-- Donor details --}}
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Donor Name</span>
                    <span class="info-value">{{ $donation->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Donor Email</span>
                    <span class="info-value">{{ $donation->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date & Time</span>
                    <span class="info-value">{{ $donation->created_at->format('F j, Y — g:i A') }}</span>
                </div>
                @if($donation->notes)
                <div class="info-row">
                    <span class="info-label">Donor Note</span>
                    <span class="info-value" style="text-align:right;max-width:320px;font-style:italic;">"{{ $donation->notes }}"</span>
                </div>
                @endif
            </div>

            {{-- CTA to admin dashboard --}}
            <div class="cta-wrap">
                <a href="{{ 'https://' . $tenant->subdomain . '.' . config('app.base_domain', 'faithstack.com') . '/admin/donations' }}"
                   class="cta-btn">
                    View All Donations →
                </a>
            </div>

            <hr class="divider">

            <p class="text" style="margin:0;font-size:13px;">
                A receipt has automatically been sent to {{ $donation->email }}.
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
