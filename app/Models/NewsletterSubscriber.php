<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'email',
        'first_name',
        'last_name',
        'phone',
        'status',
        'source',
        'subscribed_at',
        'unsubscribed_at',
        'last_email_sent_at',
        'email_count',
        'open_count',
        'click_count',
        'bounce_count',
        'complaint_count',
        'metadata',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'last_email_sent_at' => 'datetime',
        'metadata' => 'array'
    ];

    protected $dates = [
        'subscribed_at',
        'unsubscribed_at',
        'last_email_sent_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the school that owns the subscriber
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user who created the subscriber
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the subscriber
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get newsletters sent to this subscriber
     */
    public function newsletters()
    {
        return $this->belongsToMany(Newsletter::class, 'newsletter_subscriber_pivot', 'subscriber_id', 'newsletter_id')
            ->withPivot(['sent_at', 'opened_at', 'clicked_at', 'bounced_at', 'unsubscribed_at', 'status'])
            ->withTimestamps();
    }

    /**
     * Get subscriber analytics
     */
    public function analytics()
    {
        return $this->hasMany(NewsletterAnalytics::class, 'subscriber_id');
    }

    /**
     * Scope by school
     */
    public function scopeBySchool($query, $schoolId = null)
    {
        $schoolId = $schoolId ?? Auth::user()->school_id;
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope unsubscribed subscribers
     */
    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }

    /**
     * Scope by source
     */
    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        if ($this->full_name) {
            return $this->full_name;
        }
        return $this->email;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            'unsubscribed' => 'badge-danger',
            'bounced' => 'badge-warning',
            'complained' => 'badge-dark'
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    /**
     * Get source badge class
     */
    public function getSourceBadgeClassAttribute()
    {
        $classes = [
            'website' => 'badge-primary',
            'admin' => 'badge-info',
            'import' => 'badge-success',
            'api' => 'badge-warning',
            'manual' => 'badge-secondary'
        ];

        return $classes[$this->source] ?? 'badge-secondary';
    }

    /**
     * Get engagement rate
     */
    public function getEngagementRateAttribute()
    {
        if ($this->email_count == 0) return 0;
        return round((($this->open_count + $this->click_count) / $this->email_count) * 100, 2);
    }

    /**
     * Get open rate
     */
    public function getOpenRateAttribute()
    {
        if ($this->email_count == 0) return 0;
        return round(($this->open_count / $this->email_count) * 100, 2);
    }

    /**
     * Get click rate
     */
    public function getClickRateAttribute()
    {
        if ($this->email_count == 0) return 0;
        return round(($this->click_count / $this->email_count) * 100, 2);
    }

    /**
     * Check if subscriber is active
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    /**
     * Check if subscriber can receive emails
     */
    public function getCanReceiveEmailsAttribute()
    {
        return $this->status === 'active' && !$this->unsubscribed_at;
    }

    /**
     * Get formatted subscribed date
     */
    public function getFormattedSubscribedDateAttribute()
    {
        if (!$this->subscribed_at) return 'Not subscribed';
        return $this->subscribed_at->format('M d, Y \a\t g:i A');
    }

    /**
     * Get formatted unsubscribed date
     */
    public function getFormattedUnsubscribedDateAttribute()
    {
        if (!$this->unsubscribed_at) return 'Active subscriber';
        return $this->unsubscribed_at->format('M d, Y \a\t g:i A');
    }

    /**
     * Generate unsubscribe token
     */
    public function generateUnsubscribeToken()
    {
        $this->update([
            'metadata' => array_merge($this->metadata ?? [], [
                'unsubscribe_token' => Str::random(64)
            ])
        ]);
    }

    /**
     * Get unsubscribe token
     */
    public function getUnsubscribeTokenAttribute()
    {
        return $this->metadata['unsubscribe_token'] ?? null;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (Auth::check()) {
                $subscriber->created_by = Auth::id();
                $subscriber->updated_by = Auth::id();
                $subscriber->school_id = Auth::user()->school_id;
            }
            
            if (!$subscriber->subscribed_at) {
                $subscriber->subscribed_at = now();
            }
        });

        static::updating(function ($subscriber) {
            if (Auth::check()) {
                $subscriber->updated_by = Auth::id();
            }
        });
    }
}
