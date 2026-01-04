<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Newsletter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'title',
        'subject',
        'content',
        'template_id',
        'status',
        'scheduled_at',
        'sent_at',
        'total_subscribers',
        'sent_count',
        'opened_count',
        'clicked_count',
        'bounced_count',
        'unsubscribed_count',
        'is_draft',
        'is_featured',
        'category',
        'tags',
        'metadata',
        'created_by',
        'updated_by',
        'sent_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'tags' => 'array',
        'metadata' => 'array',
        'is_draft' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected $dates = [
        'scheduled_at',
        'sent_at',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';

    // Category constants
    const CATEGORY_NEWSLETTER = 'newsletter';
    const CATEGORY_ANNOUNCEMENT = 'announcement';
    const CATEGORY_PROMOTIONAL = 'promotional';
    const CATEGORY_EDUCATIONAL = 'educational';
    const CATEGORY_EVENT = 'event';
    const CATEGORY_OTHER = 'other';

    /**
     * Get the school that owns the newsletter.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the template used for this newsletter.
     */
    public function template()
    {
        return $this->belongsTo(NewsletterTemplate::class, 'template_id');
    }

    /**
     * Get the user who created the newsletter.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the newsletter.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who sent the newsletter.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Get the subscribers for this newsletter.
     */
    public function subscribers()
    {
        return $this->belongsToMany(NewsletterSubscriber::class, 'newsletter_subscriber_pivot', 'newsletter_id', 'subscriber_id')
                    ->withPivot([
                        'status', 'sent_at', 'delivered_at', 'opened_at', 
                        'clicked_at', 'bounced_at', 'unsubscribed_at', 
                        'failed_at', 'tracking_data'
                    ])
                    ->withTimestamps();
    }

    /**
     * Get the analytics for this newsletter.
     */
    public function analytics()
    {
        return $this->hasMany(NewsletterAnalytics::class);
    }

    /**
     * Scope a query to only include newsletters for a specific school.
     */
    public function scopeBySchool(Builder $query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope a query to only include active newsletters.
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', '!=', self::STATUS_DRAFT);
    }

    /**
     * Scope a query to only include draft newsletters.
     */
    public function scopeDrafts(Builder $query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope a query to only include scheduled newsletters.
     */
    public function scopeScheduled(Builder $query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    /**
     * Scope a query to only include sent newsletters.
     */
    public function scopeSent(Builder $query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    /**
     * Scope a query to only include newsletters by category.
     */
    public function scopeByCategory(Builder $query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include featured newsletters.
     */
    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the status badge class for display.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'badge-secondary',
            self::STATUS_SCHEDULED => 'badge-info',
            self::STATUS_SENDING => 'badge-warning',
            self::STATUS_SENT => 'badge-success',
            self::STATUS_CANCELLED => 'badge-danger',
            self::STATUS_FAILED => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Get the category badge class for display.
     */
    public function getCategoryBadgeClassAttribute()
    {
        return match($this->category) {
            self::CATEGORY_NEWSLETTER => 'badge-primary',
            self::CATEGORY_ANNOUNCEMENT => 'badge-info',
            self::CATEGORY_PROMOTIONAL => 'badge-warning',
            self::CATEGORY_EDUCATIONAL => 'badge-success',
            self::CATEGORY_EVENT => 'badge-danger',
            self::CATEGORY_OTHER => 'badge-secondary',
            default => 'badge-secondary',
        };
    }

    /**
     * Get the open rate percentage.
     */
    public function getOpenRateAttribute()
    {
        if ($this->sent_count == 0) {
            return 0;
        }
        return round(($this->opened_count / $this->sent_count) * 100, 2);
    }

    /**
     * Get the click rate percentage.
     */
    public function getClickRateAttribute()
    {
        if ($this->sent_count == 0) {
            return 0;
        }
        return round(($this->clicked_count / $this->sent_count) * 100, 2);
    }

    /**
     * Get the bounce rate percentage.
     */
    public function getBounceRateAttribute()
    {
        if ($this->sent_count == 0) {
            return 0;
        }
        return round(($this->bounced_count / $this->sent_count) * 100, 2);
    }

    /**
     * Get the unsubscribe rate percentage.
     */
    public function getUnsubscribeRateAttribute()
    {
        if ($this->sent_count == 0) {
            return 0;
        }
        return round(($this->unsubscribed_count / $this->sent_count) * 100, 2);
    }

    /**
     * Check if the newsletter can be sent.
     */
    public function getCanBeSentAttribute()
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_SCHEDULED]);
    }

    /**
     * Check if the newsletter can be scheduled.
     */
    public function getCanBeScheduledAttribute()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if the newsletter can be cancelled.
     */
    public function getCanBeCancelledAttribute()
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    /**
     * Get the formatted scheduled date.
     */
    public function getFormattedScheduledDateAttribute()
    {
        return $this->scheduled_at ? $this->scheduled_at->format('M d, Y \a\t g:i A') : 'Not scheduled';
    }

    /**
     * Get the formatted sent date.
     */
    public function getFormattedSentDateAttribute()
    {
        return $this->sent_at ? $this->sent_at->format('M d, Y \a\t g:i A') : 'Not sent yet';
    }

    /**
     * Get the tags as a comma-separated string.
     */
    public function getTagsStringAttribute()
    {
        if (!$this->tags || !is_array($this->tags)) {
            return '';
        }
        return implode(', ', $this->tags);
    }

    /**
     * Set the tags from a comma-separated string.
     */
    public function setTagsStringAttribute($value)
    {
        if (is_string($value)) {
            $this->tags = array_map('trim', explode(',', $value));
        } else {
            $this->tags = $value;
        }
    }

    /**
     * Get the status text for display.
     */
    public function getStatusTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Get the category text for display.
     */
    public function getCategoryTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->category));
    }

    /**
     * Boot method to set default values.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($newsletter) {
            if (auth()->check()) {
                $newsletter->created_by = auth()->id();
                $newsletter->updated_by = auth()->id();
            }
        });

        static::updating(function ($newsletter) {
            if (auth()->check()) {
                $newsletter->updated_by = auth()->id();
            }
        });
    }
}
