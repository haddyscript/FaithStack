<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index(): View
    {
        $features = [
            [
                'icon'        => 'cube',
                'title'       => 'Drag & Drop Builder',
                'description' => 'Build pages visually with our intuitive section-based editor. No coding required.',
                'color'       => 'indigo',
            ],
            [
                'icon'        => 'swatch',
                'title'       => '80+ Premium Themes',
                'description' => 'Choose from a curated library of themes across every industry and style.',
                'color'       => 'purple',
            ],
            [
                'icon'        => 'users',
                'title'       => 'Multi-Site Platform',
                'description' => 'Manage multiple organizations from one account with isolated tenant environments.',
                'color'       => 'blue',
            ],
            [
                'icon'        => 'currency-dollar',
                'title'       => 'Donation Management',
                'description' => 'Accept donations online with built-in payment forms and reporting dashboards.',
                'color'       => 'emerald',
            ],
            [
                'icon'        => 'paint-brush',
                'title'       => 'Custom Branding',
                'description' => 'Apply your own colors, fonts, logo, and navigation CTA to match your brand.',
                'color'       => 'rose',
            ],
            [
                'icon'        => 'device-phone-mobile',
                'title'       => 'Mobile-First Design',
                'description' => 'Every theme is fully responsive. Your site looks great on any device.',
                'color'       => 'amber',
            ],
        ];

        $themes = [
            [
                'name'     => 'Church & Ministry',
                'slug'     => 'church',
                'count'    => 3,
                'gradient' => 'from-violet-600 to-indigo-600',
                'icon'     => '⛪',
                'tags'     => ['Modern Clean', 'Church Classic', 'Contemporary'],
            ],
            [
                'name'     => 'SaaS & Technology',
                'slug'     => 'saas',
                'count'    => 7,
                'gradient' => 'from-blue-600 to-cyan-500',
                'icon'     => '🚀',
                'tags'     => ['Apex SaaS', 'Cloud Deck', 'Orbit Platform'],
            ],
            [
                'name'     => 'Online Store',
                'slug'     => 'ecommerce',
                'count'    => 7,
                'gradient' => 'from-emerald-500 to-teal-600',
                'icon'     => '🛒',
                'tags'     => ['Shop Elite', 'Luxe Cart', 'Nova Buy'],
            ],
            [
                'name'     => 'Creative Portfolio',
                'slug'     => 'portfolio',
                'count'    => 6,
                'gradient' => 'from-slate-700 to-slate-900',
                'icon'     => '🎨',
                'tags'     => ['Folio Noir', 'Luminary', 'Canvas Pro'],
            ],
            [
                'name'     => 'Creative Agency',
                'slug'     => 'agency',
                'count'    => 6,
                'gradient' => 'from-orange-500 to-rose-600',
                'icon'     => '⚡',
                'tags'     => ['Boldline', 'Stratosphere', 'Nexus Studio'],
            ],
            [
                'name'     => 'Restaurant & Food',
                'slug'     => 'restaurant',
                'count'    => 4,
                'gradient' => 'from-red-600 to-pink-600',
                'icon'     => '🍽️',
                'tags'     => ['Bistro Noir', 'Harvest Table', 'Savor'],
            ],
        ];

        $steps = [
            [
                'number'      => '01',
                'title'       => 'Sign Up for Free',
                'description' => 'Create your FaithStack account in seconds. No credit card required to get started.',
                'icon'        => 'user-plus',
            ],
            [
                'number'      => '02',
                'title'       => 'Choose Your Theme',
                'description' => 'Browse 80+ themes designed for your industry. Preview, pick, and apply instantly.',
                'icon'        => 'swatch',
            ],
            [
                'number'      => '03',
                'title'       => 'Launch & Grow',
                'description' => 'Publish your site, collect donations, and grow your community — all in one place.',
                'icon'        => 'rocket-launch',
            ],
        ];

        $plans = [
            [
                'name'        => 'Starter',
                'price'       => 0,
                'period'      => 'forever',
                'description' => 'Perfect for getting started.',
                'featured'    => false,
                'cta'         => 'Get Started Free',
                'features'    => [
                    '1 website',
                    'Up to 5 pages',
                    '10 basic themes',
                    'FaithStack subdomain',
                    'Donation forms',
                    'Email support',
                ],
                'missing' => [
                    'Custom domain',
                    'Priority support',
                    'White-label',
                ],
            ],
            [
                'name'        => 'Pro',
                'price'       => 29,
                'period'      => 'per month',
                'description' => 'Everything you need to grow.',
                'featured'    => true,
                'cta'         => 'Start Free Trial',
                'features'    => [
                    '1 website',
                    'Unlimited pages',
                    'All 80+ themes',
                    'Custom domain',
                    'Advanced branding',
                    'Donation analytics',
                    'Priority support',
                ],
                'missing' => [
                    'Multiple sites',
                    'White-label',
                ],
            ],
            [
                'name'        => 'Business',
                'price'       => 79,
                'period'      => 'per month',
                'description' => 'For organizations at scale.',
                'featured'    => false,
                'cta'         => 'Contact Sales',
                'features'    => [
                    'Up to 10 websites',
                    'Unlimited pages',
                    'All 80+ themes',
                    'Custom domains',
                    'White-label platform',
                    'Advanced analytics',
                    'Dedicated support',
                    'SLA guarantee',
                ],
                'missing' => [],
            ],
        ];

        $testimonials = [
            [
                'quote'        => 'FaithStack transformed how we connect with our congregation online. Setup took minutes, not weeks.',
                'author'       => 'Pastor James Okafor',
                'role'         => 'Senior Pastor, Grace Community Church',
                'avatar_color' => 'bg-violet-600',
                'initials'     => 'JO',
            ],
            [
                'quote'        => 'We switched from WordPress and never looked back. The donation management alone was worth it.',
                'author'       => 'Sarah Mitchell',
                'role'         => 'Executive Director, Hope Foundation',
                'avatar_color' => 'bg-blue-600',
                'initials'     => 'SM',
            ],
            [
                'quote'        => 'Our team with zero technical skills built a stunning site in an afternoon. Incredible product.',
                'author'       => 'Rev. David Chen',
                'role'         => 'Lead Pastor, Cornerstone Ministry',
                'avatar_color' => 'bg-emerald-600',
                'initials'     => 'DC',
            ],
        ];

        return view('landing', compact('features', 'themes', 'steps', 'plans', 'testimonials'));
    }
}
