<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TransportVehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'vehicle_number',
        'registration_number',
        'vehicle_type',
        'brand',
        'model',
        'year_of_manufacture',
        'seating_capacity',
        'current_occupancy',
        'fuel_type',
        'fuel_efficiency',
        'insurance_number',
        'insurance_expiry',
        'permit_number',
        'permit_expiry',
        'fitness_certificate_number',
        'fitness_expiry',
        'puc_certificate_number',
        'puc_expiry',
        'driver_id',
        'conductor_id',
        'assigned_route_id',
        'status',
        'is_active',
        'is_available',
        'last_maintenance_date',
        'next_maintenance_date',
        'total_distance_covered',
        'average_speed',
        'description',
        'features',
        'images',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'year_of_manufacture' => 'integer',
        'seating_capacity' => 'integer',
        'current_occupancy' => 'integer',
        'fuel_efficiency' => 'decimal:2',
        'insurance_expiry' => 'date',
        'permit_expiry' => 'date',
        'fitness_expiry' => 'date',
        'puc_expiry' => 'date',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'total_distance_covered' => 'decimal:2',
        'average_speed' => 'decimal:2',
        'features' => 'array',
        'images' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_REPAIR = 'repair';
    const STATUS_OFFLINE = 'offline';

    const VEHICLE_TYPE_BUS = 'bus';
    const VEHICLE_TYPE_MINIBUS = 'minibus';
    const VEHICLE_TYPE_VAN = 'van';
    const VEHICLE_TYPE_CAR = 'car';
    const VEHICLE_TYPE_TRUCK = 'truck';

    const FUEL_TYPE_PETROL = 'petrol';
    const FUEL_TYPE_DIESEL = 'diesel';
    const FUEL_TYPE_CNG = 'cng';
    const FUEL_TYPE_ELECTRIC = 'electric';
    const FUEL_TYPE_HYBRID = 'hybrid';

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function driver()
    {
        return $this->belongsTo(TransportDriver::class, 'driver_id');
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }

    public function assignedRoute()
    {
        return $this->belongsTo(TransportRoute::class, 'assigned_route_id');
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
        return $query->where('status', 'active')->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByVehicleType($query, $vehicleType)
    {
        return $query->where('vehicle_type', $vehicleType);
    }

    public function scopeByFuelType($query, $fuelType)
    {
        return $query->where('fuel_type', $fuelType);
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        return [
            self::STATUS_ACTIVE => 'badge-success',
            self::STATUS_INACTIVE => 'badge-secondary',
            self::STATUS_MAINTENANCE => 'badge-warning',
            self::STATUS_REPAIR => 'badge-danger',
            self::STATUS_OFFLINE => 'badge-dark'
        ][$this->status] ?? 'badge-secondary';
    }

    public function getVehicleDisplayNameAttribute()
    {
        return $this->brand . ' ' . $this->model . ' (' . $this->vehicle_number . ')';
    }

    public function getFormattedFuelEfficiencyAttribute()
    {
        return $this->fuel_efficiency . ' km/l';
    }

    public function getFormattedTotalDistanceAttribute()
    {
        return number_format($this->total_distance_covered, 2) . ' km';
    }

    public function getFormattedAverageSpeedAttribute()
    {
        return $this->average_speed . ' km/h';
    }

    public function getAvailableSeatsAttribute()
    {
        return max(0, $this->seating_capacity - $this->current_occupancy);
    }

    public function getOccupancyPercentageAttribute()
    {
        if ($this->seating_capacity == 0) return 0;
        return round(($this->current_occupancy / $this->seating_capacity) * 100, 1);
    }

    public function getIsFullAttribute()
    {
        return $this->current_occupancy >= $this->seating_capacity;
    }

    public function getMaintenanceStatusAttribute()
    {
        if (!$this->next_maintenance_date) return 'unknown';
        
        $daysUntilMaintenance = Carbon::now()->diffInDays($this->next_maintenance_date, false);
        
        if ($daysUntilMaintenance < 0) return 'overdue';
        if ($daysUntilMaintenance <= 7) return 'urgent';
        if ($daysUntilMaintenance <= 30) return 'warning';
        return 'good';
    }

    public function getMaintenanceStatusBadgeClassAttribute()
    {
        return [
            'overdue' => 'badge-danger',
            'urgent' => 'badge-warning',
            'warning' => 'badge-info',
            'good' => 'badge-success',
            'unknown' => 'badge-secondary'
        ][$this->maintenance_status] ?? 'badge-secondary';
    }

    // Methods
    public function toggleStatus()
    {
        $newStatus = $this->status === self::STATUS_ACTIVE ? self::STATUS_INACTIVE : self::STATUS_ACTIVE;
        $this->update([
            'status' => $newStatus,
            'is_active' => $newStatus === self::STATUS_ACTIVE
        ]);
        return $this;
    }

    public function activate()
    {
        $this->update(['status' => self::STATUS_ACTIVE, 'is_active' => true]);
        return $this;
    }

    public function deactivate()
    {
        $this->update(['status' => self::STATUS_INACTIVE, 'is_active' => false]);
        return $this;
    }

    public function setMaintenance()
    {
        $this->update(['status' => self::STATUS_MAINTENANCE, 'is_active' => false, 'is_available' => false]);
        return $this;
    }

    public function setRepair()
    {
        $this->update(['status' => self::STATUS_REPAIR, 'is_active' => false, 'is_available' => false]);
        return $this;
    }

    public function setOffline()
    {
        $this->update(['status' => self::STATUS_OFFLINE, 'is_active' => false, 'is_available' => false]);
        return $this;
    }

    public function assignDriver($driverId)
    {
        $this->update(['driver_id' => $driverId]);
        return $this;
    }

    public function assignConductor($conductorId)
    {
        $this->update(['conductor_id' => $conductorId]);
        return $this;
    }

    public function assignRoute($routeId)
    {
        $this->update(['assigned_route_id' => $routeId]);
        return $this;
    }

    public function updateOccupancy($occupancy)
    {
        $this->update(['current_occupancy' => max(0, min($occupancy, $this->seating_capacity))]);
        return $this;
    }

    public function addDistance($distance)
    {
        $this->update(['total_distance_covered' => $this->total_distance_covered + $distance]);
        return $this;
    }

    public function isInsuranceExpired()
    {
        return $this->insurance_expiry && $this->insurance_expiry->isPast();
    }

    public function isPermitExpired()
    {
        return $this->permit_expiry && $this->permit_expiry->isPast();
    }

    public function isFitnessExpired()
    {
        return $this->fitness_expiry && $this->fitness_expiry->isPast();
    }

    public function isPucExpired()
    {
        return $this->puc_expiry && $this->puc_expiry->isPast();
    }

    public function hasExpiredDocuments()
    {
        return $this->isInsuranceExpired() || $this->isPermitExpired() || 
               $this->isFitnessExpired() || $this->isPucExpired();
    }
}


