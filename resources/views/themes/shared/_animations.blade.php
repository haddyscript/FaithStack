<style>
/* ═══════════════════════════════════════════════════════
   FaithStack — Animation & Interaction System
   Scroll: IntersectionObserver + data-animate / data-stagger
   Instant: hero-enter keyframes (no JS needed)
   Micro:   CSS hover / active transitions
════════════════════════════════════════════════════════*/

/* ── Variables ─────────────────────────────────────── */
:root {
    --anim-dur:   650ms;
    --anim-ease:  cubic-bezier(0.16, 1, 0.3, 1);
    --anim-step:  90ms;
}

/* ── Scroll-animated elements — initial hidden state ── */
[data-animate] {
    opacity: 0;
    will-change: transform, opacity;
    transition: opacity var(--anim-dur) var(--anim-ease),
                transform var(--anim-dur) var(--anim-ease);
}
[data-animate="fade-up"]     { transform: translateY(36px); }
[data-animate="fade-down"]   { transform: translateY(-36px); }
[data-animate="slide-left"]  { transform: translateX(56px); }
[data-animate="slide-right"] { transform: translateX(-56px); }
[data-animate="scale-in"]    { transform: scale(0.90); }
[data-animate="zoom-in"]     { transform: scale(0.75); }
[data-animate="fade-in"]     { transform: none; }

/* Visible state — JS adds .anim-in */
.anim-in {
    opacity: 1 !important;
    transform: none !important;
}

/* ── Stagger parent ────────────────────────────────── */
[data-stagger] > * {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity var(--anim-dur) var(--anim-ease),
                transform var(--anim-dur) var(--anim-ease);
}

/* ── Hero entrance (CSS-only, fires immediately) ───── */
@keyframes heroFadeUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: none; }
}
@keyframes heroFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
.hero-enter   { animation: heroFadeUp var(--anim-dur) var(--anim-ease) both; }
.hero-enter-1 { animation-delay: 80ms; }
.hero-enter-2 { animation-delay: 220ms; }
.hero-enter-3 { animation-delay: 380ms; }
.hero-enter-4 { animation-delay: 520ms; }

/* ── Hover: lift card ──────────────────────────────── */
.hover-lift {
    transition: transform 220ms ease, box-shadow 220ms ease !important;
}
.hover-lift:hover {
    transform: translateY(-6px) !important;
    box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
}

/* ── Hover: image zoom ─────────────────────────────── */
.img-zoom { overflow: hidden; }
.img-zoom img {
    transition: transform 500ms var(--anim-ease);
    will-change: transform;
}
.img-zoom:hover img { transform: scale(1.07); }

/* ── Gallery item ──────────────────────────────────── */
.gallery-item { overflow: hidden; cursor: pointer; }
.gallery-item img {
    transition: transform 500ms var(--anim-ease);
    will-change: transform;
}
.gallery-item:hover img { transform: scale(1.09); }
.gallery-overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,0);
    transition: background 300ms ease;
    display: flex; align-items: center; justify-content: center;
}
.gallery-item:hover .gallery-overlay { background: rgba(0,0,0,0.22); }
.gallery-overlay svg { opacity: 0; transition: opacity 250ms ease; }
.gallery-item:hover .gallery-overlay svg { opacity: 1; }

/* ── Button micro-interaction ──────────────────────── */
.btn-primary,
[class*="btn-"] {
    transition: opacity 150ms ease, transform 150ms ease, box-shadow 200ms ease !important;
}
.btn-primary:hover  { box-shadow: 0 8px 24px rgba(0,0,0,0.25) !important; }
.btn-primary:active,
[class*="btn-"]:active { transform: scale(0.96) !important; }

/* ── Nav link slide-underline ──────────────────────── */
.nav-link { position: relative; }
.nav-link::after {
    content: '';
    position: absolute;
    bottom: -3px; left: 0;
    width: 0; height: 2px;
    background: var(--secondary);
    border-radius: 2px;
    transition: width 280ms var(--anim-ease);
}
.nav-link:hover::after { width: 100%; }

/* ── Section divider fade ──────────────────────────── */
.section-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(0,0,0,0.08), transparent);
    margin: 0;
}

/* ── Smooth focus ring ─────────────────────────────── */
:focus-visible {
    outline: 2px solid var(--secondary);
    outline-offset: 3px;
    border-radius: 4px;
}

/* ── Reduced motion ────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
    [data-animate], [data-stagger] > * { opacity: 1 !important; transform: none !important; transition: none !important; }
    .hero-enter, .hero-enter-1, .hero-enter-2, .hero-enter-3, .hero-enter-4 { animation: none !important; }
    .hover-lift:hover, .img-zoom:hover img, .gallery-item:hover img { transform: none !important; }
}
</style>

<script>
(function () {
    'use strict';

    var DELAY_STEP = 90; // ms between staggered children

    function revealEl(el) {
        el.classList.add('anim-in');
    }

    function revealStagger(el) {
        Array.from(el.children).forEach(function (child, i) {
            setTimeout(function () {
                child.style.opacity    = '1';
                child.style.transform  = 'none';
            }, i * (parseInt(el.dataset.staggerDelay, 10) || DELAY_STEP));
        });
    }

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var el    = entry.target;
            var delay = parseInt(el.dataset.delay, 10) || 0;
            setTimeout(function () {
                el.hasAttribute('data-stagger') ? revealStagger(el) : revealEl(el);
            }, delay);
            io.unobserve(el);
        });
    }, { rootMargin: '0px 0px -64px 0px', threshold: 0.08 });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-animate], [data-stagger]').forEach(function (el) {
            io.observe(el);
        });
    });
}());
</script>
