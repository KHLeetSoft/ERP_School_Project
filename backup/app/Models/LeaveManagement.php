<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class LeaveManagement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id', 'staff_id', 'leave_type', 'start_date', 'end_date', 'total_days',
        'reason', 'status', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at',
        'rejection_reason', 'half_day', 'half_day_type', 'emergency_contact',
        'emergency_contact_phone', 'address_during_leave', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'total_days' => 'decimal:1',
        'half_day' => 'boolean',
        'half_day_type' => 'string'
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getLeavePeriodAttribute(): string
    {
        $start = $this->start_date->format('d M Y');
        $end = $this->end_date->format('d M Y');
        
        if ($this->start_date->isSameDay($this->end_date)) {
            return $start . ($this->half_day ? ' (' . ucfirst($this->half_day_type) . ' Half Day)' : '');
        }
        
        return $start . ' to ' . $end;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            'cancelled' => '<span class="badge bg-secondary">Cancelled</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }

    public function getLeaveTypeLabelAttribute(): string
    {
        return match($this->leave_type) {
            'casual' => 'Casual Leave',
            'sick' => 'Sick Leave',
            'annual' => 'Annual Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'bereavement' => 'Bereavement Leave',
            'study' => 'Study Leave',
            'other' => 'Other Leave',
            default => ucfirst($this->leave_type)
        };
    }

    public function getDurationAttribute(): string
    {
        if ($this->start_date->isSameDay($this->end_date)) {
            if ($this->half_day) {
                return 'Half Day (' . ucfirst($this->half_day_type) . ')';
            }
            return '1 Day';
        }
        
        $days = $this->start_date->diffInDays($this->end_date) + 1;
        return $days . ' Day' . ($days > 1 ? 's' : '');
    }

    public function getIsOverlappingAttribute(): bool
    {
        return $this->start_date->isPast() && $this->end_date->isPast();
    }

    public function getIsCurrentAttribute(): bool
    {
        $now = Carbon::now();
        return $this->start_date->lte($now) && $this->end_date->gte($now);
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_date->isFuture();
    }

    // Scopes
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    public function scopeByLeaveType($query, $leaveType)
    {
        return $query->where('leave_type', $leaveType);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        return $query->where('status', 'approved')
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'approved')
                    ->where('start_date', '>', Carbon::now());
    }

    public function scopePast($query)
    {
        return $query->where('end_date', '<', Carbon::now());
    }

    // Methods
    public function calculateTotalDays(): float
    {
        if ($this->start_date->isSameDay($this->end_date)) {
            return $this->half_day ? 0.5 : 1.0;
        }
        
        $days = $this->start_date->diffInDays($this->end_date) + 1;
        return (float) $days;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeRejected(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    public function approve($userId, $remarks = null): bool
    {
        if (!$this->canBeApproved()) {
            return false;
        }

        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
            'rejection_reason' => null
        ]);

        return true;
    }

    public function reject($userId, $reason): bool
    {
        if (!$this->canBeRejected()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'rejected_by' => $userId,
            'rejected_at' => now(),
            'rejection_reason' => $reason
        ]);

        return true;
    }

    public function cancel($userId): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->update([
            'status' => 'cancelled',
            'updated_by' => $userId
        ]);

        return true;
    }

    public function overlapsWith($startDate, $endDate): bool
    {
        return $this->start_date->lte($endDate) && $this->end_date->gte($startDate);
    }
}
