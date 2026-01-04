<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TransportAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'vehicle_id',
        'route_id',
        'driver_id',
        'conductor_id',
        'assignment_date',
        'start_time',
        'end_time',
        'shift_type',
        'status',
        'is_active',
        'notes',
        'assigned_by',
        'assigned_at',
        'completed_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_DELAYED = 'delayed';

    const SHIFT_MORNING = 'morning';
    const SHIFT_AFTERNOON = 'afternoon';
    const SHIFT_EVENING = 'evening';
    const SHIFT_NIGHT = 'night';
    const SHIFT_FULL_DAY = 'full_day';

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(TransportVehicle::class, 'vehicle_id');
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
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
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('assignment_date', $date);
    }

    public function scopeByShift($query, $shift)
    {
        return $query->where('shift_type', $shift);
    }

    public function scopeByVehicle($query, $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    public function scopeByRoute($query, $routeId)
    {
        return $query->where('route_id', $routeId);
    }

    public function scopeByDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    public function scopeByConductor($query, $conductorId)
    {
        return $query->where('conductor_id', $conductorId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('assignment_date', '>=', now()->toDateString())
                    ->where('status', '!=', self::STATUS_COMPLETED);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('assignment_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('assignment_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('assignment_date', now()->month)
                    ->whereYear('assignment_date', now()->year);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'badge-light-warning',
            self::STATUS_ACTIVE => 'badge-light-success',
            self::STATUS_COMPLETED => 'badge-light-info',
            self::STATUS_CANCELLED => 'badge-light-danger',
            self::STATUS_DELAYED => 'badge-light-secondary'
        ];

        return $badges[$this->status] ?? 'badge-light-dark';
    }

    public function getShiftBadgeAttribute()
    {
        $badges = [
            self::SHIFT_MORNING => 'badge-light-primary',
            self::SHIFT_AFTERNOON => 'badge-light-success',
            self::SHIFT_EVENING => 'badge-light-warning',
            self::SHIFT_NIGHT => 'badge-light-dark',
            self::SHIFT_FULL_DAY => 'badge-light-info'
        ];

        return $badges[$this->shift_type] ?? 'badge-light-secondary';
    }

    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInHours($this->end_time);
        }
        return null;
    }

    public function getIsOverdueAttribute()
    {
        if ($this->status === self::STATUS_ACTIVE && $this->end_time) {
            return now()->isAfter($this->end_time);
        }
        return false;
    }

    public function getIsUpcomingAttribute()
    {
        return $this->assignment_date->isFuture() && $this->status === self::STATUS_PENDING;
    }

    // Mutators
    public function setAssignmentDateAttribute($value)
    {
        $this->attributes['assignment_date'] = Carbon::parse($value);
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::parse($value);
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = Carbon::parse($value);
    }

    // Methods
    public function activate()
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'assigned_at' => now(),
            'is_active' => true
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'is_active' => false
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'is_active' => false
        ]);
    }

    public function isConflicting()
    {
        return static::where('vehicle_id', $this->vehicle_id)
                    ->where('id', '!=', $this->id)
                    ->where('assignment_date', $this->assignment_date)
                    ->where('status', '!=', self::STATUS_CANCELLED)
                    ->where('status', '!=', self::STATUS_COMPLETED)
                    ->where(function($query) {
                        $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                              ->orWhereBetween('end_time', [$this->start_time, $this->end_time])
                              ->orWhere(function($q) {
                                  $q->where('start_time', '<=', $this->start_time)
                                    ->where('end_time', '>=', $this->end_time);
                              });
                    })
                    ->exists();
    }

    public function getConflictingAssignments()
    {
        return static::where('vehicle_id', $this->vehicle_id)
                    ->where('id', '!=', $this->id)
                    ->where('assignment_date', $this->assignment_date)
                    ->where('status', '!=', self::STATUS_CANCELLED)
                    ->where('status', '!=', self::STATUS_COMPLETED)
                    ->where(function($query) {
                        $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                              ->orWhereBetween('end_time', [$this->start_time, $this->end_time])
                              ->orWhere(function($q) {
                                  $q->where('start_time', '<=', $this->start_time)
                                    ->where('end_time', '>=', $this->end_time);
                              });
                    })
                    ->get();
    }
}
