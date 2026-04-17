<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FaithStack is a multi-tenant CMS built for churches, nonprofits, and community organizations. 80+ themes, donation management, custom branding — launch in minutes.">
    <title>FaithStack — Beautiful Websites for Your Organization</title>

    {{-- Open Graph --}}
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
        /* Smooth scroll offset for fixed nav */
        html { scroll-padding-top: 80px; }

        /* Fade-in animation for sections */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up {
            opacity: 0;
            animation: fadeUp 0.6s ease forwards;
        }
        .fade-up-delay-1 { animation-delay: 0.1s; }
        .fade-up-delay-2 { animation-delay: 0.2s; }
        .fade-up-delay-3 { animation-delay: 0.3s; }

        /* Intersection observer driven animations */
        [data-reveal] {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        [data-reveal].revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-white antialiased" x-data>

    {{-- Sticky navigation --}}
    <x-landing.nav />

    {{-- Hero --}}
    <x-landing.hero />

    {{-- Features --}}
    <x-landing.features :features="$features" />

    {{-- Themes preview --}}
    <x-landing.themes :themes="$themes" />

    {{-- How it works --}}
    <x-landing.how-it-works :steps="$steps" />

    {{-- Pricing --}}
    <x-landing.pricing :plans="$plans" />

    {{-- Testimonials --}}
    <x-landing.testimonials :testimonials="$testimonials" />

    {{-- Final CTA --}}
    <x-landing.final-cta />

    {{-- Footer --}}
    <x-landing.footer />

    <script>
        // Scroll reveal for sections
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('revealed');
                    }, i * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        document.querySelectorAll('[data-reveal]').forEach(el => observer.observe(el));
    </script>

</body>
</html>
