<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NewsletterAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'newsletter_id',
        'subscriber_id',
        'event_type',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'operating_system',
        'country',
        'city',
        'region',
        'event_data',
        'occurred_at',
    ];

    protected $casts = [
        'event_data' => 'array',
        'occurred_at' => 'datetime',
    ];

    // Event type constants
    const EVENT_OPEN = 'open';
    const EVENT_CLICK = 'click';
    const EVENT_BOUNCE = 'bounce';
    const EVENT_UNSUBSCRIBE = 'unsubscribe';
    const EVENT_COMPLAINT = 'complaint';
    const EVENT_DELIVERY = 'delivery';
    const EVENT_FAILURE = 'failure';

    // Device type constants
    const DEVICE_DESKTOP = 'desktop';
    const DEVICE_MOBILE = 'mobile';
    const DEVICE_TABLET = 'tablet';
    const DEVICE_UNKNOWN = 'unknown';

    /**
     * Get the newsletter that this analytics belongs to.
     */
    public function newsletter()
    {
        return $this->belongsTo(Newsletter::class);
    }

    /**
     * Get the subscriber that this analytics belongs to.
     */
    public function subscriber()
    {
        return $this->belongsTo(NewsletterSubscriber::class);
    }

    /**
     * Scope a query to only include analytics for a specific newsletter.
     */
    public function scopeByNewsletter(Builder $query, $newsletterId)
    {
        return $query->where('newsletter_id', $newsletterId);
    }

    /**
     * Scope a query to only include analytics for a specific subscriber.
     */
    public function scopeBySubscriber(Builder $query, $subscriberId)
    {
        return $query->where('subscriber_id', $subscriberId);
    }

    /**
     * Scope a query to only include analytics by event type.
     */
    public function scopeByEventType(Builder $query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope a query to only include analytics by device type.
     */
    public function scopeByDeviceType(Builder $query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    /**
     * Scope a query to only include analytics by country.
     */
    public function scopeByCountry(Builder $query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope a query to only include analytics within a date range.
     */
    public function scopeByDateRange(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('occurred_at', [$startDate, $endDate]);
    }

    /**
     * Get the event type text for display.
     */
    public function getEventTypeTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->event_type));
    }

    /**
     * Get the device type text for display.
     */
    public function getDeviceTypeTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->device_type));
    }

    /**
     * Get the event type badge class for display.
     */
    public function getEventTypeBadgeClassAttribute()
    {
        return match($this->event_type) {
            self::EVENT_OPEN => 'badge-success',
            self::EVENT_CLICK => 'badge-info',
            self::EVENT_BOUNCE => 'badge-warning',
            self::EVENT_UNSUBSCRIBE => 'badge-danger',
            self::EVENT_COMPLAINT => 'badge-danger',
            self::EVENT_DELIVERY => 'badge-primary',
            self::EVENT_FAILURE => 'badge-secondary',
            default => 'badge-secondary',
        };
    }

    /**
     * Get the device type badge class for display.
     */
    public function getDeviceTypeBadgeClassAttribute()
    {
        return match($this->device_type) {
            self::DEVICE_DESKTOP => 'badge-primary',
            self::DEVICE_MOBILE => 'badge-info',
            self::DEVICE_TABLET => 'badge-warning',
            self::DEVICE_UNKNOWN => 'badge-secondary',
            default => 'badge-secondary',
        };
    }

    /**
     * Get the formatted occurred date.
     */
    public function getFormattedOccurredDateAttribute()
    {
        return $this->occurred_at ? $this->occurred_at->format('M d, Y \a\t g:i A') : 'Unknown';
    }

    /**
     * Get the location string.
     */
    public function getLocationStringAttribute()
    {
        $parts = [];
        if ($this->city) $parts[] = $this->city;
        if ($this->region) $parts[] = $this->region;
        if ($this->country) $parts[] = $this->country;
        
        return !empty($parts) ? implode(', ', $parts) : 'Unknown location';
    }

    /**
     * Get the user agent summary.
     */
    public function getUserAgentSummaryAttribute()
    {
        if (!$this->browser && !$this->operating_system) {
            return 'Unknown';
        }
        
        $parts = [];
        if ($this->browser) $parts[] = $this->browser;
        if ($this->operating_system) $parts[] = $this->operating_system;
        
        return implode(' on ', $parts);
    }

    /**
     * Boot method to set default values.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($analytics) {
            if (!$analytics->occurred_at) {
                $analytics->occurred_at = now();
            }
        });
    }
}
