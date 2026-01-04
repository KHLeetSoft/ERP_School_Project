<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentGatewaySetting;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first school and admin user
        $school = School::first();
        $admin = User::where('role', 'admin')->first();
        
        if (!$school || !$admin) {
            $this->command->warn('No school or admin found. Please run SchoolSeeder and AdminUserSeeder first.');
            return;
        }

        // Clear existing payment gateway settings for this school
        PaymentGatewaySetting::where('school_id', $school->id)->delete();

        $paymentGateways = [
            [
                'school_id' => $school->id,
                'gateway_name' => 'razorpay',
                'display_name' => 'Razorpay',
                'is_active' => true,
                'is_test_mode' => true,
                'api_credentials' => [
                    'key_id' => 'rzp_test_' . Str::random(14),
                    'key_secret' => Str::random(32),
                    'webhook_secret' => Str::random(32)
                ],
                'supported_payment_methods' => ['card', 'upi', 'netbanking', 'wallet'],
                'transaction_fee_percentage' => 2.00,
                'minimum_amount' => 1.00,
                'maximum_amount' => 100000.00,
                'webhook_url' => url('/api/payment/razorpay/webhook'),
                'return_url' => url('/payment/success'),
                'cancel_url' => url('/payment/cancel'),
                'additional_settings' => [
                    'theme_color' => '#3395ff',
                    'currency' => 'INR',
                    'timeout' => 900
                ],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'gateway_name' => 'paytm',
                'display_name' => 'Paytm',
                'is_active' => true,
                'is_test_mode' => true,
                'api_credentials' => [
                    'merchant_id' => 'TEST_MERCHANT_' . Str::random(10),
                    'merchant_key' => Str::random(32),
                    'website' => 'WEBSTAGING',
                    'industry_type' => 'Education'
                ],
                'supported_payment_methods' => ['card', 'upi', 'netbanking', 'wallet'],
                'transaction_fee_percentage' => 1.50,
                'minimum_amount' => 1.00,
                'maximum_amount' => 50000.00,
                'webhook_url' => url('/api/payment/paytm/webhook'),
                'return_url' => url('/payment/success'),
                'cancel_url' => url('/payment/cancel'),
                'additional_settings' => [
                    'theme_color' => '#00baf2',
                    'currency' => 'INR',
                    'timeout' => 600
                ],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'gateway_name' => 'stripe',
                'display_name' => 'Stripe',
                'is_active' => false,
                'is_test_mode' => true,
                'api_credentials' => [
                    'publishable_key' => 'pk_test_' . Str::random(24),
                    'secret_key' => 'sk_test_' . Str::random(24),
                    'webhook_secret' => 'whsec_' . Str::random(32)
                ],
                'supported_payment_methods' => ['card', 'upi'],
                'transaction_fee_percentage' => 2.90,
                'minimum_amount' => 0.50,
                'maximum_amount' => 999999.99,
                'webhook_url' => url('/api/payment/stripe/webhook'),
                'return_url' => url('/payment/success'),
                'cancel_url' => url('/payment/cancel'),
                'additional_settings' => [
                    'theme_color' => '#635bff',
                    'currency' => 'USD',
                    'timeout' => 1200
                ],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'gateway_name' => 'upi',
                'display_name' => 'UPI Payment',
                'is_active' => true,
                'is_test_mode' => false,
                'api_credentials' => [
                    'merchant_id' => 'MERCHANT_' . Str::random(8),
                    'merchant_key' => Str::random(32),
                    'upi_id' => 'school@paytm'
                ],
                'supported_payment_methods' => ['upi'],
                'transaction_fee_percentage' => 0.00,
                'minimum_amount' => 1.00,
                'maximum_amount' => 10000.00,
                'webhook_url' => url('/api/payment/upi/webhook'),
                'return_url' => url('/payment/success'),
                'cancel_url' => url('/payment/cancel'),
                'additional_settings' => [
                    'theme_color' => '#00baf2',
                    'currency' => 'INR',
                    'timeout' => 300
                ],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'gateway_name' => 'cashfree',
                'display_name' => 'Cashfree',
                'is_active' => false,
                'is_test_mode' => true,
                'api_credentials' => [
                    'app_id' => 'TEST_APP_' . Str::random(12),
                    'secret_key' => Str::random(32),
                    'webhook_secret' => Str::random(32)
                ],
                'supported_payment_methods' => ['card', 'upi', 'netbanking', 'wallet'],
                'transaction_fee_percentage' => 1.75,
                'minimum_amount' => 1.00,
                'maximum_amount' => 75000.00,
                'webhook_url' => url('/api/payment/cashfree/webhook'),
                'return_url' => url('/payment/success'),
                'cancel_url' => url('/payment/cancel'),
                'additional_settings' => [
                    'theme_color' => '#ff6b35',
                    'currency' => 'INR',
                    'timeout' => 900
                ],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert payment gateway settings
        foreach ($paymentGateways as $gateway) {
            PaymentGatewaySetting::create($gateway);
        }

        $this->command->info('Payment gateway settings seeded successfully for ' . $school->name);
    }
}
