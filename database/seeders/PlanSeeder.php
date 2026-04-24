<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'             => 'Free Trial',
                'slug'             => 'free-trial',
                'price_monthly'    => 0,
                'trial_days'       => 7,
                'is_featured'      => false,
                'is_active'        => true,
                'cta_label'        => 'Start Free Trial',
                'badge'            => null,
                'description'      => '7-day trial, no credit card required',
                'features'         => [
                    '1 website',
                    '5 pages',
                    '10 themes',
                    'Subdomain hosting',
                    'Donation forms',
                    'Email support',
                ],
                'missing_features' => [
                    'Custom domain',
                    'Custom branding',
                    'Analytics',
                    'Priority support',
                ],
                'limits'           => [
                    'max_sites'  => 1,
                    'max_pages'  => 5,
                    'max_themes' => 10,
                ],
            ],
            [
                'name'             => 'Basic',
                'slug'             => 'basic',
                'price_monthly'    => 39,
                'trial_days'       => null,
                'is_featured'      => false,
                'is_active'        => true,
                'cta_label'        => 'Get Started',
                'badge'            => null,
                'description'      => 'Everything you need to get your organization online',
                'features'         => [
                    '1 website',
                    'Unlimited pages',
                    '30+ themes',
                    'Custom domain',
                    'Donation forms',
                    'Email support',
                ],
                'missing_features' => [
                    'Custom branding',
                    'Analytics',
                    'Multiple websites',
                    'Priority support',
                ],
                'limits'           => [
                    'max_sites'  => 1,
                    'max_pages'  => null,
                    'max_themes' => 30,
                ],
            ],
            [
                'name'             => 'Pro',
                'slug'             => 'pro',
                'price_monthly'    => 59,
                'trial_days'       => null,
                'is_featured'      => true,
                'is_active'        => true,
                'cta_label'        => 'Get Started',
                'badge'            => 'Most Popular',
                'description'      => 'Everything for growing organizations',
                'features'         => [
                    '1 website',
                    'Unlimited pages',
                    '80+ themes',
                    'Custom domain',
                    'Custom branding',
                    'Analytics',
                    'Priority support',
                ],
                'missing_features' => [
                    'Multiple websites',
                    'White-label',
                    'Dedicated support',
                    'SLA guarantee',
                ],
                'limits'           => [
                    'max_sites'  => 1,
                    'max_pages'  => null,
                    'max_themes' => null,
                ],
            ],
            [
                'name'             => 'Business',
                'slug'             => 'business',
                'price_monthly'    => 109,
                'trial_days'       => null,
                'is_featured'      => false,
                'is_active'        => true,
                'cta_label'        => 'Get Started',
                'badge'            => null,
                'description'      => 'For large organizations and agencies',
                'features'         => [
                    '10 websites',
                    'Unlimited pages',
                    'All 80+ themes',
                    'Custom domains',
                    'White-label',
                    'Dedicated support',
                    'SLA guarantee',
                ],
                'missing_features' => [],
                'limits'           => [
                    'max_sites'  => 10,
                    'max_pages'  => null,
                    'max_themes' => null,
                ],
            ],
        ];

        foreach ($plans as $data) {
            Plan::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
