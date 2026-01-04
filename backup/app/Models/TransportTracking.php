<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TransportTracking extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transport_tracking';

    protected $fillable = [
        'school_id',
        'vehicle_id',
        'route_id',
        'driver_id',
        'tracking_date',
        'tracking_time',
        'latitude',
        'longitude',
        'speed',
        'status',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tracking_date' => 'date',
        'tracking_time' => 'datetime:H:i:s',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'speed' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Constants
    const STATUS_ON_TIME = 'on_time';
    const STATUS_DELAYED = 'delayed';
    const STATUS_EARLY = 'early';
    const STATUS_STOPPED = 'stopped';
    const STATUS_MOVING = 'moving';

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
        return $this->belongsTo(TransportDriver::class, 'driver_id');
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

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
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

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tracking_date', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tracking_date', [$startDate, $endDate]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tracking_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('tracking_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tracking_date', now()->month)
                    ->whereYear('tracking_date', now()->year);
    }

    public function scopeOnTime($query)
    {
        return $query->where('status', self::STATUS_ON_TIME);
    }

    public function scopeDelayed($query)
    {
        return $query->where('status', self::STATUS_DELAYED);
    }

    public function scopeMoving($query)
    {
        return $query->where('status', self::STATUS_MOVING);
    }

    public function scopeStopped($query)
    {
        return $query->where('status', self::STATUS_STOPPED);
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'on_time' => 'bg-success',
            'delayed' => 'bg-danger',
            'early' => 'bg-warning',
            'stopped' => 'bg-secondary',
            'moving' => 'bg-primary',
            default => 'bg-secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'on_time' => 'On Time',
            'delayed' => 'Delayed',
            'early' => 'Early',
            'stopped' => 'Stopped',
            'moving' => 'Moving',
            default => 'Unknown'
        };
    }

    public function getFormattedSpeedAttribute()
    {
        return $this->speed ? $this->speed . ' km/h' : 'N/A';
    }

    public function getFormattedLocationAttribute()
    {
        return $this->latitude && $this->longitude 
            ? number_format($this->latitude, 6) . ', ' . number_format($this->longitude, 6)
            : 'N/A';
    }

    public function getFormattedDateTimeAttribute()
    {
        return $this->tracking_date->format('M d, Y') . ' at ' . $this->tracking_time->format('H:i:s');
    }

    public function getGoogleMapsUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    // Methods
    public function isOnTime()
    {
        return $this->status === self::STATUS_ON_TIME;
    }

    public function isDelayed()
    {
        return $this->status === self::STATUS_DELAYED;
    }

    public function isMoving()
    {
        return $this->status === self::STATUS_MOVING;
    }

    public function isStopped()
    {
        return $this->status === self::STATUS_STOPPED;
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = 6371; // Earth's radius in kilometers

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function getBearingTo($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $lonDelta = $lonTo - $lonFrom;

        $y = sin($lonDelta) * cos($latTo);
        $x = cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta);

        $bearing = atan2($y, $x);
        $bearing = rad2deg($bearing);
        $bearing = fmod(($bearing + 360), 360);

        return $bearing;
    }

    public function getCompassDirection($bearing = null)
    {
        if ($bearing === null) {
            return 'N/A';
        }

        $directions = [
            'N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE',
            'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'
        ];

        $index = round($bearing / 22.5) % 16;
        return $directions[$index];
    }
}
