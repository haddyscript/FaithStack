/**
 * FaithStack — Immersive 3D Landing  ·  v2 (smooth rewrite)
 * Deps (CDN, loaded before this file): gsap, ScrollTrigger, Lenis
 *
 * Smoothness principles applied throughout:
 *  • Parallax → scrub 2–3  (slow follow = floating, not twitchy)
 *  • Card reveals → scrub-based fromTo (bidirectional: reverses on scroll-up)
 *  • Rotation/scale extremes → halved so cards glide in, not pop
 *  • Hero pin → removed (was the #1 jank source)
 *  • Z-tunnel → very subtle (scale 0.985, scrub 2.5) so it reads as depth, not glitch
 *  • GSAP quickTo durations increased so mouse tilt feels silky
 *  • Alpine.js is untouched; GSAP inline styles win over CSS classes naturally
 */

(function () {
  'use strict';

  const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const TOUCH   = window.matchMedia('(pointer: coarse)').matches;

  if (REDUCED) return;

  const q  = (sel, ctx = document) => ctx.querySelector(sel);
  const qa = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

  /* ─────────────────────────────────────────────────────────────────────────
     Init — waits for GSAP, ScrollTrigger, and Lenis to be defined
  ───────────────────────────────────────────────────────────────────────── */
  function init() {
    if (typeof gsap === 'undefined' ||
        typeof ScrollTrigger === 'undefined' ||
        typeof window.Lenis === 'undefined') {
      requestAnimationFrame(init);
      return;
    }

    gsap.registerPlugin(ScrollTrigger);

    /* ══════════════════════════════════════════════════════════════════════
       1. LENIS — buttery smooth scroll, ticked by GSAP's RAF loop
    ══════════════════════════════════════════════════════════════════════ */
    const lenis = new window.Lenis({
      duration:    1.4,                            // slightly longer = more cushion
      easing:      t => 1 - Math.pow(1 - t, 4),   // quartic ease-out: fast start, long tail
      orientation: 'vertical',
      smoothTouch: false,
      syncTouch:   false,
    });

    // Keep ScrollTrigger in sync with Lenis's virtual scroll position
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(time => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);   // prevent GSAP from skipping frames

    // Wire anchor links to Lenis
    qa('a[href^="#"]').forEach(link => {
      link.addEventListener('click', e => {
        const target = q(link.getAttribute('href'));
        if (target) { e.preventDefault(); lenis.scrollTo(target, { offset: -80, duration: 1.4 }); }
      });
    });

    /* ══════════════════════════════════════════════════════════════════════
       2. HERO BLOBS — scroll-linked depth parallax
          scrub: 2.5 = slow, floaty follow that reads as different Z-layers
    ══════════════════════════════════════════════════════════════════════ */
    const hero = q('[data-cursor-glow]');
    if (hero) {
      qa('.blob', hero).forEach((blob, i) => {
        gsap.to(blob, {
          y:    i % 2 === 0 ? -70 : 55,   // reduced from ±90/70 → less jerky
          ease: 'none',
          scrollTrigger: {
            trigger: hero,
            start:   'top top',
            end:     'bottom top',
            scrub:   2.5,                 // higher scrub = smoother lag
            invalidateOnRefresh: true,
          },
        });
      });

      // Floating UI chips — drift and fade as hero exits
      qa('.float-chip, .theme-applied-chip', hero).forEach((chip, i) => {
        gsap.to(chip, {
          y:       -50 - i * 10,          // gentler than before
          opacity: 0,
          ease:    'none',
          scrollTrigger: {
            trigger: hero,
            start:   'top top',
            end:     '60% top',
            scrub:   2,
            invalidateOnRefresh: true,
          },
        });
      });

      // Hero grid content — parallax drift upward on scroll-out
      const heroGrid = q('.grid', hero);
      if (heroGrid) {
        gsap.to(heroGrid, {
          y:       -40,
          opacity: 0.25,
          ease:    'none',
          scrollTrigger: {
            trigger: hero,
            start:   '50% top',
            end:     'bottom top',
            scrub:   2,
            invalidateOnRefresh: true,
          },
        });
      }

      /* ── Hero mockup 3D mouse-tilt (desktop only) ────────────────────── */
      if (!TOUCH) {
        const mockup = q('.mockup-float', hero);
        if (mockup) {
          mockup.style.transformStyle = 'preserve-3d';

          // quickTo duration 0.8 = very silky, longer than before (0.55)
          const qRx = gsap.quickTo(mockup, 'rotateX', { duration: 0.8, ease: 'power2.out' });
          const qRy = gsap.quickTo(mockup, 'rotateY', { duration: 0.8, ease: 'power2.out' });

          hero.addEventListener('mousemove', e => {
            const r  = hero.getBoundingClientRect();
            const dx = (e.clientX - (r.left + r.width  / 2)) / (r.width  / 2);
            const dy = (e.clientY - (r.top  + r.height / 2)) / (r.height / 2);
            qRy( dx * 7);   // 9→7: gentler tilt
            qRx(-dy * 4);   // 5→4
          }, { passive: true });

          hero.addEventListener('mouseleave', () => {
            gsap.to(mockup, {
              rotateX: 0, rotateY: 0, duration: 1.0, ease: 'power3.out',
              onComplete: () => gsap.set(mockup, { clearProps: 'rotateX,rotateY' }),
            });
          });
        }
      }
    }

    /* ══════════════════════════════════════════════════════════════════════
       3. SECTION DEPTH-ZOOM ENTRANCE — scroll-linked (bidirectional)
          Using scrub means it reverses perfectly when scrolling up.
          scale 0.97→1 reads as "camera zooming in" without popping.
    ══════════════════════════════════════════════════════════════════════ */
    qa('section:not([data-cursor-glow])').forEach(section => {
      const inner = q('.max-w-7xl, .max-w-6xl', section);
      if (!inner) return;

      gsap.fromTo(inner,
        { y: 35, scale: 0.97, opacity: 0 },
        {
          y: 0, scale: 1, opacity: 1,
          ease: 'power1.inOut',            // symmetric easing looks smooth both ways
          scrollTrigger: {
            trigger: section,
            start:   'top 90%',
            end:     'top 35%',
            scrub:   2,                    // scroll-linked: reverses on scroll-up
            invalidateOnRefresh: true,
          },
        }
      );
    });

    /* ══════════════════════════════════════════════════════════════════════
       4. Z-TUNNEL — outgoing sections subtly scale back as next enters
          Very subtle (scale 0.985, opacity 0.9) so it reads as depth not glitch.
          High scrub (2.5) = gradual, never jarring.
    ══════════════════════════════════════════════════════════════════════ */
    if (!TOUCH) {
      qa('section').forEach(section => {
        gsap.to(section, {
          scale:   0.985,   // 0.97→0.985: much subtler
          opacity: 0.88,    // 0.7→0.88: barely perceptible fade
          ease:    'none',
          scrollTrigger: {
            trigger: section,
            start:   'bottom 35%',
            end:     'bottom top',
            scrub:   2.5,
            invalidateOnRefresh: true,
          },
        });
      });
    }

    /* ══════════════════════════════════════════════════════════════════════
       5. FEATURE CARDS — scroll-linked 3D stagger reveal
          scrub + stagger = each card is at a different animation progress
          as you scroll. Scrolling up reverses the whole sequence naturally.
    ══════════════════════════════════════════════════════════════════════ */
    const featureCards = qa('.feature-card');
    if (featureCards.length) {
      gsap.fromTo(featureCards,
        {
          y:               45,
          opacity:         0,
          rotateX:         8,             // 14→8: glide not pop
          transformOrigin: '50% 0%',
        },
        {
          y:       0,
          opacity: 1,
          rotateX: 0,
          ease:    'power1.inOut',
          stagger: { amount: 0.45, from: 'start' },
          scrollTrigger: {
            trigger: '#features .grid',
            start:   'top 88%',
            end:     'top 20%',
            scrub:   1.8,
            invalidateOnRefresh: true,
          },
        }
      );
    }

    /* ══════════════════════════════════════════════════════════════════════
       6. HOW-IT-WORKS STEPS — alternating side slide, scroll-linked
    ══════════════════════════════════════════════════════════════════════ */
    const steps = qa('#how-it-works .grid > div');
    if (steps.length) {
      steps.forEach((step, i) => {
        const xFrom = i % 2 === 0 ? -40 : 40;   // 50→40: less extreme
        const ryFrom = i % 2 === 0 ? -6 : 6;    // 10→6

        gsap.fromTo(step,
          { x: xFrom, opacity: 0, rotateY: ryFrom },
          {
            x: 0, opacity: 1, rotateY: 0,
            ease: 'power1.inOut',
            scrollTrigger: {
              trigger: '#how-it-works .grid',
              start:   'top 88%',
              end:     'top 20%',
              scrub:   1.8,
              invalidateOnRefresh: true,
            },
            // Offset each step's scrub window so they stagger during scroll
            delay: 0,   // delay doesn't work with scrub; we use timeline below
          }
        );
      });

      // Override: use a timeline for proper stagger + scrub
      // (GSAP stagger inside scrub works best on a timeline)
      const stepTl = gsap.timeline({
        scrollTrigger: {
          trigger: '#how-it-works .grid',
          start:   'top 88%',
          end:     'top 20%',
          scrub:   2,
          invalidateOnRefresh: true,
        },
      });
      steps.forEach((step, i) => {
        const xFrom  = i % 2 === 0 ? -40 : 40;
        const ryFrom = i % 2 === 0 ? -6  : 6;
        stepTl.fromTo(step,
          { x: xFrom, opacity: 0, rotateY: ryFrom },
          { x: 0, opacity: 1, rotateY: 0, ease: 'power1.inOut', duration: 0.5 },
          i * 0.18   // stagger offset within the timeline
        );
      });
    }

    /* ══════════════════════════════════════════════════════════════════════
       7. MODULE CARDS — 3D stagger, scroll-linked
          Timeline-based so stagger + scrub work correctly together.
    ══════════════════════════════════════════════════════════════════════ */
    const moduleCrds = qa('.module-card');
    if (moduleCrds.length) {
      const modTl = gsap.timeline({
        scrollTrigger: {
          trigger: '#modules .grid',
          start:   'top 90%',
          end:     'top 15%',
          scrub:   2,
          invalidateOnRefresh: true,
        },
      });

      moduleCrds.forEach((card, i) => {
        modTl.fromTo(card,
          {
            y:               50,
            opacity:         0,
            scale:           0.93,   // 0.88→0.93: much gentler
            rotateX:         10,     // 18→10
            transformOrigin: '50% 0%',
          },
          {
            y: 0, opacity: 1, scale: 1, rotateX: 0,
            ease:     'power1.inOut',
            duration: 0.4,
          },
          i * 0.09   // tighter stagger: 10 cards, smooth cascade
        );
      });

      // CRM highlighted card: continuous soft glow pulse (CSS handles most of it,
      // GSAP adds a slow breathing boxShadow on top)
      const crmCard = moduleCrds.find(el =>
        el.style.background && el.style.background.includes('rgba(79,70,229')
      );
      if (crmCard) {
        gsap.to(crmCard, {
          boxShadow: '0 0 55px rgba(99,102,241,0.28), inset 0 0 0 1px rgba(129,140,248,0.55)',
          duration:  2.2,    // slower = more serene
          repeat:    -1,
          yoyo:      true,
          ease:      'sine.inOut',
        });
      }
    }

    /* ══════════════════════════════════════════════════════════════════════
       8. MODULES SECTION BACKGROUND BLOBS — deep parallax
    ══════════════════════════════════════════════════════════════════════ */
    const moduleSec = q('#modules');
    if (moduleSec) {
      qa('.blob', moduleSec).forEach((blob, i) => {
        gsap.to(blob, {
          y:    i % 2 === 0 ? -65 : -45,
          ease: 'none',
          scrollTrigger: {
            trigger: moduleSec,
            start:   'top bottom',
            end:     'bottom top',
            scrub:   3,     // deepest scrub = most floaty
            invalidateOnRefresh: true,
          },
        });
      });
    }

    /* ══════════════════════════════════════════════════════════════════════
       9. TESTIMONIAL CARDS — scroll-linked fan-in
    ══════════════════════════════════════════════════════════════════════ */
    const testCards = qa('.testimonial-card');
    if (testCards.length) {
      const testTl = gsap.timeline({
        scrollTrigger: {
          trigger: '#testimonials',
          start:   'top 88%',
          end:     'top 25%',
          scrub:   2,
          invalidateOnRefresh: true,
        },
      });

      testCards.forEach((card, i) => {
        testTl.fromTo(card,
          { y: 45, opacity: 0, scale: 0.95 },
          { y: 0, opacity: 1, scale: 1, ease: 'power1.inOut', duration: 0.45 },
          i * 0.15
        );
      });
    }

    /* ══════════════════════════════════════════════════════════════════════
       10. PRICING CARDS — scroll-linked center-fan-in
    ══════════════════════════════════════════════════════════════════════ */
    const pricingCrds = qa('.pricing-card');
    if (pricingCrds.length) {
      const mid    = Math.floor(pricingCrds.length / 2);
      const priceTl = gsap.timeline({
        scrollTrigger: {
          trigger: '#pricing',
          start:   'top 88%',
          end:     'top 25%',
          scrub:   2,
          invalidateOnRefresh: true,
        },
      });

      pricingCrds.forEach((card, i) => {
        priceTl.fromTo(card,
          { y: 50, opacity: 0, scale: 0.94 },
          { y: 0, opacity: 1, scale: 1, ease: 'power1.inOut', duration: 0.5 },
          Math.abs(i - mid) * 0.1   // fan in from center outward
        );
      });
    }

    /* ══════════════════════════════════════════════════════════════════════
       11. SECTION HEADINGS — word-by-word reveal, bidirectional
           Uses toggleActions 'play reverse play reverse' so scrolling
           up reverses the word cascade cleanly.
    ══════════════════════════════════════════════════════════════════════ */
    qa('section:not([data-cursor-glow]) h2.reveal').forEach(heading => {
      // Preserve <br> nodes while splitting text into word spans
      const fragment = document.createDocumentFragment();
      heading.childNodes.forEach(node => {
        if (node.nodeType === Node.TEXT_NODE) {
          node.textContent.split(/(\s+)/).forEach(chunk => {
            if (/^\s+$/.test(chunk)) {
              fragment.appendChild(document.createTextNode(chunk));
            } else if (chunk) {
              const outer  = document.createElement('span');
              const inner  = document.createElement('span');
              outer.className = 'gs-word';
              outer.style.cssText = 'display:inline-block;overflow:hidden;vertical-align:bottom';
              inner.className = 'gs-word-inner';
              inner.style.display = 'inline-block';
              inner.textContent = chunk;
              outer.appendChild(inner);
              fragment.appendChild(outer);
            }
          });
        } else {
          // Keep <br> and other elements intact
          fragment.appendChild(node.cloneNode(true));
        }
      });
      heading.innerHTML = '';
      heading.appendChild(fragment);

      const wordInners = heading.querySelectorAll('.gs-word-inner');
      gsap.fromTo(wordInners,
        { y: '105%', opacity: 0 },
        {
          y:        '0%',
          opacity:  1,
          duration: 0.75,
          ease:     'power3.out',
          stagger:  0.055,      // 0.065→0.055: slightly faster
          scrollTrigger: {
            trigger: heading,
            start:   'top 88%',
            toggleActions: 'play none none reverse',  // reverses on scroll-up
            invalidateOnRefresh: true,
          },
        }
      );
    });

    /* ══════════════════════════════════════════════════════════════════════
       12. SILKY 3D CARD TILT — feature + module cards
           quickTo duration 0.6 (vs 0.4 before) = more inertia = silkier
    ══════════════════════════════════════════════════════════════════════ */
    if (!TOUCH) {
      qa('.feature-card, .module-card').forEach(card => {
        const qX = gsap.quickTo(card, 'rotateX', { duration: 0.6, ease: 'power2.out' });
        const qY = gsap.quickTo(card, 'rotateY', { duration: 0.6, ease: 'power2.out' });

        card.addEventListener('mousemove', e => {
          const r  = card.getBoundingClientRect();
          const dx = (e.clientX - (r.left + r.width  / 2)) / (r.width  / 2);
          const dy = (e.clientY - (r.top  + r.height / 2)) / (r.height / 2);
          qY( dx * 5);   // 6→5
          qX(-dy * 3.5); // 4→3.5
        }, { passive: true });

        card.addEventListener('mouseleave', () => {
          gsap.to(card, {
            rotateX: 0, rotateY: 0,
            duration: 0.8,       // 0.5→0.8: longer spring-back
            ease:     'power3.out',
            onComplete: () => gsap.set(card, { clearProps: 'rotateX,rotateY' }),
          });
        });
      });
    }

    /* ══════════════════════════════════════════════════════════════════════
       13. REFRESH on load  (images/fonts may shift layout)
    ══════════════════════════════════════════════════════════════════════ */
    window.addEventListener('load', () => ScrollTrigger.refresh(), { once: true });

  } // end init()

  /* ── Boot ──────────────────────────────────────────────────────────────── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
