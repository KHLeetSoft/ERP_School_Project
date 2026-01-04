<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsRecipient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sms_message_id',
        'recipient_id',
        'recipient_type', // student, parent, staff
        'phone_number',
        'status', // pending, sent, delivered, failed
        'sent_at',
        'delivered_at',
        'failed_at',
        'failure_reason',
        'gateway_message_id',
        'gateway_response',
        'retry_count',
        'cost',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_at' => 'datetime',
        'gateway_response' => 'array'
    ];

    protected $dates = [
        'sent_at',
        'delivered_at',
        'failed_at'
    ];

    // Relationships
    public function smsMessage()
    {
        return $this->belongsTo(SmsMessage::class);
    }

    public function recipient()
    {
        switch ($this->recipient_type) {
            case 'student':
                return $this->belongsTo(Student::class, 'recipient_id');
            case 'parent':
                return $this->belongsTo(ParentDetail::class, 'recipient_id');
            case 'staff':
                return $this->belongsTo(Staff::class, 'recipient_id');
            default:
                return null;
        }
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByRecipientType($query, $type)
    {
        return $query->where('recipient_type', $type);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'sent' => 'badge-info',
            'delivered' => 'badge-success',
            'failed' => 'badge-danger'
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getRecipientNameAttribute()
    {
        if ($this->recipient) {
            switch ($this->recipient_type) {
                case 'student':
                    return $this->recipient->first_name . ' ' . $this->recipient->last_name;
                case 'parent':
                    return $this->recipient->father_name ?? $this->recipient->mother_name ?? 'Parent';
                case 'staff':
                    return $this->recipient->first_name . ' ' . $this->recipient->last_name;
                default:
                    return 'Unknown';
            }
        }
        
        return 'Unknown';
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

    public function canRetry()
    {
        return $this->status === 'failed' && $this->retry_count < 3;
    }
}
