<?php

namespace App\Services;

use App\Models\SmsMessage;
use App\Models\SmsRecipient;
use App\Models\SmsGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SmsService
{
    protected $gateway;
    protected $config;

    public function __construct()
    {
        // Get default gateway
        $this->gateway = SmsGateway::default()->first();
        if ($this->gateway) {
            $this->config = $this->gateway->getConfig();
        }
    }

    /**
     * Send SMS message
     */
    public function sendSms(SmsMessage $smsMessage)
    {
        try {
            // Check if gateway is available
            if (!$this->gateway || !$this->gateway->isAvailable()) {
                return $this->handleFailure($smsMessage, 'No available SMS gateway');
            }

            // Update message status
            $smsMessage->markAsSent();

            // Send to each recipient
            $successCount = 0;
            $failureCount = 0;

            foreach ($smsMessage->recipients as $recipient) {
                $result = $this->sendToRecipient($recipient);
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $failureCount++;
                    Log::error("SMS send failed for recipient {$recipient->id}: " . $result['message']);
                }
            }

            // Update overall message status
            if ($failureCount === 0) {
                $smsMessage->markAsDelivered();
                return ['success' => true, 'message' => 'All SMS sent successfully'];
            } elseif ($successCount === 0) {
                $smsMessage->markAsFailed('All recipients failed');
                return ['success' => false, 'message' => 'All SMS failed to send'];
            } else {
                // Partial success
                return [
                    'success' => true, 
                    'message' => "SMS sent to {$successCount} recipients, {$failureCount} failed"
                ];
            }

        } catch (\Exception $e) {
            Log::error('SMS service error: ' . $e->getMessage());
            $smsMessage->markAsFailed($e->getMessage());
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Send SMS to individual recipient
     */
    protected function sendToRecipient(SmsRecipient $recipient)
    {
        try {
            // Validate phone number
            if (!$this->isValidPhoneNumber($recipient->phone_number)) {
                return ['success' => false, 'message' => 'Invalid phone number format'];
            }

            // Check if gateway supports this phone number
            if (!$this->gateway->canSendTo($recipient->phone_number)) {
                return ['success' => false, 'message' => 'Phone number not supported by gateway'];
            }

            // Send based on gateway provider
            $result = $this->sendViaGateway($recipient);

            if ($result['success']) {
                $recipient->markAsSent();
                $recipient->update([
                    'gateway_message_id' => $result['message_id'] ?? null,
                    'gateway_response' => $result['response'] ?? null
                ]);
            } else {
                $recipient->markAsFailed($result['message']);
                $recipient->update([
                    'gateway_response' => $result['response'] ?? null
                ]);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error("SMS send error for recipient {$recipient->id}: " . $e->getMessage());
            $recipient->markAsFailed($e->getMessage());
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Send SMS via configured gateway
     */
    protected function sendViaGateway(SmsRecipient $recipient)
    {
        switch ($this->gateway->provider) {
            case 'twilio':
                return $this->sendViaTwilio($recipient);
            case 'msg91':
                return $this->sendViaMsg91($recipient);
            case 'nexmo':
                return $this->sendViaNexmo($recipient);
            case 'custom':
                return $this->sendViaCustomGateway($recipient);
            default:
                return ['success' => false, 'message' => 'Unknown gateway provider'];
        }
    }

    /**
     * Send via Twilio
     */
    protected function sendViaTwilio(SmsRecipient $recipient)
    {
        try {
            $accountSid = $this->config['api_key'];
            $authToken = $this->config['api_secret'];
            $fromNumber = $this->config['sender_id'];

            $url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";
            
            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post($url, [
                    'From' => $fromNumber,
                    'To' => $recipient->phone_number,
                    'Body' => $recipient->smsMessage->message
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message_id' => $data['sid'] ?? null,
                    'response' => $data
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Twilio API error: ' . $response->body(),
                    'response' => $response->json()
                ];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Twilio error: ' . $e->getMessage()];
        }
    }

    /**
     * Send via MSG91
     */
    protected function sendViaMsg91(SmsRecipient $recipient)
    {
        try {
            $apiKey = $this->config['api_key'];
            $senderId = $this->config['sender_id'];
            $route = $this->config['settings']['route'] ?? 4; // 4 for transactional

            $url = "https://api.msg91.com/api/v2/sendsms";
            
            $data = [
                'sender' => $senderId,
                'route' => $route,
                'country' => '91',
                'sms' => [
                    [
                        'message' => $recipient->smsMessage->message,
                        'to' => [$recipient->phone_number]
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'authkey' => $apiKey,
                'Content-Type' => 'application/json'
            ])->post($url, $data);

            if ($response->successful()) {
                $responseData = $response->json();
                if ($responseData['type'] === 'success') {
                    return [
                        'success' => true,
                        'message_id' => $responseData['request_id'] ?? null,
                        'response' => $responseData
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'MSG91 error: ' . ($responseData['message'] ?? 'Unknown error'),
                        'response' => $responseData
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'MSG91 API error: ' . $response->body(),
                    'response' => $response->json()
                ];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'MSG91 error: ' . $e->getMessage()];
        }
    }

    /**
     * Send via Nexmo
     */
    protected function sendViaNexmo(SmsRecipient $recipient)
    {
        try {
            $apiKey = $this->config['api_key'];
            $apiSecret = $this->config['api_secret'];
            $from = $this->config['sender_id'];

            $url = "https://rest.nexmo.com/sms/json";
            
            $response = Http::asForm()->post($url, [
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'to' => $recipient->phone_number,
                'from' => $from,
                'text' => $recipient->smsMessage->message
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['messages'][0]['status']) && $data['messages'][0]['status'] === '0') {
                    return [
                        'success' => true,
                        'message_id' => $data['messages'][0]['message-id'] ?? null,
                        'response' => $data
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Nexmo error: ' . ($data['messages'][0]['error-text'] ?? 'Unknown error'),
                        'response' => $data
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Nexmo API error: ' . $response->body(),
                    'response' => $response->json()
                ];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Nexmo error: ' . $e->getMessage()];
        }
    }

    /**
     * Send via custom gateway
     */
    protected function sendViaCustomGateway(SmsRecipient $recipient)
    {
        try {
            $url = $this->config['settings']['api_url'] ?? null;
            $method = $this->config['settings']['method'] ?? 'POST';
            $headers = $this->config['settings']['headers'] ?? [];
            $body = $this->config['settings']['body_template'] ?? [];

            if (!$url) {
                return ['success' => false, 'message' => 'Custom gateway URL not configured'];
            }

            // Replace placeholders in body template
            $body = $this->replacePlaceholders($body, $recipient);

            $httpRequest = Http::withHeaders($headers);

            if ($method === 'GET') {
                $response = $httpRequest->get($url, $body);
            } else {
                $response = $httpRequest->post($url, $body);
            }

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check custom success condition
                $successCondition = $this->config['settings']['success_condition'] ?? null;
                $isSuccess = $successCondition ? $this->evaluateCondition($responseData, $successCondition) : true;

                if ($isSuccess) {
                    return [
                        'success' => true,
                        'message_id' => $this->extractMessageId($responseData),
                        'response' => $responseData
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Custom gateway error',
                        'response' => $responseData
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Custom gateway API error: ' . $response->body(),
                    'response' => $response->json()
                ];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Custom gateway error: ' . $e->getMessage()];
        }
    }

    /**
     * Replace placeholders in custom gateway body template
     */
    protected function replacePlaceholders($body, SmsRecipient $recipient)
    {
        $replacements = [
            '{{phone}}' => $recipient->phone_number,
            '{{message}}' => $recipient->smsMessage->message,
            '{{sender_id}}' => $this->config['sender_id'],
            '{{timestamp}}' => now()->timestamp
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $body);
    }

    /**
     * Evaluate custom success condition
     */
    protected function evaluateCondition($response, $condition)
    {
        // Simple condition evaluation - can be extended
        if (is_string($condition)) {
            return strpos($response, $condition) !== false;
        }
        
        return true;
    }

    /**
     * Extract message ID from response
     */
    protected function extractMessageId($response)
    {
        $idField = $this->config['settings']['message_id_field'] ?? 'id';
        
        if (is_array($response) && isset($response[$idField])) {
            return $response[$idField];
        }
        
        return null;
    }

    /**
     * Validate phone number format
     */
    protected function isValidPhoneNumber($phoneNumber)
    {
        // Basic validation - can be enhanced
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // Check if it starts with + and has 10-15 digits
        if (preg_match('/^\+[0-9]{10,15}$/', $phoneNumber)) {
            return true;
        }
        
        // Check if it's a 10-digit number (assuming India)
        if (preg_match('/^[0-9]{10}$/', $phoneNumber)) {
            return true;
        }
        
        return false;
    }

    /**
     * Handle SMS failure
     */
    protected function handleFailure(SmsMessage $smsMessage, $reason)
    {
        $smsMessage->markAsFailed($reason);
        
        return ['success' => false, 'message' => $reason];
    }

    /**
     * Process delivery reports
     */
    public function processDeliveryReport($data, $gateway)
    {
        try {
            $messageId = $this->extractDeliveryReportMessageId($data, $gateway);
            $status = $this->extractDeliveryReportStatus($data, $gateway);
            
            if ($messageId && $status) {
                $recipient = SmsRecipient::where('gateway_message_id', $messageId)->first();
                
                if ($recipient) {
                    switch ($status) {
                        case 'delivered':
                            $recipient->markAsDelivered();
                            break;
                        case 'failed':
                            $recipient->markAsFailed('Delivery failed');
                            break;
                        case 'sent':
                            $recipient->markAsSent();
                            break;
                    }
                    
                    // Update overall message status
                    $this->updateMessageStatus($recipient->smsMessage);
                }
            }
            
            return ['success' => true];
            
        } catch (\Exception $e) {
            Log::error('Delivery report processing failed: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Extract message ID from delivery report
     */
    protected function extractDeliveryReportMessageId($data, $gateway)
    {
        switch ($gateway) {
            case 'twilio':
                return $data['MessageSid'] ?? null;
            case 'msg91':
                return $data['request_id'] ?? null;
            case 'nexmo':
                return $data['message-id'] ?? null;
            default:
                return $data['id'] ?? null;
        }
    }

    /**
     * Extract status from delivery report
     */
    protected function extractDeliveryReportStatus($data, $gateway)
    {
        switch ($gateway) {
            case 'twilio':
                return $data['MessageStatus'] ?? null;
            case 'msg91':
                return $data['status'] ?? null;
            case 'nexmo':
                return $data['status'] ?? null;
            default:
                return $data['status'] ?? null;
        }
    }

    /**
     * Update overall message status
     */
    protected function updateMessageStatus(SmsMessage $smsMessage)
    {
        $totalRecipients = $smsMessage->recipients()->count();
        $deliveredRecipients = $smsMessage->recipients()->where('status', 'delivered')->count();
        $failedRecipients = $smsMessage->recipients()->where('status', 'failed')->count();
        
        if ($deliveredRecipients === $totalRecipients) {
            $smsMessage->markAsDelivered();
        } elseif ($failedRecipients === $totalRecipients) {
            $smsMessage->markAsFailed('All recipients failed');
        }
        // If mixed status, keep as 'sent'
    }

    /**
     * Get SMS statistics
     */
    public function getStatistics($period = 'month')
    {
        $startDate = $this->getStartDate($period);
        
        return SmsMessage::where('created_at', '>=', $startDate)
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total,
                SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed,
                SUM(cost) as total_cost
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get start date for statistics
     */
    protected function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->startOfWeek();
            case 'month':
                return now()->startOfMonth();
            case 'year':
                return now()->startOfYear();
            default:
                return now()->subDays(30);
        }
    }
}


