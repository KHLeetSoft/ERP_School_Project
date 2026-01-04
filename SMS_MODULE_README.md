# SMS Communication Module

A comprehensive SMS communication system for Laravel applications, specifically designed for educational institutions. This module provides advanced features for sending, scheduling, and managing SMS communications with students, parents, and staff.

## Features

### 🚀 Core Functionality
- **Multi-Gateway Support**: Twilio, MSG91, Nexmo, and custom gateways
- **Bulk SMS**: Send messages to multiple recipients simultaneously
- **Scheduled SMS**: Schedule messages for future delivery
- **Template System**: Pre-built and customizable SMS templates
- **Delivery Tracking**: Real-time delivery status and reports
- **Cost Management**: Track SMS costs and usage

### 📱 Recipient Management
- **Multiple Types**: Students, Parents, Staff, Classes, Sections
- **Smart Selection**: Search and filter recipients
- **Group Management**: Send to entire classes or sections
- **Phone Validation**: Automatic phone number validation

### 🎯 Advanced Features
- **Priority Levels**: Low, Normal, High, Urgent
- **Categories**: Notification, Reminder, Alert, Marketing
- **Confirmation Required**: Optional delivery confirmation
- **Expiry Dates**: Set message expiration times
- **Retry Mechanism**: Automatic retry for failed messages
- **Rate Limiting**: Prevent abuse and control costs

### 📊 Analytics & Reporting
- **Dashboard**: Comprehensive statistics and charts
- **Delivery Reports**: Success/failure rates by gateway
- **Cost Analysis**: Daily, monthly, and total cost tracking
- **Performance Metrics**: Gateway performance comparison

## Installation

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Default Data
```bash
php artisan db:seed --class=SmsTemplateSeeder
```

### 3. Publish Configuration
```bash
php artisan vendor:publish --tag=sms-config
```

### 4. Environment Variables
Add the following to your `.env` file:

```env
# SMS Configuration
SMS_DEFAULT_GATEWAY=twilio

# Twilio Configuration
TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_FROM_NUMBER=your_twilio_number
TWILIO_TEST_MODE=false

# MSG91 Configuration
MSG91_API_KEY=your_api_key
MSG91_SENDER_ID=your_sender_id
MSG91_ROUTE=4
MSG91_COUNTRY_CODE=91

# Nexmo Configuration
NEXMO_API_KEY=your_api_key
NEXMO_API_SECRET=your_api_secret
NEXMO_FROM_NUMBER=your_nexmo_number

# Custom Gateway Configuration
CUSTOM_SMS_API_URL=your_api_url
CUSTOM_SMS_API_KEY=your_api_key
CUSTOM_SMS_SENDER_ID=your_sender_id
```

## Usage

### Basic SMS Sending

```php
use App\Services\SmsService;

$smsService = new SmsService();

// Send immediate SMS
$result = $smsService->sendSms($smsMessage);

if ($result['success']) {
    echo "SMS sent successfully!";
} else {
    echo "Failed: " . $result['message'];
}
```

### Creating SMS Messages

```php
use App\Models\SmsMessage;

$smsMessage = SmsMessage::create([
    'sender_id' => auth()->id(),
    'recipient_type' => 'student',
    'recipient_ids' => [1, 2, 3],
    'message' => 'Hello students!',
    'priority' => 'normal',
    'category' => 'notification',
    'status' => 'draft'
]);
```

### Using Templates

```php
use App\Models\SmsTemplate;

$template = SmsTemplate::where('name', 'Welcome Message')->first();
$message = $template->parseContent([
    'school_name' => 'ABC School',
    'student_name' => 'John Doe',
    'start_date' => '2024-01-15',
    'school_phone' => '+1234567890'
]);
```

## API Endpoints

### SMS Management
- `GET /admin/communications/sms` - List all SMS messages
- `GET /admin/communications/sms/dashboard` - SMS dashboard
- `GET /admin/communications/sms/create` - Create SMS form
- `POST /admin/communications/sms` - Store new SMS
- `GET /admin/communications/sms/{id}` - View SMS details
- `GET /admin/communications/sms/{id}/edit` - Edit SMS form
- `PUT /admin/communications/sms/{id}` - Update SMS
- `DELETE /admin/communications/sms/{id}` - Delete SMS

### SMS Actions
- `POST /admin/communications/sms/{id}/send-now` - Send SMS immediately
- `POST /admin/communications/sms/{id}/retry` - Retry failed SMS
- `GET /admin/communications/sms/recipient-suggestions` - Get recipient suggestions
- `GET /admin/communications/sms/statistics` - Get SMS statistics

## Models

### SmsMessage
Main model for SMS messages with status tracking and delivery information.

