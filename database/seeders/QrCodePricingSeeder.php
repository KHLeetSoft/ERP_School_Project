<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QrCodePricing;

class QrCodePricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pricingTiers = [
            [
                'name' => 'Starter',
                'description' => 'Perfect for small schools getting started',
                'min_qr_codes' => 1,
                'max_qr_codes' => 10,
                'price_per_qr_code' => 50.00,
                'discount_percentage' => 0,
                'is_active' => true,
                'sort_order' => 1,
                'features' => [
                    'Basic QR code generation',
                    'Standard support',
                    'Email notifications'
                ]
            ],
            [
                'name' => 'Growth',
                'description' => 'Ideal for growing schools with more payment needs',
                'min_qr_codes' => 11,
                'max_qr_codes' => 50,
                'price_per_qr_code' => 45.00,
                'discount_percentage' => 10,
                'is_active' => true,
                'sort_order' => 2,
                'features' => [
                    'Advanced QR code generation',
                    'Priority support',
                    'SMS notifications',
                    'Custom branding'
                ]
            ],
            [
                'name' => 'Professional',
                'description' => 'For established schools with high payment volumes',
                'min_qr_codes' => 51,
                'max_qr_codes' => 200,
                'price_per_qr_code' => 40.00,
                'discount_percentage' => 20,
                'is_active' => true,
                'sort_order' => 3,
                'features' => [
                    'Premium QR code generation',
                    '24/7 support',
                    'Multi-channel notifications',
                    'Advanced analytics',
                    'API access'
                ]
            ],
            [
                'name' => 'Enterprise',
                'description' => 'For large schools with unlimited payment needs',
                'min_qr_codes' => 201,
                'max_qr_codes' => null,
                'price_per_qr_code' => 35.00,
                'discount_percentage' => 30,
                'is_active' => true,
                'sort_order' => 4,
                'features' => [
                    'Unlimited QR code generation',
                    'Dedicated support',
                    'Custom integrations',
                    'Advanced reporting',
                    'White-label options',
                    'Priority feature requests'
                ]
            ]
        ];

        foreach ($pricingTiers as $tier) {
            QrCodePricing::create($tier);
        }
    }
}