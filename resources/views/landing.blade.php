<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FaithStack is a multi-tenant CMS built for churches, nonprofits, and community organizations. 80+ themes, donation management, custom branding — launch in minutes.">
    <title>FaithStack — Beautiful Websites for Your Organization</title>
    <meta property="og:title"       content="FaithStack — Build Beautiful Websites for Your Organization">
    <meta property="og:description" content="80+ themes, donation management, drag-and-drop editor. Launch your church or nonprofit website in minutes.">
    <meta property="og:type"        content="website">

    {{-- GSAP + ScrollTrigger + Lenis (load before Alpine) --}}
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.14/dist/lenis.min.js"></script>

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

    {{-- Register Alpine components BEFORE Alpine boots --}}
    <script>
        document.addEventListener('alpine:init', () => {

            // Typewriter effect
            Alpine.data('typewriter', () => ({
                words:      ['churches', 'nonprofits', 'ministries', 'communities'],
                displayed:  'churches',
                current:    0,
                deleting:   false,
                init() { setTimeout(() => this.tick(), 2000); },
                tick() {
                    const word  = this.words[this.current];
                    const speed = this.deleting ? 55 : 110;

                    if (!this.deleting && this.displayed === word) {
                        setTimeout(() => { this.deleting = true; this.tick(); }, 2200);
                        return;
                    }
                    if (this.deleting && this.displayed === '') {
                        this.deleting = false;
                        this.current = (this.current + 1) % this.words.length;
                        setTimeout(() => this.tick(), 380);
                        return;
                    }
                    this.displayed = this.deleting
                        ? word.slice(0, this.displayed.length - 1)
                        : word.slice(0, this.displayed.length + 1);
                    setTimeout(() => this.tick(), speed);
                }
            }));

            // Theme preview modal
            Alpine.data('themeGallery', () => ({
                active: null,
                open(theme) { this.active = theme; document.body.style.overflow = 'hidden'; },
                close()     { this.active = null;  document.body.style.overflow = ''; },
            }));

            // Interactive Build Preview
            Alpine.data('buildPreview', () => ({
                theme: 'dark',
                type:  'church',
                animating: false,
                presets: {
                    church: {
                        headline: 'Welcome to Grace Community',
                        sub:      'Growing together in faith and love',
                        cta:      'Join Us Sunday',
                        badge:    '⛪ Faith & Community',
                        nav:      ['Grace Church', 'Sermons', 'Events', 'Give'],
                        features: [{ icon: '📖', label: 'Sermons' }, { icon: '📅', label: 'Events' }, { icon: '💝', label: 'Online Giving' }],
                    },
                    business: {
                        headline: 'Scale Your Business Today',
                        sub:      'Enterprise tools that actually work',
                        cta:      'Get Started Free',
                        badge:    '🚀 Built for Growth',
                        nav:      ['AcmeCorp', 'Solutions', 'Pricing', 'Contact'],
                        features: [{ icon: '📊', label: 'Analytics' }, { icon: '🔗', label: 'Integrations' }, { icon: '🛡️', label: 'Security' }],
                    },
                    portfolio: {
                        headline: 'Creative Work That Speaks',
                        sub:      'Designs and ideas that leave marks',
                        cta:      'View My Work',
                        badge:    '✨ Portfolio & Agency',
                        nav:      ['Studio', 'Work', 'About', 'Hire Me'],
                        features: [{ icon: '🎨', label: 'Gallery' }, { icon: '💼', label: 'Case Studies' }, { icon: '📬', label: 'Contact' }],
                    },
                },
                styles: {
                    dark:    { bg: '#09090b', surface: '#18181b', accent: '#6366f1', text: '#ffffff',  muted: 'rgba(255,255,255,0.45)', border: 'rgba(255,255,255,0.07)' },
                    light:   { bg: '#f8fafc', surface: '#ffffff',  accent: '#4f46e5', text: '#0f172a', muted: '#64748b',               border: 'rgba(0,0,0,0.07)' },
                    minimal: { bg: '#ffffff',  surface: '#f1f5f9',  accent: '#111827', text: '#111827', muted: '#9ca3af',               border: 'rgba(0,0,0,0.06)' },
                },
                get currentPreset() { return this.presets[this.type]; },
                get currentStyle()  { return this.styles[this.theme]; },
                setTheme(t) {
                    if (this.theme === t) return;
                    this.animating = true;
                    setTimeout(() => { this.theme = t; this.animating = false; }, 180);
                },
                setType(t) {
                    if (this.type === t) return;
                    this.animating = true;
                    setTimeout(() => { this.type = t; this.animating = false; }, 180);
                },
            }));

            // CMS Demo
            Alpine.data('cmsDemo', () => ({
                activePage:    'home',
                activeSection: 'hero',
                saving:        false,
                pages: [
                    { id: 'home',    label: 'Home',    icon: '🏠' },
                    { id: 'about',   label: 'About',   icon: '👥' },
                    { id: 'sermons', label: 'Sermons', icon: '📖' },
                    { id: 'give',    label: 'Give',    icon: '💝' },
                    { id: 'contact', label: 'Contact', icon: '✉️' },
                ],
                sections: {
                    home:    ['Hero', 'Features', 'Testimonials', 'CTA'],
                    about:   ['Team', 'Mission', 'History'],
                    sermons: ['Latest', 'Series', 'Archive'],
                    give:    ['Online Giving', 'Causes', 'Impact'],
                    contact: ['Form', 'Map', 'Info'],
                },
                get currentSections() { return this.sections[this.activePage] || []; },
                selectPage(id) {
                    this.activePage    = id;
                    this.activeSection = (this.sections[id]?.[0] || 'hero').toLowerCase();
                    this.triggerSave();
                },
                selectSection(s) {
                    this.activeSection = s.toLowerCase();
                    this.triggerSave();
                },
                triggerSave() {
                    this.saving = true;
                    setTimeout(() => { this.saving = false; }, 1300);
                },
            }));

            // Sticky Context-Aware CTA
            Alpine.data('stickyCta', () => ({
                text:     'Start Free Trial',
                visible:  false,
                changing: false,
                sectionMap: {
                    'features':     'Explore Features',
                    'themes':       'Explore Themes',
                    'build-preview':'Try It Free',
                    'cms-demo':     'See All Features',
                    'how-it-works': 'Start Building',
                    'modules':      'Explore All Modules',
                    'pricing':      'View Pricing',
                    'testimonials': 'Join 500+ Organizations',
                },
                init() {
                    const hero = document.querySelector('[data-cursor-glow]');
                    if (hero) new IntersectionObserver(([e]) => { this.visible = !e.isIntersecting; }, { threshold: 0.15 }).observe(hero);
                    Object.keys(this.sectionMap).forEach(id => {
                        const el = document.getElementById(id);
                        if (!el) return;
                        new IntersectionObserver(([e]) => { if (e.isIntersecting) this.updateText(this.sectionMap[id]); }, { threshold: 0.35 }).observe(el);
                    });
                },
                updateText(txt) {
                    if (this.text === txt) return;
                    this.changing = true;
                    setTimeout(() => { this.text = txt; this.changing = false; }, 180);
                },
            }));

        });
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ── Base ── */
        html { scroll-padding-top: 72px; }
        *, *::before, *::after { -webkit-font-smoothing: antialiased; box-sizing: border-box; }

        /* ── Noise overlay (premium texture) ── */
        body::after {
            content: '';
            position: fixed; inset: 0; z-index: 9999;
            pointer-events: none; user-select: none;
            opacity: 0.032;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 180px 180px;
        }

        /* ── Scroll-reveal ── */
        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.16,1,0.3,1),
                        transform 0.7s cubic-bezier(0.16,1,0.3,1);
            will-change: transform, opacity;
        }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .reveal[data-delay="1"] { transition-delay: 80ms; }
        .reveal[data-delay="2"] { transition-delay: 160ms; }
        .reveal[data-delay="3"] { transition-delay: 240ms; }
        .reveal[data-delay="4"] { transition-delay: 320ms; }
        .reveal[data-delay="5"] { transition-delay: 400ms; }
        .reveal[data-delay="6"] { transition-delay: 480ms; }

        /* ── Direction-aware (scroll-up variant) ── */
        .reveal.from-below  { transform: translateY(-20px); }

        /* ── Hero word-reveal ── */
        @keyframes wordIn {
            from { opacity: 0; transform: translateY(40px) skewY(4deg); }
            to   { opacity: 1; transform: translateY(0)   skewY(0deg); }
        }
        .word-reveal {
            display: inline-block; opacity: 0;
            animation: wordIn 0.75s cubic-bezier(0.16,1,0.3,1) forwards;
            will-change: transform, opacity;
        }

        /* ── Cursor glow in hero ── */
        [data-cursor-glow] { --cx: 50%; --cy: 50%; }
        [data-cursor-glow]::before {
            content: '';
            position: absolute; inset: 0; z-index: 2; pointer-events: none;
            background: radial-gradient(700px circle at var(--cx) var(--cy),
                rgba(99,102,241,0.13) 0%, transparent 55%);
            transition: background 0.1s ease;
        }

        /* ── Animated blobs ── */
        @keyframes blobFloat {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(35px,-25px) scale(1.06); }
            66%      { transform: translate(-25px,30px) scale(0.96); }
        }
        .blob {
            animation: blobFloat var(--dur,20s) ease-in-out infinite;
            will-change: transform;
        }

        /* ── Browser mockup float ── */
        @keyframes floatY {
            0%,100% { transform: rotate(-1deg) translateY(0); }
            50%      { transform: rotate(-1deg) translateY(-12px); }
        }
        .mockup-float { animation: floatY 5.5s ease-in-out infinite; will-change: transform; }

        /* ── Gradient shift (slow, premium) ── */
        @keyframes gradShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .grad-animate { background-size: 400% 400%; animation: gradShift 14s ease infinite; }

        /* ── Feature card tilt & hover ── */
        .feature-card {
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
            will-change: transform;
            transform-style: preserve-3d;
        }
        .feature-card:hover { box-shadow: 0 24px 60px -8px rgba(99,102,241,0.15); border-color: rgba(199,210,254,0.6); }
        .feature-card:hover .feature-icon { transform: scale(1.18) rotate(7deg); }
        .feature-icon { transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1); }

        /* ── Theme card tilt & hover ── */
        .theme-card { will-change: transform; transform-style: preserve-3d; }
        .theme-preview-img { transition: transform 0.6s cubic-bezier(0.16,1,0.3,1); }
        .theme-card:hover .theme-preview-img { transform: scale(1.05); }
        .theme-overlay { transition: opacity 0.35s ease; opacity: 0; }
        .theme-card:hover .theme-overlay { opacity: 1; }

        /* ── Pricing card hover glow ── */
        .pricing-card { will-change: transform; }
        .pricing-card:not(.pricing-featured):hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(99,102,241,0.13);
            border-color: rgba(165,180,252,0.5);
        }

        /* ── Pulse glow (Pro CTA) ── */
        @keyframes pulseGlow {
            0%,100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.35); }
            50%      { box-shadow: 0 0 0 10px rgba(255,255,255,0); }
        }
        .pulse-glow { animation: pulseGlow 2.8s ease-in-out infinite; }

        /* ── Magnetic button ── */
        .magnetic { will-change: transform; display: inline-flex; }

        /* ── Ripple ── */
        .ripple-btn { position: relative; overflow: hidden; }
        .ripple-btn .ripple-wave {
            position: absolute; border-radius: 50%;
            transform: scale(0); animation: rippleAnim 0.6s linear;
            background: rgba(255,255,255,0.22); pointer-events: none;
        }
        @keyframes rippleAnim { to { transform: scale(4); opacity: 0; } }

        /* ── Button press ── */
        .ripple-btn:active { transform: scale(0.97) !important; }

        /* ── Typewriter cursor ── */
        .tw-cursor {
            display: inline-block; width: 2px; height: 0.85em;
            background: currentColor; margin-left: 2px; vertical-align: -1px;
            animation: blink 1s step-end infinite;
        }
        @keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: 0; } }

        /* ── Link underline slide ── */
        .link-slide { position: relative; }
        .link-slide::after {
            content: ''; position: absolute; bottom: -2px; left: 0;
            width: 0; height: 1px; background: currentColor;
            transition: width 0.25s cubic-bezier(0.16,1,0.3,1);
        }
        .link-slide:hover::after { width: 100%; }

        /* ── Testimonial card hover ── */
        .testimonial-card { transition: transform 0.3s cubic-bezier(0.16,1,0.3,1), box-shadow 0.3s ease, border-color 0.3s ease; }
        .testimonial-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(99,102,241,0.08); border-color: rgba(199,210,254,0.5); }

        /* ── Section separators ── */
        .section-sep {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(148,163,184,0.15), transparent);
        }

        /* ── Modal ── */
        [x-cloak] { display: none !important; }

        /* ── Activity ticker ── */
        @keyframes tickerScroll { to { transform: translateX(-50%); } }
        .ticker-track { animation: tickerScroll 38s linear infinite; }
        .ticker-wrap:hover .ticker-track { animation-play-state: paused; }

        /* ── Icon pop (more dramatic than the simple scale/rotate) ── */
        @keyframes iconPop {
            0%   { transform: scale(1)    rotate(0deg);  }
            35%  { transform: scale(1.3)  rotate(15deg); }
            65%  { transform: scale(0.95) rotate(-5deg); }
            100% { transform: scale(1.18) rotate(7deg);  }
        }
        .feature-card:hover .feature-icon { animation: iconPop 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards; }

        /* ── Spring button ── */
        .btn-spring { transition: transform 0.2s cubic-bezier(0.34,1.56,0.64,1); }
        .btn-spring:hover  { transform: scale(1.05) translateY(-1px) !important; }
        .btn-spring:active { transform: scale(0.96) translateY(1px)  !important; transition-duration: 0.08s; }

        /* ── Skeleton shimmer ── */
        @keyframes shimmer {
            0%   { background-position: -200% 0; }
            100% { background-position:  200% 0; }
        }
        .skeleton      { background: linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%); background-size:200% 100%; animation: shimmer 1.8s ease-in-out infinite; border-radius:6px; }
        .skeleton-dark { background: linear-gradient(90deg,rgba(255,255,255,0.04) 25%,rgba(255,255,255,0.09) 50%,rgba(255,255,255,0.04) 75%); background-size:200% 100%; animation: shimmer 1.8s ease-in-out infinite; border-radius:6px; }

        /* ── CMS sidebar item hover nudge ── */
        .cms-sidebar-item { transition: all 0.18s cubic-bezier(0.16,1,0.3,1); }
        .cms-sidebar-item:hover { transform: translateX(2px); }

        /* ── Build preview browser transition ── */
        .bp-content { transition: opacity 0.22s ease, transform 0.22s cubic-bezier(0.16,1,0.3,1); }
        .bp-content.animating { opacity: 0.4; transform: scale(0.99); }

        /* ── Parallax layers ── */
        [data-parallax] { will-change: transform; }

        /* ── Floating accent chips (hero foreground) ── */
        @keyframes floatChip {
            0%,100% { transform: translateY(0) rotate(var(--rot,0deg)); }
            50%     { transform: translateY(-8px) rotate(var(--rot,0deg)); }
        }
        .float-chip { animation: floatChip var(--dur,6s) ease-in-out infinite; will-change: transform; }

        /* ── Theme Applied chip: Tetris magnetic-drop entrance ── */
        @keyframes tetrisDrop {
            0%   { transform: translateY(-280px) rotate(var(--rot,0deg)); opacity: 0; }
            12%  { opacity: 1; }
            78%  { transform: translateY(8px) rotate(var(--rot,0deg)); }
            91%  { transform: translateY(-3px) rotate(var(--rot,0deg)); }
            100% { transform: translateY(0) rotate(var(--rot,0deg)); }
        }
        @keyframes impactShake {
            0%   { transform: translate(0,   0)   rotate(var(--rot,0deg)); }
            11%  { transform: translate(-3px, 2px) rotate(var(--rot,0deg)); }
            22%  { transform: translate( 3px,-2px) rotate(var(--rot,0deg)); }
            33%  { transform: translate(-3px, 1px) rotate(var(--rot,0deg)); }
            44%  { transform: translate( 2px,-2px) rotate(var(--rot,0deg)); }
            55%  { transform: translate(-2px, 2px) rotate(var(--rot,0deg)); }
            66%  { transform: translate( 2px,-1px) rotate(var(--rot,0deg)); }
            77%  { transform: translate(-1px, 1px) rotate(var(--rot,0deg)); }
            88%  { transform: translate( 1px, 0)   rotate(var(--rot,0deg)); }
            100% { transform: translate(0,   0)   rotate(var(--rot,0deg)); }
        }
        .theme-applied-chip {
            animation:
                tetrisDrop  0.65s cubic-bezier(0.22,1,0.36,1) 0.4s  both,
                impactShake 0.32s linear                       1.05s both,
                floatChip   var(--dur,6.5s) ease-in-out        1.37s infinite;
            will-change: transform;
        }
        .mockup-nav-drop {
            animation:
                tetrisDrop  0.50s cubic-bezier(0.22,1,0.36,1) 0.65s both,
                impactShake 0.26s linear                       1.15s both;
            will-change: transform;
        }
        .mockup-content-drop {
            animation:
                tetrisDrop  0.55s cubic-bezier(0.22,1,0.36,1) 0.85s both,
                impactShake 0.28s linear                       1.40s both;
            will-change: transform;
        }

        /* ── Reduce motion ── */
        @media (prefers-reduced-motion: reduce) {
            .reveal, .word-reveal, .blob, .mockup-float, .grad-animate, .pulse-glow, .magnetic,
            .ticker-track, .float-chip, .theme-applied-chip, .mockup-nav-drop, .mockup-content-drop, .btn-spring {
                animation: none !important; transition: none !important;
                opacity: 1 !important; transform: none !important;
            }
        }
        /* ── Disable heavy effects on touch ── */
        @media (pointer: coarse) {
            .mockup-float { animation-duration: 8s; }
            .blob { animation-duration: 30s; }
            .ticker-track { animation-duration: 55s; }
        }

        /* ════════════════════════════════════════════════════════
           GSAP 3D LAYER
        ════════════════════════════════════════════════════════ */

        /* Perspective context for 3D card rotations.
           Applied to grid containers — safe, doesn't affect fixed elements. */
        #features .grid,
        #modules .grid,
        #how-it-works .grid {
            perspective: 1400px;
            perspective-origin: 50% -10%;
        }

        /* 3D transform context for hero mockup tilt */
        .mockup-float { transform-style: preserve-3d; }

        /* GSAP-driven section inners: camera-zoom origin + paint containment */
        section > .max-w-7xl,
        section > .max-w-6xl {
            transform-origin: 50% 0%;
            contain: layout style;
        }

        /* Module + feature card containers: GPU layer, contained layout */
        .module-card, .feature-card {
            will-change: transform, opacity;
            contain: layout style;
        }

        /* Promote every card to its own GPU layer for 3D child rotations */
        #features .grid > *,
        #modules .grid > * {
            transform: translateZ(0);
        }

        /* CRM glow pulse — CSS @keyframes (opacity only = compositor, no paint) */
        @keyframes crmGlowPulse {
            0%, 100% { opacity: 0.55; }
            50%       { opacity: 1;    }
        }
        .js-crm-glow {
            will-change: opacity;
        }

        /* Word-reveal spans generated by GSAP heading split */
        .gs-word { vertical-align: bottom; }

        /* Z-tunnel outgoing sections: ensure no clip of content */
        section { transform-origin: 50% 0%; overflow: visible; }

        /* Prevent GSAP-animated .reveal elements from double-flashing.
           GSAP inline styles always win (highest specificity), so this
           is just a safety net for the edge-case where GSAP hasn't set
           its fromTo yet and IntersectionObserver fires first. */
        section:not([data-cursor-glow]) .feature-card.reveal,
        section:not([data-cursor-glow]) .module-card.reveal,
        section:not([data-cursor-glow]) .testimonial-card.reveal,
        section:not([data-cursor-glow]) .pricing-card.reveal {
            /* Keep opacity:0 (from .reveal) so there's no flash before GSAP runs */
            opacity: 0;
        }

        /* Mobile: flatten all 3D effects gracefully */
        @media (pointer: coarse) {
            #features .grid,
            #modules .grid,
            #how-it-works .grid {
                perspective: none;
            }
            section > .max-w-7xl,
            section > .max-w-6xl {
                transform: none !important;
                opacity: 1 !important;
            }
            .module-card, .feature-card {
                transform: none !important;
                opacity: 1 !important;
                will-change: auto;
            }
        }
    </style>