**Key Fields:**
- `sender_id`: User who sent the SMS
- `recipient_type`: Type of recipients (student, parent, staff, etc.)
- `recipient_ids`: Array of recipient IDs
- `message`: SMS content
- `status`: Current status (draft, sent, delivered, failed)
- `priority`: Priority level
- `scheduled_at`: Scheduled delivery time
- `cost`: Total cost of the SMS

### SmsRecipient
Individual recipient tracking with delivery status.

**Key Fields:**
- `sms_message_id`: Reference to SMS message
- `recipient_id`: Recipient user ID
- `recipient_type`: Type of recipient
- `phone_number`: Recipient's phone number
- `status`: Delivery status
- `gateway_message_id`: Gateway's message ID

### SmsTemplate
Reusable SMS templates with variable support.

**Key Fields:**
- `name`: Template name
- `content`: Template content with variables
- `variables`: Array of available variables
- `category`: Template category
- `is_active`: Template availability

### SmsGateway
SMS gateway configurations and settings.

**Key Fields:**
- `name`: Gateway name
- `provider`: Gateway provider (twilio, msg91, nexmo, custom)
- `api_key`: API key for authentication
- `api_secret`: API secret for authentication
- `sender_id`: Sender ID or phone number
- `is_active`: Gateway availability

## Services

### SmsService
Core service for sending SMS messages through configured gateways.

**Key Methods:**
- `sendSms($smsMessage)`: Send SMS message
- `sendToRecipient($recipient)`: Send to individual recipient
- `processDeliveryReport($data, $gateway)`: Process delivery reports
- `getStatistics($period)`: Get SMS statistics

## Configuration

### SMS Settings
The module uses a dedicated configuration file (`config/sms.php`) with comprehensive settings:

- **Gateway Configurations**: API keys, secrets, and settings
- **Rate Limits**: Per-minute, per-hour, and per-day limits
- **Cost Settings**: Cost per SMS for different types
- **Templates**: Default SMS templates
- **Validation Rules**: Message length and recipient limits
- **Security Settings**: Confirmation requirements and retry limits

### Gateway Support

#### Twilio
- Account SID and Auth Token authentication
- From number configuration
- Test mode support

#### MSG91
- API key authentication
- Sender ID configuration
- Route and country code settings

#### Nexmo
- API key and secret authentication
- From number configuration

#### Custom Gateway
- Flexible API endpoint configuration
- Custom headers and body templates
- Configurable success conditions

## Templates

### Built-in Templates
The module comes with 20+ pre-built templates covering common use cases:

- Welcome messages
- Attendance reminders
- Exam results
- Fee reminders
- Emergency alerts
- Parent-teacher meetings
- Transport updates
- Library reminders
- Birthday wishes
- Holiday announcements
- Sports events
- Academic calendar
- Staff meetings
- Maintenance notices
- Achievement congratulations
- Health advisories

### Template Variables
Templates support dynamic variables that are replaced with actual values:

- `{{student_name}}`: Student's full name
- `{{parent_name}}`: Parent's name
- `{{school_name}}`: School name
- `{{date}}`: Current date
- `{{amount}}`: Fee amount
- `{{exam_name}}`: Exam name
- `{{grade}}`: Exam grade

## Security Features

### Rate Limiting
- Per-minute, per-hour, and per-day limits
- IP-based rate limiting
- Gateway-specific rate limits

### Validation
- Phone number format validation
- Message length validation
- Recipient count limits
- Country code restrictions

### Access Control
- Authentication required for all endpoints
- Role-based access control
- Audit logging for all actions

## Monitoring & Analytics

### Dashboard Metrics
- Total sent, delivered, and failed messages
- Daily and monthly statistics
- Cost tracking and analysis
- Gateway performance comparison

### Delivery Reports
- Real-time delivery status
- Failure reason tracking
- Retry attempt monitoring
- Gateway response logging

### Performance Analytics
- Success rates by gateway
- Delivery time analysis
- Cost per message tracking
- Usage pattern analysis

## Troubleshooting

### Common Issues

#### SMS Not Sending
1. Check gateway configuration
2. Verify API keys and secrets
3. Check rate limits
4. Review gateway logs

#### Delivery Failures
1. Verify phone number format
2. Check gateway status
3. Review failure reasons
4. Check account balance

#### Template Issues
1. Verify variable names
2. Check template syntax
3. Ensure template is active
4. Validate variable values

### Debug Mode
Enable debug logging in your `.env`:

```env
SMS_DEBUG=true
LOG_LEVEL=debug
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

This module is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions:
- Create an issue on GitHub
- Check the documentation
- Review the troubleshooting guide
- Contact the development team

## Changelog

### Version 1.0.0
- Initial release
- Multi-gateway support
- Template system
- Delivery tracking
- Analytics dashboard
- Bulk SMS functionality
- Scheduled SMS
- Advanced filtering and search
