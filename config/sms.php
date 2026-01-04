<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SMS Gateway
    |--------------------------------------------------------------------------
    |
    | This option controls the default SMS gateway that will be used
    | when sending SMS messages.
    |
    */
    'default_gateway' => env('SMS_DEFAULT_GATEWAY', 'twilio'),

    /*
    |--------------------------------------------------------------------------
    | SMS Gateways
    |--------------------------------------------------------------------------
    |
    | Here you may configure the SMS gateways for your application.
    | Each gateway has its own configuration options.
    |
    */
    'gateways' => [
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'from_number' => env('TWILIO_FROM_NUMBER'),
            'test_mode' => env('TWILIO_TEST_MODE', false),
        ],

        'msg91' => [
            'api_key' => env('MSG91_API_KEY'),
            'sender_id' => env('MSG91_SENDER_ID'),
            'route' => env('MSG91_ROUTE', 4), // 4 for transactional
            'country_code' => env('MSG91_COUNTRY_CODE', '91'),
            'test_mode' => env('MSG91_TEST_MODE', false),
        ],

        'nexmo' => [
            'api_key' => env('NEXMO_API_KEY'),
            'api_secret' => env('NEXMO_API_SECRET'),
            'from_number' => env('NEXMO_FROM_NUMBER'),
            'test_mode' => env('NEXMO_TEST_MODE', false),
        ],

        'custom' => [
            'api_url' => env('CUSTOM_SMS_API_URL'),
            'api_key' => env('CUSTOM_SMS_API_KEY'),
            'sender_id' => env('CUSTOM_SMS_SENDER_ID'),
            'method' => env('CUSTOM_SMS_METHOD', 'POST'),
            'headers' => json_decode(env('CUSTOM_SMS_HEADERS', '{}'), true),
            'body_template' => json_decode(env('CUSTOM_SMS_BODY_TEMPLATE', '{}'), true),
            'success_condition' => env('CUSTOM_SMS_SUCCESS_CONDITION'),
            'message_id_field' => env('CUSTOM_SMS_MESSAGE_ID_FIELD', 'id'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Rate Limits
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for SMS sending to prevent abuse.
    |
    */
    'rate_limits' => [
        'per_minute' => env('SMS_RATE_LIMIT_PER_MINUTE', 60),
        'per_hour' => env('SMS_RATE_LIMIT_PER_HOUR', 1000),
        'per_day' => env('SMS_RATE_LIMIT_PER_DAY', 10000),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Cost Settings
    |--------------------------------------------------------------------------
    |
    | Configure the cost per SMS for different types and regions.
    |
    */
    'costs' => [
        'default' => env('SMS_COST_PER_SMS', 0.01),
        'international' => env('SMS_COST_INTERNATIONAL', 0.05),
        'premium' => env('SMS_COST_PREMIUM', 0.02),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Templates
    |--------------------------------------------------------------------------
    |
    | Default SMS templates that can be used throughout the application.
    |
    */
    'templates' => [
        'welcome' => [
            'name' => 'Welcome Message',
            'content' => 'Welcome to {{school_name}}! We\'re excited to have you on board.',
            'category' => 'welcome',
            'variables' => ['school_name'],
        ],
        'attendance_reminder' => [
            'name' => 'Attendance Reminder',
            'content' => 'Dear {{parent_name}}, {{student_name}} was absent on {{date}}. Please contact us if you have any questions.',
            'category' => 'reminder',
            'variables' => ['parent_name', 'student_name', 'date'],
        ],
        'exam_result' => [
            'name' => 'Exam Result',
            'content' => 'Dear {{parent_name}}, {{student_name}} scored {{percentage}}% in {{exam_name}}. Result: {{grade}}.',
            'category' => 'notification',
            'variables' => ['parent_name', 'student_name', 'percentage', 'exam_name', 'grade'],
        ],
        'fee_reminder' => [
            'name' => 'Fee Reminder',
            'content' => 'Dear {{parent_name}}, fee payment of ₹{{amount}} is due on {{due_date}}. Please pay on time.',
            'category' => 'reminder',
            'variables' => ['parent_name', 'amount', 'due_date'],
        ],
        'emergency_alert' => [
            'name' => 'Emergency Alert',
            'content' => 'URGENT: {{message}}. Please take necessary action immediately.',
            'category' => 'alert',
            'variables' => ['message'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Categories
    |--------------------------------------------------------------------------
    |
    | Available categories for organizing SMS messages.
    |
    */
    'categories' => [
        'notification' => 'General notifications and updates',
        'reminder' => 'Reminders for events, payments, etc.',
        'alert' => 'Important alerts and warnings',
        'marketing' => 'Promotional and marketing messages',
        'welcome' => 'Welcome and onboarding messages',
        'farewell' => 'Goodbye and farewell messages',
        'birthday' => 'Birthday wishes and greetings',
        'anniversary' => 'Anniversary and milestone messages',
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Priorities
    |--------------------------------------------------------------------------
    |
    | Available priority levels for SMS messages.
    |
    */
    'priorities' => [
        'low' => 'Low priority messages',
        'normal' => 'Standard priority messages',
        'high' => 'High priority messages',
        'urgent' => 'Urgent messages requiring immediate attention',
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Statuses
    |--------------------------------------------------------------------------
    |
    | Available statuses for tracking SMS delivery.
    |
    */
    'statuses' => [
        'draft' => 'Message is saved as draft',
        'scheduled' => 'Message is scheduled for later delivery',
        'pending' => 'Message is queued for sending',
        'sent' => 'Message has been sent to gateway',
        'delivered' => 'Message has been delivered to recipient',
        'failed' => 'Message failed to send or deliver',
        'cancelled' => 'Message was cancelled',
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Validation Rules
    |--------------------------------------------------------------------------
    |
    | Validation rules for SMS messages and recipients.
    |
    */
    'validation' => [
        'max_message_length' => 1600,
        'min_message_length' => 1,
        'max_recipients' => 10000,
        'phone_number_pattern' => '/^\+?[1-9]\d{1,14}$/',
        'allowed_countries' => ['IN', 'US', 'GB', 'CA', 'AU'],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Queue Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for SMS queuing and processing.
    |
    */
    'queue' => [
        'connection' => env('SMS_QUEUE_CONNECTION', 'default'),
        'queue' => env('SMS_QUEUE_NAME', 'sms'),
        'retry_after' => env('SMS_QUEUE_RETRY_AFTER', 90),
        'tries' => env('SMS_QUEUE_TRIES', 3),
        'timeout' => env('SMS_QUEUE_TIMEOUT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Webhook Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for receiving delivery reports and status updates.
    |
    */
    'webhooks' => [
        'enabled' => env('SMS_WEBHOOKS_ENABLED', true),
        'secret' => env('SMS_WEBHOOK_SECRET'),
        'endpoints' => [
            'twilio' => '/webhooks/sms/twilio',
            'msg91' => '/webhooks/sms/msg91',
            'nexmo' => '/webhooks/sms/nexmo',
            'custom' => '/webhooks/sms/custom',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Analytics Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for SMS analytics and reporting.
    |
    */
    'analytics' => [
        'enabled' => env('SMS_ANALYTICS_ENABLED', true),
        'retention_days' => env('SMS_ANALYTICS_RETENTION_DAYS', 365),
        'track_delivery' => env('SMS_TRACK_DELIVERY', true),
        'track_opens' => env('SMS_TRACK_OPENS', false),
        'track_clicks' => env('SMS_TRACK_CLICKS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Security Settings
    |--------------------------------------------------------------------------
    |
    | Security configurations for SMS functionality.
    |
    */
    'security' => [
        'require_confirmation' => env('SMS_REQUIRE_CONFIRMATION', false),
        'max_retries' => env('SMS_MAX_RETRIES', 3),
        'blacklist_numbers' => json_decode(env('SMS_BLACKLIST_NUMBERS', '[]'), true),
        'whitelist_numbers' => json_decode(env('SMS_WHITELIST_NUMBERS', '[]'), true),
        'rate_limit_by_ip' => env('SMS_RATE_LIMIT_BY_IP', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for SMS-related notifications.
    |
    */
    'notifications' => [
        'admin_on_failure' => env('SMS_NOTIFY_ADMIN_ON_FAILURE', true),
        'admin_email' => env('SMS_ADMIN_EMAIL'),
        'low_balance_threshold' => env('SMS_LOW_BALANCE_THRESHOLD', 100),
        'daily_summary' => env('SMS_DAILY_SUMMARY', true),
        'weekly_report' => env('SMS_WEEKLY_REPORT', true),
    ],
];
