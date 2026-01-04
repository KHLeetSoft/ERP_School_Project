<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // General Settings
        $generalSettings = [
            'app_name' => 'School Management System',
            'system_title' => 'School Management System',
            'footer_text' => '© 2024 School Management System. All rights reserved.',
            'default_language' => 'en',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'currency_symbol' => '$',
        ];

        foreach ($generalSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'general',
                    'type' => 'string',
                    'description' => 'General application setting',
                    'is_public' => false
                ]
            );
        }

        // User & Roles Settings
        $userSettings = [
            'max_login_attempts' => '5',
            'session_timeout' => '120',
            'two_factor_enabled' => '0',
            'password_min_length' => '8',
            'password_require_special' => '1',
        ];

        foreach ($userSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'users',
                    'type' => 'string',
                    'description' => 'User management setting',
                    'is_public' => false
                ]
            );
        }

        // Email Settings
        $emailSettings = [
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '587',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'noreply@schoolsystem.com',
            'mail_from_name' => 'School Management System',
            'sms_api_key' => '',
            'push_notification_key' => '',
        ];

        foreach ($emailSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'email',
                    'type' => 'string',
                    'description' => 'Email configuration setting',
                    'is_public' => false
                ]
            );
        }

        // System Settings
        $systemSettings = [
            'app_env' => 'production',
            'debug_mode' => '0',
            'log_level' => 'error',
            'maintenance_mode' => '0',
            'stripe_key' => '',
            'stripe_secret' => '',
            'razorpay_key' => '',
            'razorpay_secret' => '',
            'google_api_key' => '',
            'openai_api_key' => '',
        ];

        foreach ($systemSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'system',
                    'type' => 'string',
                    'description' => 'System configuration setting',
                    'is_public' => false
                ]
            );
        }

        // Database Settings
        $databaseSettings = [
            'backup_frequency' => 'daily',
            'backup_retention_days' => '30',
            'storage_disk' => 'local',
            's3_bucket' => '',
            's3_region' => 'us-east-1',
        ];

        foreach ($databaseSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'database',
                    'type' => 'string',
                    'description' => 'Database configuration setting',
                    'is_public' => false
                ]
            );
        }

        // Security Settings
        $securitySettings = [
            'force_https' => '0',
            'password_expiry_days' => '90',
            'ip_whitelist' => '',
            'csrf_protection' => '1',
            'xss_protection' => '1',
            'audit_log_enabled' => '1',
        ];

        foreach ($securitySettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'security',
                    'type' => 'string',
                    'description' => 'Security configuration setting',
                    'is_public' => false
                ]
            );
        }

        // Payment Settings
        $paymentSettings = [
            'default_payment_gateway' => 'stripe',
            'stripe_webhook_secret' => '',
            'paypal_client_id' => '',
            'paypal_client_secret' => '',
            'tax_rate' => '0',
            'currency_code' => 'USD',
        ];

        foreach ($paymentSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'payment',
                    'type' => 'string',
                    'description' => 'Payment configuration setting',
                    'is_public' => false
                ]
            );
        }

        // Developer Settings
        $developerSettings = [
            'api_documentation_enabled' => '1',
            'webhook_url' => '',
            'custom_css' => '',
            'custom_js' => '',
        ];

        foreach ($developerSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'developer',
                    'type' => 'string',
                    'description' => 'Developer configuration setting',
                    'is_public' => false
                ]
            );
        }

        // Theme Settings
        $themeSettings = [
            'theme_mode' => 'light',
            'primary_color' => '#007bff',
            'secondary_color' => '#6c757d',
            'sidebar_style' => 'default',
            'font_family' => 'Inter',
        ];

        foreach ($themeSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'theme',
                    'type' => 'string',
                    'description' => 'Theme configuration setting',
                    'is_public' => false
                ]
            );
        }

        // Advanced Settings
        $advancedSettings = [
            'multi_tenancy_enabled' => '0',
            'data_retention_days' => '365',
            'gdpr_compliance' => '1',
            'privacy_policy_url' => '',
            'terms_of_service_url' => '',
        ];

        foreach ($advancedSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'category' => 'advanced',
                    'type' => 'string',
                    'description' => 'Advanced configuration setting',
                    'is_public' => false
                ]
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}
