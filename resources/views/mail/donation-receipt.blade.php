<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Donation Receipt</title>
<style>
    body { margin:0; padding:0; background:#f1f5f9; font-family:'Inter','Helvetica Neue',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
    .wrap { max-width:580px; margin:40px auto; }
    .card { background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
    .header { background:linear-gradient(135deg,#059669,#10b981); padding:36px 40px 32px; text-align:center; }
    .header-icon { font-size:40px; margin:0 auto 16px; display:block; }
    .header h1 { margin:0; color:#ffffff; font-size:24px; font-weight:700; letter-spacing:-0.5px; }
    .header p { margin:6px 0 0; color:rgba(255,255,255,0.80); font-size:14px; }
    .body { padding:36px 40px; }
    .greeting { font-size:16px; color:#1e293b; font-weight:600; margin:0 0 12px; }
    .text { font-size:14px; color:#475569; line-height:1.7; margin:0 0 24px; }
    .amount-box { text-align:center; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:24px; margin:0 0 24px; }
    .amount-label { font-size:12px; font-weight:700; color:#059669; text-transform:uppercase; letter-spacing:0.08em; margin:0 0 6px; }
    .amount-value { font-size:42px; font-weight:800; color:#064e3b; letter-spacing:-1px; margin:0; }
    .amount-freq { font-size:13px; color:#6b7280; margin:4px 0 0; }
    .info-box { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:20px 24px; margin:0 0 28px; }
    .info-row { display:flex; justify-content:space-between; align-items:center; margin:0 0 12px; }
    .info-row:last-child { margin:0; padding-top:12px; border-top:1px solid #e2e8f0; }
    .info-label { font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; }
    .info-value { font-size:14px; font-weight:600; color:#1e293b; }
    .info-badge { display:inline-block; padding:3px 10px; background:#dcfce7; color:#059669; border-radius:20px; font-size:12px; font-weight:700; }
    .tax-note { background:#fefce8; border:1px solid #fde68a; border-radius:8px; padding:12px 16px; font-size:12px; color:#92400e; margin:0 0 28px; line-height:1.6; }
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
            <span class="header-icon">💚</span>
            <h1>Thank you for giving!</h1>
            <p>Your donation to {{ $tenant->name }} has been received</p>
        </div>

        <div class="body">
            <p class="greeting">Dear {{ $donation->full_name }},</p>
            <p class="text">
                Your generosity makes a real difference. This email is your official donation receipt —
                please keep it for your records.
            </p>

            {{-- Amount highlight --}}
            <div class="amount-box">
                <p class="amount-label">Donation Amount</p>
                <p class="amount-value">${{ number_format((float) $donation->amount, 2) }}</p>
                <p class="amount-freq">
                    @if($donation->frequency === 'monthly') Monthly recurring donation
                    @elseif($donation->frequency === 'yearly') Annual recurring donation
                    @else One-time donation
                    @endif
                </p>
            </div>

            {{-- Receipt details --}}
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Donor</span>
                    <span class="info-value">{{ $donation->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Organization</span>
                    <span class="info-value">{{ $tenant->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date</span>
                    <span class="info-value">{{ $donation->created_at->format('F j, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-badge">Received</span>
                </div>
                @if($donation->notes)
                <div class="info-row">
                    <span class="info-label">Notes</span>
                    <span class="info-value" style="text-align:right;max-width:320px;">{{ $donation->notes }}</span>
                </div>
                @endif
            </div>

            <div class="tax-note">
                <strong>Tax Receipt Notice:</strong> Please retain this email as documentation of your charitable donation.
                {{ $tenant->name }} is a registered organization. Consult your tax advisor regarding deductibility.
            </div>

            <hr class="divider">

            <p class="text" style="margin:0;">
                Questions about your donation? Contact {{ $tenant->name }} directly at
                <a href="mailto:{{ $tenant->email }}" style="color:#6366f1;">{{ $tenant->email }}</a>.
            </p>
        </div>

    </div>

    <div class="footer">
        <p>Donation processed securely via <strong>FaithStack</strong></p>
        <p><a href="#">Privacy Policy</a></p>
    </div>
</div>
</body>
</html>
