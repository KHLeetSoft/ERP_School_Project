<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class SmsMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_type', // student, parent, staff, class, section, all
        'recipient_ids', // JSON array of recipient IDs
        'message',
        'status', // draft, sent, delivered, failed
        'priority', // low, normal, high, urgent
        'scheduled_at',
        'sent_at',
        'delivered_at',
        'failed_at',
        'failure_reason',
        'sms_count', // Number of SMS parts
        'cost',
        'gateway_response',
        'template_id',
        'category', // notification, reminder, alert, marketing
        'requires_confirmation',
        'confirmed_at',
        'confirmation_code',
        'expires_at',
        'retry_count',
        'max_retries',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'recipient_ids' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'expires_at' => 'datetime',
        'gateway_response' => 'array'
    ];

    protected $dates = [
        'scheduled_at',
        'sent_at',
        'delivered_at',
        'failed_at',
        'confirmed_at',
        'expires_at'
    ];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->hasMany(SmsRecipient::class);
    }

    public function template()
    {
        return $this->belongsTo(SmsTemplate::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeRequiresConfirmation($query)
    {
        return $query->where('requires_confirmation', true);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'badge-secondary',
            'scheduled' => 'badge-info',
            'sent' => 'badge-primary',
            'delivered' => 'badge-success',
            'failed' => 'badge-danger'
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'badge-light',
            'normal' => 'badge-info',
            'high' => 'badge-warning',
            'urgent' => 'badge-danger'
        ];

        return $badges[$this->priority] ?? 'badge-secondary';
    }

    public function getCategoryBadgeAttribute()
    {
        $badges = [
            'notification' => 'badge-primary',
            'reminder' => 'badge-info',
            'alert' => 'badge-warning',
            'marketing' => 'badge-success'
        ];

        return $badges[$this->category] ?? 'badge-secondary';
    }

    public function getIsScheduledAttribute()
    {
        return $this->status === 'scheduled' && $this->scheduled_at && $this->scheduled_at->isFuture();
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getCanRetryAttribute()
    {
        return $this->status === 'failed' && $this->retry_count < $this->max_retries;
    }

    // Methods
    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failure_reason' => $reason
        ]);
    }

    public function incrementRetryCount()
    {
        $this->increment('retry_count');
    }

    public function calculateCost()
    {
        // Calculate cost based on SMS count and rate
        $rate = config('sms.rate_per_sms', 0.01);
        $this->cost = $this->sms_count * $rate;
        $this->save();
    }

    public function getDeliveryRate()
    {
        if ($this->status === 'sent') {
            $totalRecipients = $this->recipients()->count();
            $deliveredRecipients = $this->recipients()->where('status', 'delivered')->count();
            
            return $totalRecipients > 0 ? round(($deliveredRecipients / $totalRecipients) * 100, 2) : 0;
        }
        
        return 0;
    }
}
