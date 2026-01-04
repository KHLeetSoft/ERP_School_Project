<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;
use App\Models\PaymentPlan;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PaymentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a superadmin user
        $superadmin = User::where('role_id', 1)->first();
        if (!$superadmin) {
            $superadmin = User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'status' => true
            ]);
        }

        // Create sample payment gateways
        $gateway1 = PaymentGateway::create([
            'name' => 'Razorpay Gateway',
            'provider' => 'razorpay',
            'mode' => 'sandbox',
            'api_key' => encrypt('rzp_test_1234567890'),
            'api_secret' => encrypt('secret_1234567890'),
            'webhook_url' => 'https://example.com/webhook/razorpay',
            'currency' => 'INR',
            'commission_rate' => 2.5,
            'is_active' => true,
            'created_by' => $superadmin->id
        ]);

        $gateway2 = PaymentGateway::create([
            'name' => 'PayU Gateway',
            'provider' => 'payu',
            'mode' => 'sandbox',
            'api_key' => encrypt('payu_test_1234567890'),
            'api_secret' => encrypt('payu_secret_1234567890'),
            'webhook_url' => 'https://example.com/webhook/payu',
            'currency' => 'INR',
            'commission_rate' => 3.0,
            'is_active' => true,
            'created_by' => $superadmin->id
        ]);

        $gateway3 = PaymentGateway::create([
            'name' => 'Stripe Gateway',
            'provider' => 'stripe',
            'mode' => 'sandbox',
            'api_key' => encrypt('sk_test_1234567890'),
            'api_secret' => encrypt('stripe_secret_1234567890'),
            'webhook_url' => 'https://example.com/webhook/stripe',
            'currency' => 'USD',
            'commission_rate' => 2.9,
            'is_active' => false,
            'created_by' => $superadmin->id
        ]);

        // Get schools
        $schools = School::all();

        // Attach gateways to schools
        foreach ($schools as $school) {
            $gateway1->schools()->attach($school->id);
            $gateway2->schools()->attach($school->id);
        }

        // Create sample payment plans
        $plan1 = PaymentPlan::create([
            'name' => 'Basic Plan',
            'description' => 'Basic features for small schools',
            'gateway_id' => $gateway1->id,
            'price_type' => 'recurring',
            'price' => 999.00,
            'billing_cycle' => 'monthly',
            'features' => [
                'Student Management',
                'Basic Reports',
                'Email Support',
                'Up to 100 students'
            ],
            'is_active' => true,
            'created_by' => $superadmin->id
        ]);

        $plan2 = PaymentPlan::create([
            'name' => 'Standard Plan',
            'description' => 'Standard features for medium schools',
            'gateway_id' => $gateway1->id,
            'price_type' => 'recurring',
            'price' => 1999.00,
            'billing_cycle' => 'monthly',
            'features' => [
                'Student Management',
                'Advanced Reports',
                'Priority Support',
                'Up to 500 students',
                'Custom Fields',
                'API Access'
            ],
            'is_active' => true,
            'created_by' => $superadmin->id
        ]);

        $plan3 = PaymentPlan::create([
            'name' => 'Premium Plan',
            'description' => 'Premium features for large schools',
            'gateway_id' => $gateway2->id,
            'price_type' => 'recurring',
            'price' => 3999.00,
            'billing_cycle' => 'monthly',
            'features' => [
                'Student Management',
                'Advanced Reports',
                '24/7 Support',
                'Unlimited students',
                'Custom Fields',
                'API Access',
                'White Label',
                'Custom Integrations'
            ],
            'is_active' => true,
            'created_by' => $superadmin->id
        ]);

        $plan4 = PaymentPlan::create([
            'name' => 'Enterprise Plan',
            'description' => 'Enterprise features for large institutions',
            'gateway_id' => $gateway2->id,
            'price_type' => 'recurring',
            'price' => 7999.00,
            'billing_cycle' => 'yearly',
            'features' => [
                'Student Management',
                'Advanced Reports',
                '24/7 Support',
                'Unlimited students',
                'Custom Fields',
                'API Access',
                'White Label',
                'Custom Integrations',
                'Dedicated Support',
                'Custom Development'
            ],
            'is_active' => true,
            'created_by' => $superadmin->id
        ]);

        // Attach plans to schools
        foreach ($schools as $school) {
            $plan1->schools()->attach($school->id);
            $plan2->schools()->attach($school->id);
            $plan3->schools()->attach($school->id);
            $plan4->schools()->attach($school->id);
        }

        $this->command->info('Payment data seeded successfully!');
        $this->command->info('Created ' . PaymentGateway::count() . ' payment gateways');
        $this->command->info('Created ' . PaymentPlan::count() . ' payment plans');
    }
}