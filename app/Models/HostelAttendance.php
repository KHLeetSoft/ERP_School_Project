<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostelAttendance extends Model
{
    use HasFactory;

    protected $table = 'hostel_attendances';

    protected $fillable = [
        'school_id',
        'allocation_id',
        'date',
        'status',
        'check_in_time',
        'check_out_time',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
    ];

    // Constants
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';
    const STATUS_LEAVE = 'leave';

    // Relationships
    public function allocation(): BelongsTo
    {
        return $this->belongsTo(HostelAllocation::class, 'allocation_id');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', self::STATUS_ABSENT);
    }

    public function scopeLate($query)
    {
        return $query->where('status', self::STATUS_LATE);
    }

    public function scopeLeave($query)
    {
        return $query->where('status', self::STATUS_LEAVE);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('date', $month)->whereYear('date', $year);
    }

    // Methods
    public function isPresent()
    {
        return $this->status === self::STATUS_PRESENT;
    }

    public function isAbsent()
    {
        return $this->status === self::STATUS_ABSENT;
    }

    public function isLate()
    {
        return $this->status === self::STATUS_LATE;
    }

    public function isOnLeave()
    {
        return $this->status === self::STATUS_LEAVE;
    }

    public function getDuration()
    {
        if ($this->check_in_time && $this->check_out_time) {
            return $this->check_in_time->diffInHours($this->check_out_time);
        }
        
        return null;
    }

    public function getFormattedDate()
    {
        return $this->date->format('d M Y');
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_PRESENT => 'badge-success',
            self::STATUS_ABSENT => 'badge-danger',
            self::STATUS_LATE => 'badge-warning',
            self::STATUS_LEAVE => 'badge-info',
            default => 'badge-secondary'
        };
    }
}