</head>
<body class="bg-white antialiased" x-data>

    <x-landing.nav />
    <x-landing.hero />
    <div class="section-sep"></div>
    <x-landing.features :features="$features" />
    <div class="section-sep"></div>
    <x-landing.themes :themes="$themes" />
    <div class="section-sep"></div>
    <x-landing.build-preview />
    <div class="section-sep"></div>
    <x-landing.cms-demo />
    <div class="section-sep"></div>
    <x-landing.how-it-works :steps="$steps" />
    <x-landing.modules />
    <div class="section-sep"></div>
    <x-landing.pricing :plans="$plans" />
    <div class="section-sep"></div>
    <x-landing.testimonials :testimonials="$testimonials" />
    <x-landing.activity-ticker />
    <x-landing.final-cta />
    <x-landing.footer />

    {{-- Sticky context-aware CTA (desktop only) --}}
    <div x-data="stickyCta"
         x-show="visible"
         x-cloak
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 translate-y-3 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0 translate-y-3 scale-95"
         class="hidden md:block fixed bottom-6 right-6 z-40">
        <a href="{{ url('/register') }}?plan=free-trial"
           class="ripple-btn btn-spring flex items-center gap-3 pl-5 pr-4 py-3.5 rounded-2xl bg-[#09090b] text-white shadow-2xl shadow-black/50 border border-white/[0.08] no-underline">
            <span class="relative flex h-2 w-2 flex-shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
            </span>
            <span class="text-sm font-semibold min-w-[120px] text-center"
                  :class="changing ? 'opacity-0 -translate-y-1' : 'opacity-100 translate-y-0'"
                  style="transition: opacity 0.18s ease, transform 0.18s cubic-bezier(0.16,1,0.3,1)"
                  x-text="text"></span>
            <svg class="w-4 h-4 flex-shrink-0 text-white/50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>

    {{-- GSAP 3D / Lenis animations (after DOM is built) --}}
    <script src="{{ asset('js/landing-3d.js') }}" defer></script>

    <script>
    (() => {
        const isMobile = window.matchMedia('(pointer: coarse)').matches;

        // ── Parallax layers ────────────────────────────────────────────────
        if (!isMobile) {
            const parallaxEls = document.querySelectorAll('[data-parallax]');
            if (parallaxEls.length) {
                window.addEventListener('scroll', () => {
                    requestAnimationFrame(() => {
                        const sy = window.scrollY;
                        parallaxEls.forEach(el => {
                            const speed  = parseFloat(el.dataset.parallax) || 0.15;
                            const offset = sy * speed;
                            el.style.transform = `translateY(${offset}px)`;
                        });
                    });
                }, { passive: true });
            }
        }

        // ── Scroll state ───────────────────────────────────────────────────
        let raf = false, lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            if (!raf) {
                requestAnimationFrame(() => {
                    document.documentElement.style.setProperty('--scroll-y', window.scrollY + 'px');
                    lastScrollY = window.scrollY;
                    raf = false;
                });
                raf = true;
            }
        }, { passive: true });

        // ── Direction-aware reveal ─────────────────────────────────────────
        let prevY = 0;
        const revealObs = new IntersectionObserver((entries) => {
            const goingDown = window.scrollY >= prevY;
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (!goingDown) entry.target.classList.add('from-below');
                    entry.target.classList.add('in');
                    revealObs.unobserve(entry.target);
                }
            });
            prevY = window.scrollY;
        }, { threshold: 0.07, rootMargin: '0px 0px -36px 0px' });

        document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

        // ── Hero word reveal ───────────────────────────────────────────────
        document.querySelectorAll('.word-reveal').forEach((el, i) => {
            el.style.animationDelay = `${0.1 + i * 0.09}s`;
        });

        // ── Cursor glow (hero only, skip mobile) ──────────────────────────
        if (!isMobile) {
            const glowEl = document.querySelector('[data-cursor-glow]');
            if (glowEl) {
                let cx = 0, cy = 0, tx = 0, ty = 0, glowRaf = null;
                glowEl.addEventListener('mousemove', (e) => {
                    const r = glowEl.getBoundingClientRect();
                    tx = e.clientX - r.left; ty = e.clientY - r.top;
                }, { passive: true });
                glowEl.addEventListener('mouseenter', () => {
                    function lerp(a, b, t) { return a + (b - a) * t; }
                    function animate() {
                        cx = lerp(cx, tx, 0.08); cy = lerp(cy, ty, 0.08);
                        glowEl.style.setProperty('--cx', cx + 'px');
                        glowEl.style.setProperty('--cy', cy + 'px');
                        glowRaf = requestAnimationFrame(animate);
                    }
                    glowRaf = requestAnimationFrame(animate);
                });
                glowEl.addEventListener('mouseleave', () => {
                    if (glowRaf) cancelAnimationFrame(glowRaf);
                });
            }
        }

        // ── Magnetic buttons (desktop only) ──────────────────────────────
        if (!isMobile) {
            document.querySelectorAll('[data-magnetic]').forEach(btn => {
                btn.addEventListener('mousemove', (e) => {
                    const r  = btn.getBoundingClientRect();
                    const dx = (e.clientX - (r.left + r.width / 2))  * 0.32;
                    const dy = (e.clientY - (r.top  + r.height / 2)) * 0.32;
                    btn.style.transform    = `translate(${dx}px, ${dy}px)`;
                    btn.style.transition   = 'transform 0.15s ease';
                });
                btn.addEventListener('mouseleave', () => {
                    btn.style.transform  = '';
                    btn.style.transition = 'transform 0.6s cubic-bezier(0.23,1,0.32,1)';
                });
            });
        }

        // ── Card 3D tilt (desktop only) ──────────────────────────────────
        if (!isMobile) {
            document.querySelectorAll('[data-tilt]').forEach(card => {
                const intensity = parseFloat(card.dataset.tilt) || 8;
                let tiltRaf = null, tx = 0, ty = 0, cx = 0, cy = 0;

                card.addEventListener('mousemove', (e) => {
                    const r = card.getBoundingClientRect();
                    tx = ((e.clientX - r.left) / r.width  - 0.5) * intensity;
                    ty = ((e.clientY - r.top)  / r.height - 0.5) * intensity;
                });

                card.addEventListener('mouseenter', () => {
                    function animate() {
                        cx += (tx - cx) * 0.1; cy += (ty - cy) * 0.1;
                        card.style.transform = `perspective(900px) rotateX(${-cy}deg) rotateY(${cx}deg) translateZ(8px)`;
                        tiltRaf = requestAnimationFrame(animate);
                    }
                    tiltRaf = requestAnimationFrame(animate);
                });
                card.addEventListener('mouseleave', () => {
                    if (tiltRaf) cancelAnimationFrame(tiltRaf);
                    tx = 0; ty = 0; cx = 0; cy = 0;
                    card.style.transform  = '';
                    card.style.transition = 'transform 0.5s cubic-bezier(0.23,1,0.32,1)';
                    setTimeout(() => card.style.transition = '', 500);
                });
            });
        }

        // ── Animated counters ─────────────────────────────────────────────
        const cntObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    runCounter(entry.target);
                    cntObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('[data-counter]').forEach(el => cntObs.observe(el));

        function runCounter(el) {
            const raw    = el.dataset.counter;
            const suffix = el.dataset.suffix  || '';
            const prefix = el.dataset.prefix  || '';
            const dec    = el.dataset.decimals || 0;
            const target = parseFloat(raw);
            const dur    = 2200;
            const t0     = performance.now();

            (function step(now) {
                const p   = Math.min((now - t0) / dur, 1);
                const ease = 1 - Math.pow(1 - p, 3);
                const val  = (target * ease).toFixed(dec);
                el.textContent = prefix + Number(val).toLocaleString() + suffix;
                if (p < 1) requestAnimationFrame(step);
            })(t0);
        }

        // ── Ripple on click ───────────────────────────────────────────────
        document.querySelectorAll('.ripple-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const r    = btn.getBoundingClientRect();
                const size = Math.max(r.width, r.height);
                const rip  = document.createElement('span');
                rip.className = 'ripple-wave';
                rip.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX-r.left-size/2}px;top:${e.clientY-r.top-size/2}px`;
                btn.appendChild(rip);
                rip.addEventListener('animationend', () => rip.remove());
            });
        });

    })();
    </script>
</body>
</html>
