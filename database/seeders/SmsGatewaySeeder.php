<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsGateway;
use App\Models\User;

class SmsGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('role_id', 2)->first() ?? User::first();

        $gateways = [
            [
                'name' => 'Twilio',
                'provider' => 'twilio',
                'api_key' => 'your_twilio_account_sid',
                'api_secret' => 'your_twilio_auth_token',
                'sender_id' => 'your_twilio_phone_number',
                'webhook_url' => null,
                'webhook_secret' => null,
                'is_active' => true,
                'is_default' => true,
                'priority' => 1,
                'rate_limit' => 1000,
                'daily_limit' => 10000,
                'monthly_limit' => 300000,
                'cost_per_sms' => 0.0075,
                'currency' => 'USD',
                'settings' => [
                    'account_sid' => 'your_twilio_account_sid',
                    'auth_token' => 'your_twilio_auth_token',
                    'from_number' => 'your_twilio_phone_number'
                ],
                'test_mode' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'MSG91',
                'provider' => 'msg91',
                'api_key' => 'your_msg91_api_key',
                'api_secret' => 'your_msg91_api_secret',
                'sender_id' => 'SCHOOL',
                'webhook_url' => null,
                'webhook_secret' => null,
                'is_active' => true,
                'is_default' => false,
                'priority' => 2,
                'rate_limit' => 500,
                'daily_limit' => 5000,
                'monthly_limit' => 150000,
                'cost_per_sms' => 0.02,
                'currency' => 'INR',
                'settings' => [
                    'api_key' => 'your_msg91_api_key',
                    'sender_id' => 'SCHOOL',
                    'route' => '4'
                ],
                'test_mode' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Nexmo (Vonage)',
                'provider' => 'nexmo',
                'api_key' => 'your_nexmo_api_key',
                'api_secret' => 'your_nexmo_api_secret',
                'sender_id' => 'SchoolApp',
                'webhook_url' => null,
                'webhook_secret' => null,
                'is_active' => true,
                'is_default' => false,
                'priority' => 3,
                'rate_limit' => 100,
                'daily_limit' => 1000,
                'monthly_limit' => 30000,
                'cost_per_sms' => 0.045,
                'currency' => 'USD',
                'settings' => [
                    'api_key' => 'your_nexmo_api_key',
                    'api_secret' => 'your_nexmo_api_secret',
                    'from' => 'SchoolApp'
                ],
                'test_mode' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Custom Gateway',
                'provider' => 'custom',
                'api_key' => 'your_custom_api_key',
                'api_secret' => 'your_custom_api_secret',
                'sender_id' => 'CUSTOM',
                'webhook_url' => 'https://your-domain.com/sms/webhook',
                'webhook_secret' => 'your_webhook_secret',
                'is_active' => false,
                'is_default' => false,
                'priority' => 4,
                'rate_limit' => 100,
                'daily_limit' => 1000,
                'monthly_limit' => 30000,
                'cost_per_sms' => 0.01,
                'currency' => 'USD',
                'settings' => [
                    'api_url' => 'https://your-sms-provider.com/api/send',
                    'method' => 'POST',
                    'headers' => [
                        'Authorization' => 'Bearer {api_key}',
                        'Content-Type' => 'application/json'
                    ],
                    'body_format' => 'json',
                    'body_mapping' => [
                        'to' => 'phone_number',
                        'message' => 'message',
                        'from' => 'sender_id'
                    ],
                    'success_response' => [
                        'status_field' => 'status',
                        'success_value' => 'success',
                        'message_id_field' => 'message_id'
                    ]
                ],
                'test_mode' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ]
        ];

        foreach ($gateways as $gateway) {
            SmsGateway::updateOrCreate(
                ['name' => $gateway['name']],
                $gateway
            );
        }

        $this->command->info('SMS gateways seeded successfully!');
        $this->command->warn('Remember to update the API keys and credentials with your actual values!');
    }
}
