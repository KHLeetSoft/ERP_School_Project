<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_detail_id',
        'student_id',
        'admin_id',
        'communication_type',
        'subject',
        'message',
        'status',
        'sent_at',
        'delivered_at',
        'read_at',
        'priority',
        'category',
        'response',
        'response_at',
        'communication_channel',
        'cost',
        'notes',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'response_at' => 'datetime',
        'cost' => 'decimal:2',
    ];

    public function parentDetail(): BelongsTo
    {
        return $this->belongsTo(ParentDetail::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('communication_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'sent' => 'badge-light-primary',
            'delivered' => 'badge-light-info',
            'read' => 'badge-light-success',
            'failed' => 'badge-light-danger',
        ];

        return $badges[$this->status] ?? 'badge-light-secondary';
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'badge-light-secondary',
            'normal' => 'badge-light-primary',
            'high' => 'badge-light-warning',
            'urgent' => 'badge-light-danger',
        ];

        return $badges[$this->priority] ?? 'badge-light-secondary';
    }

    public function getCommunicationTypeIconAttribute()
    {
        $icons = [
            'email' => 'bx bx-envelope',
            'sms' => 'bx bx-message-square',
            'phone' => 'bx bx-phone',
            'meeting' => 'bx bx-calendar',
            'letter' => 'bx bx-file',
        ];

        return $icons[$this->communication_type] ?? 'bx bx-message';
    }
}
