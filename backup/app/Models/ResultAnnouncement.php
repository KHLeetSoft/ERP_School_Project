<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'description',
        'exam_id',
        'online_exam_id',
        'announcement_type',
        'status',
        'publish_at',
        'expires_at',
        'target_audience',
        'class_ids',
        'section_ids',
        'send_sms',
        'send_email',
        'send_push_notification',
        'notification_settings',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'publish_at' => 'datetime',
        'expires_at' => 'datetime',
        'target_audience' => 'array',
        'class_ids' => 'array',
        'section_ids' => 'array',
        'notification_settings' => 'array',
        'send_sms' => 'boolean',
        'send_email' => 'boolean',
        'send_push_notification' => 'boolean',
    ];

    /**
     * Get the school that owns this announcement.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the exam that this announcement is for.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the online exam that this announcement is for.
     */
    public function onlineExam(): BelongsTo
    {
        return $this->belongsTo(OnlineExam::class);
    }

    /**
     * Get the user who created this announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this announcement.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for published announcements.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where(function($q) {
                        $q->whereNull('publish_at')
                          ->orWhere('publish_at', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
    }

    /**
     * Scope for draft announcements.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for archived announcements.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Check if announcement is currently active.
     */
    public function isActive(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->publish_at && now() < $this->publish_at) {
            return false;
        }

        if ($this->expires_at && now() > $this->expires_at) {
            return false;
        }

        return true;
    }

    /**
     * Get target audience as readable text.
     */
    public function getTargetAudienceTextAttribute(): string
    {
        if (!$this->target_audience) {
            return 'All';
        }

        return implode(', ', array_map('ucfirst', $this->target_audience));
    }

    /**
     * Get announcement type as readable text.
     */
    public function getAnnouncementTypeTextAttribute(): string
    {
        return str_replace('_', ' ', ucfirst($this->announcement_type));
    }
}
