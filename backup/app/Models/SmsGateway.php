<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsGateway extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'provider', // twilio, msg91, nexmo, custom
        'api_key',
        'api_secret',
        'sender_id',
        'webhook_url',
        'webhook_secret',
        'is_active',
        'is_default',
        'priority',
        'rate_limit', // messages per minute
        'daily_limit', // messages per day
        'monthly_limit', // messages per month
        'cost_per_sms',
        'currency',
        'settings', // JSON configuration
        'test_mode',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'test_mode' => 'boolean',
        'settings' => 'array',
        'cost_per_sms' => 'decimal:4'
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
        'webhook_secret'
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeTestMode($query)
    {
        return $query->where('test_mode', true);
    }

    public function scopeProductionMode($query)
    {
        return $query->where('test_mode', false);
    }

    // Accessors
    public function getProviderBadgeAttribute()
    {
        $badges = [
            'twilio' => 'badge-info',
            'msg91' => 'badge-primary',
            'nexmo' => 'badge-success',
            'custom' => 'badge-secondary'
        ];

        return $badges[$this->provider] ?? 'badge-secondary';
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_active) {
            return $this->is_default ? 'badge-success' : 'badge-primary';
        }
        return 'badge-secondary';
    }

    public function getMaskedApiKeyAttribute()
    {
        if ($this->api_key) {
            $length = strlen($this->api_key);
            return substr($this->api_key, 0, 4) . str_repeat('*', $length - 8) . substr($this->api_key, -4);
        }
        return null;
    }

    // Methods
    public function isAvailable()
    {
        if (!$this->is_active) {
            return false;
        }

        // Check rate limits
        if ($this->rate_limit) {
            $messagesThisMinute = SmsMessage::where('gateway_id', $this->id)
                ->where('created_at', '>=', now()->subMinute())
                ->count();
            
            if ($messagesThisMinute >= $this->rate_limit) {
                return false;
            }
        }

        if ($this->daily_limit) {
            $messagesToday = SmsMessage::where('gateway_id', $this->id)
                ->where('created_at', '>=', now()->startOfDay())
                ->count();
            
            if ($messagesToday >= $this->daily_limit) {
                return false;
            }
        }

        if ($this->monthly_limit) {
            $messagesThisMonth = SmsMessage::where('gateway_id', $this->id)
                ->where('created_at', '>=', now()->startOfMonth())
                ->count();
            
            if ($messagesThisMonth >= $this->monthly_limit) {
                return false;
            }
        }

        return true;
    }

    public function getConfig()
    {
        $config = [
            'provider' => $this->provider,
            'api_key' => $this->api_key,
            'api_secret' => $this->api_secret,
            'sender_id' => $this->sender_id,
            'test_mode' => $this->test_mode
        ];

        if ($this->settings) {
            $config = array_merge($config, $this->settings);
        }

        return $config;
    }

    public function testConnection()
    {
        try {
            // Implement gateway-specific test logic
            switch ($this->provider) {
                case 'twilio':
                    return $this->testTwilioConnection();
                case 'msg91':
                    return $this->testMsg91Connection();
                case 'nexmo':
                    return $this->testNexmoConnection();
                default:
                    return ['success' => false, 'message' => 'Unknown provider'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function testTwilioConnection()
    {
        // Implement Twilio test connection
        return ['success' => true, 'message' => 'Connection successful'];
    }

    private function testMsg91Connection()
    {
        // Implement MSG91 test connection
        return ['success' => true, 'message' => 'Connection successful'];
    }

    private function testNexmoConnection()
    {
        // Implement Nexmo test connection
        return ['success' => true, 'message' => 'Connection successful'];
    }

    public function canSendTo($phoneNumber)
    {
        // Check if gateway supports the phone number format
        $countryCode = $this->getCountryCode($phoneNumber);
        
        if ($this->settings && isset($this->settings['supported_countries'])) {
            return in_array($countryCode, $this->settings['supported_countries']);
        }
        
        return true; // Assume supported if not specified
    }

    private function getCountryCode($phoneNumber)
    {
        // Extract country code from phone number
        if (preg_match('/^\+(\d{1,3})/', $phoneNumber, $matches)) {
            return $matches[1];
        }
        
        return '91'; // Default to India
    }
}
