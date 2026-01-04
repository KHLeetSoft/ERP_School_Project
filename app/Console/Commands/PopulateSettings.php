<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate settings table with default values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Populating settings table...');

        $settings = [
            // General Settings
            ['key' => 'app_name', 'value' => 'School Management System', 'category' => 'general', 'type' => 'string', 'description' => 'Application name', 'is_public' => true],
            ['key' => 'system_title', 'value' => 'School Management System', 'category' => 'general', 'type' => 'string', 'description' => 'System title', 'is_public' => true],
            ['key' => 'footer_text', 'value' => '© 2024 School Management System. All rights reserved.', 'category' => 'general', 'type' => 'text', 'description' => 'Footer text', 'is_public' => true],
            ['key' => 'default_language', 'value' => 'en', 'category' => 'general', 'type' => 'string', 'description' => 'Default language', 'is_public' => true],
            ['key' => 'timezone', 'value' => 'UTC', 'category' => 'general', 'type' => 'string', 'description' => 'Default timezone', 'is_public' => true],
            ['key' => 'currency', 'value' => 'USD', 'category' => 'general', 'type' => 'string', 'description' => 'Default currency', 'is_public' => true],
            ['key' => 'currency_symbol', 'value' => '$', 'category' => 'general', 'type' => 'string', 'description' => 'Currency symbol', 'is_public' => true],

            // User Settings
            ['key' => 'max_login_attempts', 'value' => '5', 'category' => 'users', 'type' => 'integer', 'description' => 'Maximum login attempts', 'is_public' => false],
            ['key' => 'session_timeout', 'value' => '120', 'category' => 'users', 'type' => 'integer', 'description' => 'Session timeout in minutes', 'is_public' => false],
            ['key' => 'two_factor_enabled', 'value' => '0', 'category' => 'users', 'type' => 'boolean', 'description' => 'Enable two-factor authentication', 'is_public' => false],
            ['key' => 'password_min_length', 'value' => '8', 'category' => 'users', 'type' => 'integer', 'description' => 'Minimum password length', 'is_public' => false],
            ['key' => 'password_require_special', 'value' => '1', 'category' => 'users', 'type' => 'boolean', 'description' => 'Require special characters in password', 'is_public' => false],

            // Email Settings
            ['key' => 'mail_driver', 'value' => 'smtp', 'category' => 'email', 'type' => 'string', 'description' => 'Mail driver', 'is_public' => false],
            ['key' => 'mail_host', 'value' => 'smtp.gmail.com', 'category' => 'email', 'type' => 'string', 'description' => 'Mail host', 'is_public' => false],
            ['key' => 'mail_port', 'value' => '587', 'category' => 'email', 'type' => 'integer', 'description' => 'Mail port', 'is_public' => false],
            ['key' => 'mail_username', 'value' => '', 'category' => 'email', 'type' => 'string', 'description' => 'Mail username', 'is_public' => false],
            ['key' => 'mail_password', 'value' => '', 'category' => 'email', 'type' => 'string', 'description' => 'Mail password', 'is_public' => false],
            ['key' => 'mail_encryption', 'value' => 'tls', 'category' => 'email', 'type' => 'string', 'description' => 'Mail encryption', 'is_public' => false],
            ['key' => 'mail_from_address', 'value' => 'noreply@schoolsystem.com', 'category' => 'email', 'type' => 'string', 'description' => 'From email address', 'is_public' => false],
            ['key' => 'mail_from_name', 'value' => 'School Management System', 'category' => 'email', 'type' => 'string', 'description' => 'From name', 'is_public' => false],

            // System Settings
            ['key' => 'app_env', 'value' => 'production', 'category' => 'system', 'type' => 'string', 'description' => 'Application environment', 'is_public' => false],
            ['key' => 'debug_mode', 'value' => '0', 'category' => 'system', 'type' => 'boolean', 'description' => 'Debug mode', 'is_public' => false],
            ['key' => 'log_level', 'value' => 'error', 'category' => 'system', 'type' => 'string', 'description' => 'Log level', 'is_public' => false],
            ['key' => 'maintenance_mode', 'value' => '0', 'category' => 'system', 'type' => 'boolean', 'description' => 'Maintenance mode', 'is_public' => false],

            // Security Settings
            ['key' => 'force_https', 'value' => '0', 'category' => 'security', 'type' => 'boolean', 'description' => 'Force HTTPS', 'is_public' => false],
            ['key' => 'password_expiry_days', 'value' => '90', 'category' => 'security', 'type' => 'integer', 'description' => 'Password expiry days', 'is_public' => false],
            ['key' => 'ip_whitelist', 'value' => '', 'category' => 'security', 'type' => 'text', 'description' => 'IP whitelist', 'is_public' => false],
            ['key' => 'csrf_protection', 'value' => '1', 'category' => 'security', 'type' => 'boolean', 'description' => 'CSRF protection', 'is_public' => false],
            ['key' => 'xss_protection', 'value' => '1', 'category' => 'security', 'type' => 'boolean', 'description' => 'XSS protection', 'is_public' => false],
            ['key' => 'audit_log_enabled', 'value' => '1', 'category' => 'security', 'type' => 'boolean', 'description' => 'Audit log enabled', 'is_public' => false],

            // Theme Settings
            ['key' => 'theme_mode', 'value' => 'light', 'category' => 'theme', 'type' => 'string', 'description' => 'Theme mode', 'is_public' => true],
            ['key' => 'primary_color', 'value' => '#007bff', 'category' => 'theme', 'type' => 'string', 'description' => 'Primary color', 'is_public' => true],
            ['key' => 'secondary_color', 'value' => '#6c757d', 'category' => 'theme', 'type' => 'string', 'description' => 'Secondary color', 'is_public' => true],
            ['key' => 'sidebar_style', 'value' => 'default', 'category' => 'theme', 'type' => 'string', 'description' => 'Sidebar style', 'is_public' => true],
            ['key' => 'font_family', 'value' => 'Inter', 'category' => 'theme', 'type' => 'string', 'description' => 'Font family', 'is_public' => true],

            // Payment Settings
            ['key' => 'default_payment_gateway', 'value' => 'stripe', 'category' => 'payment', 'type' => 'string', 'description' => 'Default payment gateway', 'is_public' => false],
            ['key' => 'stripe_webhook_secret', 'value' => '', 'category' => 'payment', 'type' => 'string', 'description' => 'Stripe webhook secret', 'is_public' => false],
            ['key' => 'paypal_client_id', 'value' => '', 'category' => 'payment', 'type' => 'string', 'description' => 'PayPal client ID', 'is_public' => false],
            ['key' => 'paypal_client_secret', 'value' => '', 'category' => 'payment', 'type' => 'string', 'description' => 'PayPal client secret', 'is_public' => false],
            ['key' => 'tax_rate', 'value' => '0', 'category' => 'payment', 'type' => 'decimal', 'description' => 'Tax rate', 'is_public' => false],
            ['key' => 'currency_code', 'value' => 'USD', 'category' => 'payment', 'type' => 'string', 'description' => 'Currency code', 'is_public' => false],

            // Developer Settings
            ['key' => 'api_documentation_enabled', 'value' => '1', 'category' => 'developer', 'type' => 'boolean', 'description' => 'API documentation enabled', 'is_public' => false],
            ['key' => 'webhook_url', 'value' => '', 'category' => 'developer', 'type' => 'string', 'description' => 'Webhook URL', 'is_public' => false],
            ['key' => 'custom_css', 'value' => '', 'category' => 'developer', 'type' => 'text', 'description' => 'Custom CSS', 'is_public' => false],
            ['key' => 'custom_js', 'value' => '', 'category' => 'developer', 'type' => 'text', 'description' => 'Custom JavaScript', 'is_public' => false],

            // Advanced Settings
            ['key' => 'multi_tenancy_enabled', 'value' => '0', 'category' => 'advanced', 'type' => 'boolean', 'description' => 'Multi-tenancy enabled', 'is_public' => false],
            ['key' => 'data_retention_days', 'value' => '365', 'category' => 'advanced', 'type' => 'integer', 'description' => 'Data retention days', 'is_public' => false],
            ['key' => 'gdpr_compliance', 'value' => '1', 'category' => 'advanced', 'type' => 'boolean', 'description' => 'GDPR compliance', 'is_public' => false],
            ['key' => 'privacy_policy_url', 'value' => '', 'category' => 'advanced', 'type' => 'string', 'description' => 'Privacy policy URL', 'is_public' => false],
            ['key' => 'terms_of_service_url', 'value' => '', 'category' => 'advanced', 'type' => 'string', 'description' => 'Terms of service URL', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->info('Settings populated successfully!');
        return 0;
    }
}
