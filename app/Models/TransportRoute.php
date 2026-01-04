<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TransportRoute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'route_name',
        'route_number',
        'start_location',
        'end_location',
        'description',
        'total_distance',
        'estimated_duration',
        'vehicle_capacity',
        'current_occupancy',
        'route_type',
        'status',
        'is_featured',
        'stops',
        'schedule',
        'fare_structure',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'total_distance' => 'decimal:2',
        'estimated_duration' => 'integer',
        'vehicle_capacity' => 'integer',
        'current_occupancy' => 'integer',
        'is_featured' => 'boolean',
        'stops' => 'array',
        'schedule' => 'array',
        'fare_structure' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_SUSPENDED = 'suspended';

    const ROUTE_TYPE_REGULAR = 'regular';
    const ROUTE_TYPE_EXPRESS = 'express';
    const ROUTE_TYPE_SPECIAL = 'special';
    const ROUTE_TYPE_SCHOOL = 'school';
    const ROUTE_TYPE_COLLEGE = 'college';

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
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
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByRouteType($query, $routeType)
    {
        return $query->where('route_type', $routeType);
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        return [
            self::STATUS_ACTIVE => 'badge-success',
            self::STATUS_INACTIVE => 'badge-secondary',
            self::STATUS_MAINTENANCE => 'badge-warning',
            self::STATUS_SUSPENDED => 'badge-danger'
        ][$this->status] ?? 'badge-secondary';
    }

    public function getFormattedFareAttribute()
    {
        if ($this->fare_structure && isset($this->fare_structure['base_fare'])) {
            return '₹' . number_format($this->fare_structure['base_fare'], 2);
        }
        return '₹0.00';
    }

    public function getRouteDisplayNameAttribute()
    {
        return $this->route_name . ' (' . $this->route_number . ')';
    }

    public function getIsActiveAttribute()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getFormattedDistanceAttribute()
    {
        return $this->total_distance . ' km';
    }

    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->estimated_duration / 60);
        $minutes = $this->estimated_duration % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        return $minutes . 'm';
    }

    // Methods
    public function toggleStatus()
    {
        $newStatus = $this->status === self::STATUS_ACTIVE ? self::STATUS_INACTIVE : self::STATUS_ACTIVE;
        $this->update(['status' => $newStatus]);
        return $this;
    }

    public function activate()
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
        return $this;
    }

    public function deactivate()
    {
        $this->update(['status' => self::STATUS_INACTIVE]);
        return $this;
    }

    public function setMaintenance()
    {
        $this->update(['status' => self::STATUS_MAINTENANCE]);
        return $this;
    }

    public function suspend()
    {
        $this->update(['status' => self::STATUS_SUSPENDED]);
        return $this;
    }

    public function getAvailableCapacity()
    {
        return max(0, $this->vehicle_capacity - $this->current_occupancy);
    }

    public function isFull()
    {
        return $this->current_occupancy >= $this->vehicle_capacity;
    }
}


