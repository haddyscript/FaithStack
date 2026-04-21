<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Welcome to FaithStack</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; background-color: #f1f5f9; }
        table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        a { text-decoration: none; }
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; }
            .px-fluid { padding-left: 24px !important; padding-right: 24px !important; }
            .stack { display: block !important; width: 100% !important; }
            .btn-full { width: 100% !important; text-align: center !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Inter','Helvetica Neue',Helvetica,Arial,sans-serif;">

{{-- ── Preheader (hidden preview text) ── --}}
<div style="display:none;max-height:0;overflow:hidden;mso-hide:all;">
    Your FaithStack account is ready.
    @if($plan->effectiveTrialDays() > 0)
    Your {{ $plan->effectiveTrialDays() }}-day free trial starts now — your card won't be charged until {{ now()->addDays($plan->effectiveTrialDays())->format('M j') }}.
    @endif
    &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
</div>

{{-- ── Outer wrapper ── --}}
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
       style="background-color:#f1f5f9;">
    <tr>
        <td style="padding:40px 16px;">

            {{-- ── Email card ── --}}
            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                   class="email-container"
                   style="max-width:580px;margin:0 auto;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06),0 8px 32px rgba(0,0,0,0.08);">

                {{-- ══ HEADER ══ --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#312e81 0%,#4f46e5 50%,#6d28d9 100%);padding:40px 48px 36px;text-align:center;"
                        class="px-fluid">
                        {{-- Logo mark --}}
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto 20px;">
                            <tr>
                                <td style="width:44px;height:44px;background:rgba(255,255,255,0.15);border-radius:12px;text-align:center;vertical-align:middle;border:1px solid rgba(255,255,255,0.2);">
                                    <img src="https://faithstack.com/logo-mark-white.png"
                                         alt="✝"
                                         width="24" height="24"
                                         style="display:block;margin:10px auto;"
                                         onerror="this.style.display='none';this.parentNode.innerHTML='<span style=\'font-size:20px;line-height:44px;\'>✝</span>';">
                                </td>
                            </tr>
                        </table>
                        {{-- Brand name --}}
                        <p style="margin:0 0 18px;font-size:13px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.55);">FaithStack</p>
                        {{-- Headline --}}
                        <h1 style="margin:0 0 10px;font-size:26px;font-weight:700;color:#ffffff;letter-spacing:-0.5px;line-height:1.3;">
                            You're in. Let's get started.
                        </h1>
                        <p style="margin:0;font-size:15px;color:rgba(255,255,255,0.7);line-height:1.5;">
                            Your FaithStack site is live and ready for your community.
                        </p>
                    </td>
                </tr>

                {{-- ══ BODY ══ --}}
                <tr>
                    <td style="padding:40px 48px 8px;" class="px-fluid">

                        {{-- Greeting --}}
                        <p style="margin:0 0 16px;font-size:16px;font-weight:600;color:#0f172a;">
                            Hi {{ $tenant->name }},
                        </p>

                        {{-- Opening copy --}}
                        <p style="margin:0 0 28px;font-size:15px;color:#475569;line-height:1.7;">
                            Welcome to FaithStack — your new home for managing your ministry online.
                            Your account is set up on the <strong style="color:#1e293b;">{{ $plan->name }}</strong> plan
                            and your site is already live.
                            @if($plan->effectiveTrialDays() > 0)
                            Your <strong style="color:#1e293b;">{{ $plan->effectiveTrialDays() }}-day free trial</strong> has started —
                            your card won't be charged until
                            <strong style="color:#1e293b;">{{ now()->addDays($plan->effectiveTrialDays())->format('F j, Y') }}</strong>.
                            @endif
                        </p>

                        {{-- ── Account details box ── --}}
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                               style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;margin-bottom:32px;">
                            {{-- Row: Organization --}}
                            <tr>
                                <td style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#94a3b8;width:40%;">Organization</td>
                                            <td style="font-size:14px;font-weight:600;color:#1e293b;text-align:right;">{{ $tenant->name }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            {{-- Row: Plan --}}
                            <tr>
                                <td style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#94a3b8;width:40%;">Plan</td>
                                            <td style="text-align:right;">
                                                <span style="display:inline-block;padding:3px 12px;background:#eef2ff;color:#4f46e5;border-radius:20px;font-size:12px;font-weight:700;border:1px solid #c7d2fe;">
                                                    {{ $plan->name }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            {{-- Row: Site URL --}}
                            <tr>
                                <td style="padding:14px 20px;{{ $plan->effectiveTrialDays() > 0 ? 'border-bottom:1px solid #e2e8f0;' : '' }}">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#94a3b8;width:40%;">Your Site</td>
                                            <td style="text-align:right;">
                                                <a href="https://{{ $tenant->subdomain }}.{{ config('app.base_domain', 'faithstack.com') }}"
                                                   style="font-size:14px;font-weight:600;color:#4f46e5;text-decoration:none;">
                                                    {{ $tenant->subdomain }}.{{ config('app.base_domain', 'faithstack.com') }}
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            @if($plan->effectiveTrialDays() > 0)
                            {{-- Row: Trial ends --}}
                            <tr>
                                <td style="padding:14px 20px;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#94a3b8;width:40%;">Trial Ends</td>
                                            <td style="font-size:14px;font-weight:600;color:#1e293b;text-align:right;">
                                                {{ now()->addDays($plan->effectiveTrialDays())->format('F j, Y') }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            @endif
                        </table>

                        {{-- ── CTA button ── --}}
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto 12px;">
                            <tr>
                                <td style="border-radius:10px;background:#4f46e5;box-shadow:0 4px 14px rgba(79,70,229,0.35);">
                                    <a href="https://{{ $tenant->subdomain }}.{{ config('app.base_domain', 'faithstack.com') }}/admin"
                                       target="_blank"
                                       style="display:inline-block;padding:14px 36px;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;letter-spacing:0.01em;border-radius:10px;">
                                        Go to Your Dashboard &rarr;
                                    </a>
                                </td>
                            </tr>
                        </table>

                        {{-- Secondary link --}}
                        <p style="margin:0 0 36px;text-align:center;">
                            <a href="https://{{ $tenant->subdomain }}.{{ config('app.base_domain', 'faithstack.com') }}"
                               target="_blank"
                               style="font-size:13px;color:#6366f1;text-decoration:none;">
                                View your live site &rarr;
                            </a>
                        </p>

                        {{-- ── Divider ── --}}
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                               style="margin-bottom:32px;">
                            <tr>
                                <td style="border-top:1px solid #f1f5f9;"></td>
                            </tr>
                        </table>

                        {{-- ── Next steps ── --}}
                        <p style="margin:0 0 16px;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#94a3b8;">
                            Get started in 3 steps
                        </p>

                        {{-- Step 1 --}}
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                               style="margin-bottom:12px;">
                            <tr>
                                <td style="width:32px;vertical-align:top;padding-top:1px;">
                                    <div style="width:24px;height:24px;background:#4f46e5;border-radius:50%;text-align:center;line-height:24px;font-size:11px;font-weight:700;color:#ffffff;">1</div>
                                </td>
                                <td style="padding-left:12px;vertical-align:top;">
                                    <p style="margin:0 0 2px;font-size:14px;font-weight:600;color:#1e293b;">Customize your brand</p>
                                    <p style="margin:0;font-size:13px;color:#64748b;line-height:1.5;">Upload your logo and set your colors in Admin → Settings.</p>
                                </td>
                            </tr>
                        </table>

                        {{-- Step 2 --}}
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                               style="margin-bottom:12px;">
                            <tr>
                                <td style="width:32px;vertical-align:top;padding-top:1px;">
                                    <div style="width:24px;height:24px;background:#4f46e5;border-radius:50%;text-align:center;line-height:24px;font-size:11px;font-weight:700;color:#ffffff;">2</div>
                                </td>
                                <td style="padding-left:12px;vertical-align:top;">
                                    <p style="margin:0 0 2px;font-size:14px;font-weight:600;color:#1e293b;">Choose a theme and build your first page</p>
                                    <p style="margin:0;font-size:13px;color:#64748b;line-height:1.5;">Pick from our themes and publish your homepage in minutes.</p>
                                </td>
                            </tr>
                        </table>

                        {{-- Step 3 --}}
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                               style="margin-bottom:36px;">
                            <tr>
                                <td style="width:32px;vertical-align:top;padding-top:1px;">
                                    <div style="width:24px;height:24px;background:#4f46e5;border-radius:50%;text-align:center;line-height:24px;font-size:11px;font-weight:700;color:#ffffff;">3</div>
                                </td>
                                <td style="padding-left:12px;vertical-align:top;">
                                    <p style="margin:0 0 2px;font-size:14px;font-weight:600;color:#1e293b;">Set up your donation form</p>
                                    <p style="margin:0;font-size:13px;color:#64748b;line-height:1.5;">Enable online giving so your supporters can contribute securely.</p>
                                </td>
                            </tr>
                        </table>

                        {{-- ── Support note ── --}}
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                               style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;margin-bottom:8px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <p style="margin:0;font-size:13px;color:#475569;line-height:1.6;">
                                        Have questions or need a hand?
                                        Reply to this email or reach us at
                                        <a href="mailto:hello@faithstack.com"
                                           style="color:#4f46e5;font-weight:600;text-decoration:none;">hello@faithstack.com</a>.
                                        We typically respond within a few hours.
                                    </p>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                {{-- ══ FOOTER ══ --}}
                <tr>
                    <td style="padding:24px 48px 32px;border-top:1px solid #f1f5f9;" class="px-fluid">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="text-align:center;">
                                    <p style="margin:0 0 6px;font-size:12px;color:#94a3b8;">
                                        <strong style="color:#64748b;">FaithStack</strong>
                                        &nbsp;&middot;&nbsp;
                                        <a href="#" style="color:#94a3b8;text-decoration:none;">Unsubscribe</a>
                                        &nbsp;&middot;&nbsp;
                                        <a href="#" style="color:#94a3b8;text-decoration:none;">Privacy Policy</a>
                                    </p>
                                    <p style="margin:0;font-size:11px;color:#94a3b8;line-height:1.5;">
                                        You're receiving this because you created a FaithStack account.<br>
                                        Payments are securely handled by Stripe.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
            {{-- /card --}}

        </td>
    </tr>
</table>

</body>
</html>
