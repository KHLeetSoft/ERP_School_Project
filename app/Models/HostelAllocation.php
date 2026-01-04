<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HostelAllocation extends Model
{
    use HasFactory;

    protected $table = 'hostel_allocations';

    protected $fillable = [
        'school_id',
        'student_id',
        'hostel_id',
        'room_id',
        'bed_no',
        'join_date',
        'leave_date',
        'status',
        'monthly_fee',
        'security_deposit',
        'remarks',
    ];

    protected $casts = [
        'join_date' => 'date',
        'leave_date' => 'date',
        'monthly_fee' => 'decimal:2',
        'security_deposit' => 'decimal:2',
    ];

    // Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_LEFT = 'left';

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class, 'hostel_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HostelRoom::class, 'room_id');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(HostelFee::class, 'allocation_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(HostelAttendance::class, 'allocation_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeLeft($query)
    {
        return $query->where('status', self::STATUS_LEFT);
    }

    // Methods
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getDurationInDays()
    {
        if (!$this->leave_date) {
            return now()->diffInDays($this->join_date);
        }
        
        return $this->leave_date->diffInDays($this->join_date);
    }

    public function getTotalFeesPaid()
    {
        return $this->fees()->where('status', 'paid')->sum('amount');
    }

    public function getPendingFees()
    {
        return $this->fees()->where('status', 'pending')->sum('amount');
    }

    public function getAttendancePercentage($startDate = null, $endDate = null)
    {
        $query = $this->attendances();
        
        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }
        
        $totalDays = $query->count();
        $presentDays = $query->where('status', 'present')->count();
        
        return $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
    }
}
