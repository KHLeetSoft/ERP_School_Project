<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TransportDriver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'user_id',
        'name',
        'license_number',
        'license_type',
        'license_expiry_date',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'date_of_joining',
        'experience_level',
        'years_of_experience',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'vehicle_id',
        'status',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'license_expiry_date' => 'date',
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'years_of_experience' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_ON_LEAVE = 'on_leave';

    const LICENSE_TYPE_LIGHT_MOTOR = 'light_motor';
    const LICENSE_TYPE_HEAVY_MOTOR = 'heavy_motor';
    const LICENSE_TYPE_COMMERCIAL = 'commercial';
    const LICENSE_TYPE_PASSENGER = 'passenger';
    const LICENSE_TYPE_SPECIAL = 'special';

    const EXPERIENCE_LEVEL_BEGINNER = 'beginner';
    const EXPERIENCE_LEVEL_INTERMEDIATE = 'intermediate';
    const EXPERIENCE_LEVEL_EXPERIENCED = 'experienced';
    const EXPERIENCE_LEVEL_EXPERT = 'expert';

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedVehicle()
    {
        return $this->belongsTo(TransportVehicle::class, 'vehicle_id');
    }

    public function assignments()
    {
        return $this->hasMany(TransportAssignment::class, 'driver_id', 'user_id');
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

    public function scopeByLicenseType($query, $licenseType)
    {
        return $query->where('license_type', $licenseType);
    }

    public function scopeByExperienceLevel($query, $experienceLevel)
    {
        return $query->where('experience_level', $experienceLevel);
    }

    public function scopeAssigned($query)
    {
        return $query->whereNotNull('vehicle_id');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('vehicle_id');
    }

    public function scopeExpiringLicenses($query, $days = 30)
    {
        return $query->where('license_expiry_date', '<=', now()->addDays($days))
                    ->where('license_expiry_date', '>', now());
    }

    public function scopeExpiredLicenses($query)
    {
        return $query->where('license_expiry_date', '<', now());
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        return [
            self::STATUS_ACTIVE => 'badge-success',
            self::STATUS_INACTIVE => 'badge-secondary',
            self::STATUS_SUSPENDED => 'badge-danger',
            self::STATUS_ON_LEAVE => 'badge-warning'
        ][$this->status] ?? 'badge-secondary';
    }

    public function getLicenseTypeLabelAttribute()
    {
        return [
            self::LICENSE_TYPE_LIGHT_MOTOR => 'Light Motor',
            self::LICENSE_TYPE_HEAVY_MOTOR => 'Heavy Motor',
            self::LICENSE_TYPE_COMMERCIAL => 'Commercial',
            self::LICENSE_TYPE_PASSENGER => 'Passenger',
            self::LICENSE_TYPE_SPECIAL => 'Special'
        ][$this->license_type] ?? 'Unknown';
    }

    public function getExperienceLevelLabelAttribute()
    {
        return [
            self::EXPERIENCE_LEVEL_BEGINNER => 'Beginner',
            self::EXPERIENCE_LEVEL_INTERMEDIATE => 'Intermediate',
            self::EXPERIENCE_LEVEL_EXPERIENCED => 'Experienced',
            self::EXPERIENCE_LEVEL_EXPERT => 'Expert'
        ][$this->experience_level] ?? 'Unknown';
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getYearsOfServiceAttribute()
    {
        return $this->date_of_joining ? $this->date_of_joining->diffInYears(now()) : 0;
    }

    public function getLicenseExpiryStatusAttribute()
    {
        if (!$this->license_expiry_date) {
            return 'unknown';
        }

        if ($this->license_expiry_date->isPast()) {
            return 'expired';
        }

        if ($this->license_expiry_date->diffInDays(now()) <= 30) {
            return 'expiring';
        }

        return 'valid';
    }

    public function getLicenseExpiryDaysAttribute()
    {
        return $this->license_expiry_date ? $this->license_expiry_date->diffInDays(now(), false) : null;
    }

    public function getIsAssignedAttribute()
    {
        return !is_null($this->vehicle_id);
    }

    public function getActiveAssignmentsCountAttribute()
    {
        return $this->assignments()
            ->whereIn('status', ['active', 'pending'])
            ->count();
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

    public function suspend()
    {
        $this->update(['status' => self::STATUS_SUSPENDED]);
        return $this;
    }

    public function putOnLeave()
    {
        $this->update(['status' => self::STATUS_ON_LEAVE]);
        return $this;
    }

    public function assignVehicle($vehicleId)
    {
        // Remove previous vehicle assignment
        if ($this->vehicle_id) {
            TransportVehicle::where('id', $this->vehicle_id)
                ->update(['assigned_driver_id' => null]);
        }

        // Assign new vehicle
        $this->update(['vehicle_id' => $vehicleId]);
        
        if ($vehicleId) {
            TransportVehicle::where('id', $vehicleId)
                ->update(['assigned_driver_id' => $this->id]);
        }

        return $this;
    }

    public function unassignVehicle()
    {
        if ($this->vehicle_id) {
            TransportVehicle::where('id', $this->vehicle_id)
                ->update(['assigned_driver_id' => null]);
        }

        $this->update(['vehicle_id' => null]);
        return $this;
    }

    public function hasActiveAssignments()
    {
        return $this->assignments()
            ->whereIn('status', ['active', 'pending'])
            ->exists();
    }

    public function canBeDeleted()
    {
        return !$this->hasActiveAssignments();
    }

    public function getLicenseExpiryWarning()
    {
        if (!$this->license_expiry_date) {
            return null;
        }

        $daysUntilExpiry = $this->license_expiry_date->diffInDays(now(), false);

        if ($daysUntilExpiry < 0) {
            return [
                'type' => 'danger',
                'message' => 'License expired ' . abs($daysUntilExpiry) . ' days ago',
                'days' => $daysUntilExpiry
            ];
        }

        if ($daysUntilExpiry <= 30) {
            return [
                'type' => 'warning',
                'message' => 'License expires in ' . $daysUntilExpiry . ' days',
                'days' => $daysUntilExpiry
            ];
        }

        return null;
    }

    public function getExperienceLevelColor()
    {
        return [
            self::EXPERIENCE_LEVEL_BEGINNER => 'secondary',
            self::EXPERIENCE_LEVEL_INTERMEDIATE => 'info',
            self::EXPERIENCE_LEVEL_EXPERIENCED => 'warning',
            self::EXPERIENCE_LEVEL_EXPERT => 'success'
        ][$this->experience_level] ?? 'secondary';
    }

    public function getLicenseTypeColor()
    {
        return [
            self::LICENSE_TYPE_LIGHT_MOTOR => 'info',
            self::LICENSE_TYPE_HEAVY_MOTOR => 'warning',
            self::LICENSE_TYPE_COMMERCIAL => 'primary',
            self::LICENSE_TYPE_PASSENGER => 'success',
            self::LICENSE_TYPE_SPECIAL => 'danger'
        ][$this->license_type] ?? 'secondary';
    }
}
