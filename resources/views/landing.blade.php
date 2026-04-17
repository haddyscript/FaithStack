<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FaithStack is a multi-tenant CMS built for churches, nonprofits, and community organizations. 80+ themes, donation management, custom branding — launch in minutes.">
    <title>FaithStack — Beautiful Websites for Your Organization</title>
    <meta property="og:title"       content="FaithStack — Build Beautiful Websites for Your Organization">
    <meta property="og:description" content="80+ themes, donation management, drag-and-drop editor. Launch your church or nonprofit website in minutes.">
    <meta property="og:type"        content="website">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: { 50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81',950:'#1e1b4b' },
                        purple: { 50:'#faf5ff',100:'#f3e8ff',200:'#e9d5ff',300:'#d8b4fe',400:'#c084fc',500:'#a855f7',600:'#9333ea',700:'#7e22ce',800:'#6b21a8',900:'#581c87',950:'#3b0764' },
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ── Base ── */
        html { scroll-padding-top: 72px; }
        * { -webkit-font-smoothing: antialiased; }

        /* ── Scroll-reveal system ── */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.65s cubic-bezier(0.16,1,0.3,1),
                        transform 0.65s cubic-bezier(0.16,1,0.3,1);
        }
        .reveal.in { opacity: 1; transform: translateY(0); }

        /* stagger delays */
        .reveal[data-delay="1"] { transition-delay: 80ms; }
        .reveal[data-delay="2"] { transition-delay: 160ms; }
        .reveal[data-delay="3"] { transition-delay: 240ms; }
        .reveal[data-delay="4"] { transition-delay: 320ms; }
        .reveal[data-delay="5"] { transition-delay: 400ms; }
        .reveal[data-delay="6"] { transition-delay: 480ms; }

        /* ── Hero word-reveal ── */
        @keyframes wordIn {
            from { opacity: 0; transform: translateY(36px) skewY(3deg); }
            to   { opacity: 1; transform: translateY(0)   skewY(0deg); }
        }
        .word-reveal { display: inline-block; opacity: 0; animation: wordIn 0.7s cubic-bezier(0.16,1,0.3,1) forwards; }

        /* ── Floating blob animation ── */
        @keyframes blobFloat {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(30px,-20px) scale(1.05); }
            66%      { transform: translate(-20px,25px) scale(0.97); }
        }
        .blob { animation: blobFloat var(--duration,18s) ease-in-out infinite; }

        /* ── Browser mockup bob ── */
        @keyframes float {
            0%,100% { transform: translateY(0px) rotate(-1deg); }
            50%      { transform: translateY(-10px) rotate(-1deg); }
        }
        .mockup-float { animation: float 5s ease-in-out infinite; }

        /* ── Gradient shift (CTA section) ── */
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .gradient-animate {
            background-size: 300% 300%;
            animation: gradientShift 8s ease infinite;
        }

        /* ── Pulse glow (Pro CTA button) ── */
        @keyframes pulseGlow {
            0%,100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.4); }
            50%      { box-shadow: 0 0 0 10px rgba(255,255,255,0); }
        }
        .pulse-glow { animation: pulseGlow 2.5s ease-in-out infinite; }

        /* ── Ripple on buttons ── */
        .ripple-btn { position: relative; overflow: hidden; }
        .ripple-btn .ripple {
            position: absolute; border-radius: 50%;
            transform: scale(0); animation: rippleAnim 0.55s linear;
            background: rgba(255,255,255,0.25); pointer-events: none;
        }
        @keyframes rippleAnim { to { transform: scale(4); opacity: 0; } }

        /* ── Feature card hover ── */
        .feature-card { transition: transform 0.3s cubic-bezier(0.16,1,0.3,1), box-shadow 0.3s ease, border-color 0.3s ease; }
        .feature-card:hover { transform: translateY(-6px); }
        .feature-card:hover .feature-icon { transform: scale(1.15) rotate(6deg); }
        .feature-icon { transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1); }

        /* ── Theme card hover ── */
        .theme-card { transition: transform 0.35s cubic-bezier(0.16,1,0.3,1), box-shadow 0.35s ease; }
        .theme-card:hover { transform: translateY(-8px); box-shadow: 0 24px 60px rgba(0,0,0,0.12); }
        .theme-preview-img { transition: transform 0.5s cubic-bezier(0.16,1,0.3,1); }
        .theme-card:hover .theme-preview-img { transform: scale(1.04); }
        .theme-overlay { transition: opacity 0.3s ease; opacity: 0; }
        .theme-card:hover .theme-overlay { opacity: 1; }

        /* ── Pricing card hover ── */
        .pricing-card { transition: transform 0.3s cubic-bezier(0.16,1,0.3,1), box-shadow 0.3s ease, border-color 0.3s ease; }
        .pricing-card:not(.pricing-featured):hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(99,102,241,0.12);
            border-color: rgb(199,210,254);
        }

        /* ── Link underline slide ── */
        .link-slide { position: relative; }
        .link-slide::after {
            content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 1px;
            background: currentColor; transition: width 0.25s ease;
        }
        .link-slide:hover::after { width: 100%; }

        /* ── Smooth scroll nav ── */
        @media (prefers-reduced-motion: reduce) {
            .reveal, .word-reveal, .blob, .mockup-float, .gradient-animate, .pulse-glow { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
        }
    </style>
</head>
<body class="bg-white antialiased" x-data>

    <x-landing.nav />
    <x-landing.hero />
    <x-landing.features :features="$features" />
    <x-landing.themes :themes="$themes" />
    <x-landing.how-it-works :steps="$steps" />
    <x-landing.pricing :plans="$plans" />
    <x-landing.testimonials :testimonials="$testimonials" />
    <x-landing.final-cta />
    <x-landing.footer />

    <script>
    (() => {
        // ── Scroll reveal ──────────────────────────────────────────────
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    revealObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

        // ── Ripple effect ──────────────────────────────────────────────
        document.querySelectorAll('.ripple-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const r = document.createElement('span');
                const rect = btn.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                r.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX-rect.left-size/2}px;top:${e.clientY-rect.top-size/2}px`;
                r.className = 'ripple';
                btn.appendChild(r);
                r.addEventListener('animationend', () => r.remove());
            });
        });

        // ── Hero word reveal trigger ───────────────────────────────────
        document.querySelectorAll('.word-reveal').forEach((el, i) => {
            el.style.animationDelay = `${0.15 + i * 0.08}s`;
        });
    })();
    </script>
</body>
</html>
