/**
 * FaithStack — Immersive 3D Landing  ·  v4 (Alpine-safe + flash-free)
 * Deps (CDN, loaded before this file): gsap, ScrollTrigger, Lenis
 *
 * Key principles:
 *  • gsap.set() stamps all starting positions synchronously before any frame
 *    renders → eliminates the FOUC flash before ScrollTrigger initialises
 *  • autoAlpha (not opacity) on every reveal tween → GSAP manages both
 *    opacity AND visibility:hidden, so invisible elements can't receive
 *    clicks or interfere with Alpine.js x-show state machine
 *  • force3D: true → GPU compositing path (translate3d/matrix3d) on all tweens
 *  • Lenis lerp: 0.05, smoothWheel: true (correct v1.x API)
 *  • ScrollTrigger.config(limitCallbacks, syncInterval) → fewer callback fires
 *  • getBoundingClientRect() cached on mouseenter, never inside mousemove
 *  • boxShadow GSAP tween replaced with CSS @keyframes on .js-crm-glow (no paint)
 *  • How-it-works: single stepTl timeline (removed duplicate forEach triggers)
 *  • overwrite: 'auto' prevents stale tween accumulation on fast scroll reversals
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
    ScrollTrigger.config({ limitCallbacks: true, syncInterval: 40 });

    /* ══════════════════════════════════════════════════════════════════════
       0. STAMP INITIAL STATES — synchronous, before any frame renders
          Prevents the flash where elements appear at their final position
          for one frame before ScrollTrigger's fromTo kicks in.
          Uses autoAlpha so GSAP sets visibility:hidden (not just opacity:0),
          which prevents invisible elements from blocking pointer events.
    ══════════════════════════════════════════════════════════════════════ */
    (function stampInitialStates() {
      // Section inners: depth-zoom starts hidden + pulled down
      qa('section:not([data-cursor-glow])').forEach(section => {
        const inner = q('.max-w-7xl, .max-w-6xl', section);
        if (inner) gsap.set(inner, { y: 35, scale: 0.97, autoAlpha: 0 });
      });

      // Feature cards
      const featureCards = qa('.feature-card');
      if (featureCards.length) {
        gsap.set(featureCards, { y: 45, autoAlpha: 0, rotateX: 8, transformOrigin: '50% 0%' });
      }

      // How-it-works steps: all slide up from below (consistent for a horizontal row)
      qa('#how-it-works .grid > div').forEach(step => {
        gsap.set(step, { y: 40, autoAlpha: 0 });
      });

      // Module cards
      const moduleCrds = qa('.module-card');
      if (moduleCrds.length) {
        gsap.set(moduleCrds, { y: 50, autoAlpha: 0, scale: 0.93, rotateX: 10, transformOrigin: '50% 0%' });
      }

      // Testimonial cards
      const testCards = qa('.testimonial-card');
      if (testCards.length) {
        gsap.set(testCards, { y: 45, autoAlpha: 0, scale: 0.95 });
      }

      // Pricing cards
      const pricingCrds = qa('.pricing-card');
      if (pricingCrds.length) {
        gsap.set(pricingCrds, { y: 50, autoAlpha: 0, scale: 0.94 });
      }
    })();

    /* ══════════════════════════════════════════════════════════════════════
       1. LENIS — buttery smooth scroll, ticked by GSAP's RAF loop
    ══════════════════════════════════════════════════════════════════════ */
    const lenis = new window.Lenis({
      lerp:        0.05,
      smoothWheel: true,
      smoothTouch: false,
      syncTouch:   false,
    });

    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(time => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);

    qa('a[href^="#"]').forEach(link => {
      link.addEventListener('click', e => {
        const target = q(link.getAttribute('href'));
        if (target) { e.preventDefault(); lenis.scrollTo(target, { offset: -80, duration: 1.4 }); }
      });
    });

    /* ══════════════════════════════════════════════════════════════════════
       2. HERO BLOBS — scroll-linked depth parallax
          Uses opacity (not autoAlpha) for partial/intermediate fade values.
    ══════════════════════════════════════════════════════════════════════ */
    const hero = q('[data-cursor-glow]');
    if (hero) {
      qa('.blob', hero).forEach((blob, i) => {
        gsap.to(blob, {
          y:         i % 2 === 0 ? -70 : 55,
          ease:      'none',
          force3D:   true,
          overwrite: 'auto',
          scrollTrigger: {
            trigger: hero,
            start:   'top top',
            end:     'bottom top',
            scrub:   2.5,
            invalidateOnRefresh: true,
          },
        });
      });

      qa('.float-chip, .theme-applied-chip', hero).forEach((chip, i) => {
        gsap.to(chip, {
          y:         -50 - i * 10,
          opacity:   0,           // partial scrub fade — opacity intentional
          ease:      'none',
          force3D:   true,
          overwrite: 'auto',
          scrollTrigger: {
            trigger: hero,
            start:   'top top',
            end:     '60% top',
            scrub:   2,
            invalidateOnRefresh: true,
          },
        });
      });

      const heroGrid = q('.grid', hero);
      if (heroGrid) {
        gsap.to(heroGrid, {
          y:         -40,
          opacity:   0.25,        // fades to 25%, not fully hidden — opacity intentional
          ease:      'none',
          force3D:   true,
          overwrite: 'auto',
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

          const qRx = gsap.quickTo(mockup, 'rotateX', { duration: 0.8, ease: 'power2.out', force3D: true });
          const qRy = gsap.quickTo(mockup, 'rotateY', { duration: 0.8, ease: 'power2.out', force3D: true });

          let heroRect = null;
          hero.addEventListener('mouseenter', () => {
            heroRect = hero.getBoundingClientRect();
          }, { passive: true });

          hero.addEventListener('mousemove', e => {
            if (!heroRect) return;
            const dx = (e.clientX - (heroRect.left + heroRect.width  / 2)) / (heroRect.width  / 2);
            const dy = (e.clientY - (heroRect.top  + heroRect.height / 2)) / (heroRect.height / 2);
            qRy( dx * 7);
            qRx(-dy * 4);
          }, { passive: true });

          hero.addEventListener('mouseleave', () => {
            heroRect = null;
            gsap.to(mockup, {
              rotateX: 0, rotateY: 0, duration: 1.0, ease: 'power3.out', force3D: true,
              onComplete: () => gsap.set(mockup, { clearProps: 'rotateX,rotateY' }),
            });
          });
        }
      }
    }

    /* ══════════════════════════════════════════════════════════════════════
       3. SECTION DEPTH-ZOOM ENTRANCE — scroll-linked, bidirectional
          autoAlpha: visibility:hidden on hidden state → no stray clicks
    ══════════════════════════════════════════════════════════════════════ */
    qa('section:not([data-cursor-glow])').forEach(section => {
      const inner = q('.max-w-7xl, .max-w-6xl', section);
      if (!inner) return;

      gsap.fromTo(inner,
        { y: 35, scale: 0.97, autoAlpha: 0 },
        {
          y: 0, scale: 1, autoAlpha: 1,
          ease:      'power1.inOut',
          force3D:   true,
          overwrite: 'auto',
          scrollTrigger: {
            trigger: section,
            start:   'top 90%',
            end:     'top 35%',
            scrub:   2,
            invalidateOnRefresh: true,
          },
        }
      );
    });

    /* ══════════════════════════════════════════════════════════════════════
       4. Z-TUNNEL — outgoing sections subtly scale back
          opacity (not autoAlpha) — fades to 0.88, never fully hidden
    ══════════════════════════════════════════════════════════════════════ */
    if (!TOUCH) {
      qa('section').forEach(section => {
        gsap.to(section, {
          scale:     0.985,
          opacity:   0.88,
          ease:      'none',
          force3D:   true,
          overwrite: 'auto',
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
    ══════════════════════════════════════════════════════════════════════ */
    const featureCards = qa('.feature-card');
    if (featureCards.length) {
      gsap.fromTo(featureCards,
        { y: 45, autoAlpha: 0, rotateX: 8, transformOrigin: '50% 0%' },
        {
          y: 0, autoAlpha: 1, rotateX: 0,
          ease:      'power1.inOut',
          force3D:   true,
          overwrite: 'auto',
          stagger:   { amount: 0.45, from: 'start' },
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
       6. HOW-IT-WORKS STEPS — all slide up together, tight stagger
          All three steps use y (not alternating x) so they cascade cleanly
          left → right. Tighter end point means 03 isn't delayed waiting
          for you to scroll deep past the section.
    ══════════════════════════════════════════════════════════════════════ */
    const steps = qa('#how-it-works .grid > div');
    if (steps.length) {
      const stepTl = gsap.timeline({
        scrollTrigger: {
          trigger: '#how-it-works .grid',
          start:   'top 85%',
          end:     'top 45%',   // shorter window → all 3 steps finish sooner
          scrub:   1.5,
          invalidateOnRefresh: true,
        },
      });
      steps.forEach((step, i) => {
        stepTl.fromTo(step,
          { y: 40, autoAlpha: 0 },
          { y: 0, autoAlpha: 1, ease: 'power2.out', duration: 0.4, force3D: true },
          i * 0.1   // tight 0.1 offset → 01, 02, 03 appear close together
        );
      });
    }

    /* ══════════════════════════════════════════════════════════════════════
       7. MODULE CARDS — 3D stagger, scroll-linked timeline
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
          { y: 50, autoAlpha: 0, scale: 0.93, rotateX: 10, transformOrigin: '50% 0%' },
          { y: 0, autoAlpha: 1, scale: 1, rotateX: 0, ease: 'power1.inOut', duration: 0.4, force3D: true },
          i * 0.09
        );
      });

      // CRM glow: CSS @keyframes on the .js-crm-glow div (opacity-only = no paint)
      const crmGlowEl = q('.js-crm-glow');
      if (crmGlowEl) {
        crmGlowEl.style.animation = 'crmGlowPulse 2.4s ease-in-out infinite';
      }
    }

    /* ══════════════════════════════════════════════════════════════════════
       8. MODULES SECTION BACKGROUND BLOBS — deep parallax
    ══════════════════════════════════════════════════════════════════════ */
    const moduleSec = q('#modules');
    if (moduleSec) {
      qa('.blob', moduleSec).forEach((blob, i) => {
        gsap.to(blob, {
          y:         i % 2 === 0 ? -65 : -45,
          ease:      'none',
          force3D:   true,
          overwrite: 'auto',
          scrollTrigger: {
            trigger: moduleSec,
            start:   'top bottom',
            end:     'bottom top',
            scrub:   3,
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
          { y: 45, autoAlpha: 0, scale: 0.95 },
          { y: 0, autoAlpha: 1, scale: 1, ease: 'power1.inOut', duration: 0.45, force3D: true },
          i * 0.15
        );
      });
    }

    /* ══════════════════════════════════════════════════════════════════════
       10. PRICING CARDS — scroll-linked center-fan-in
    ══════════════════════════════════════════════════════════════════════ */
    const pricingCrds = qa('.pricing-card');
    if (pricingCrds.length) {
      const mid     = Math.floor(pricingCrds.length / 2);
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
          { y: 50, autoAlpha: 0, scale: 0.94 },
          { y: 0, autoAlpha: 1, scale: 1, ease: 'power1.inOut', duration: 0.5, force3D: true },
          Math.abs(i - mid) * 0.1
        );
      });
    }

    /* ══════════════════════════════════════════════════════════════════════
       11. SECTION HEADINGS — word-by-word reveal, bidirectional
           Word spans are created here so we gsap.set() them immediately
           after DOM insertion to avoid a flash before ScrollTrigger fires.
    ══════════════════════════════════════════════════════════════════════ */
    qa('section:not([data-cursor-glow]) h2.reveal').forEach(heading => {
      const fragment = document.createDocumentFragment();
      heading.childNodes.forEach(node => {
        if (node.nodeType === Node.TEXT_NODE) {
          node.textContent.split(/(\s+)/).forEach(chunk => {
            if (/^\s+$/.test(chunk)) {
              fragment.appendChild(document.createTextNode(chunk));
            } else if (chunk) {
              const outer = document.createElement('span');
              const inner = document.createElement('span');
              outer.className  = 'gs-word';
              outer.style.cssText = 'display:inline-block;overflow:hidden;vertical-align:bottom';
              inner.className  = 'gs-word-inner';
              inner.style.display = 'inline-block';
              inner.textContent = chunk;
              outer.appendChild(inner);
              fragment.appendChild(outer);
            }
          });
        } else {
          fragment.appendChild(node.cloneNode(true));
        }
      });
      heading.innerHTML = '';
      heading.appendChild(fragment);

      const wordInners = heading.querySelectorAll('.gs-word-inner');

      // Stamp initial state immediately after DOM insertion
      gsap.set(wordInners, { y: '105%', autoAlpha: 0 });

      gsap.fromTo(wordInners,
        { y: '105%', autoAlpha: 0 },
        {
          y:        '0%',
          autoAlpha: 1,
          duration: 0.75,
          ease:     'power3.out',
          force3D:  true,
          stagger:  0.055,
          scrollTrigger: {
            trigger: heading,
            start:   'top 88%',
            toggleActions: 'play none none reverse',
            invalidateOnRefresh: true,
          },
        }
      );
    });

    /* ══════════════════════════════════════════════════════════════════════
       12. SILKY 3D CARD TILT — feature + module cards
           getBoundingClientRect cached on mouseenter, never in mousemove
    ══════════════════════════════════════════════════════════════════════ */
    if (!TOUCH) {
      qa('.feature-card, .module-card').forEach(card => {
        const qX = gsap.quickTo(card, 'rotateX', { duration: 0.6, ease: 'power2.out', force3D: true });
        const qY = gsap.quickTo(card, 'rotateY', { duration: 0.6, ease: 'power2.out', force3D: true });

        let cardRect = null;

        card.addEventListener('mouseenter', () => {
          cardRect = card.getBoundingClientRect();
        }, { passive: true });

        card.addEventListener('mousemove', e => {
          if (!cardRect) return;
          const dx = (e.clientX - (cardRect.left + cardRect.width  / 2)) / (cardRect.width  / 2);
          const dy = (e.clientY - (cardRect.top  + cardRect.height / 2)) / (cardRect.height / 2);
          qY( dx * 5);
          qX(-dy * 3.5);
        }, { passive: true });

        card.addEventListener('mouseleave', () => {
          cardRect = null;
          gsap.to(card, {
            rotateX: 0, rotateY: 0,
            duration:  0.8,
            ease:      'power3.out',
            force3D:   true,
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

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
