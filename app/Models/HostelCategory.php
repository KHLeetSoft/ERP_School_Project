<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HostelCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'slug',
        'description',
        'monthly_fee',
        'security_deposit',
        'capacity',
        'available_rooms',
        'facilities',
        'rules',
        'status',
        'image',
        'images',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'monthly_fee' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'facilities' => 'array',
        'rules' => 'array',
        'images' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_MAINTENANCE = 'maintenance';

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

    public function rooms()
    {
        return $this->hasMany(HostelRoom::class, 'category_id');
    }

    public function allocations()
    {
        return $this->hasMany(HostelAllocation::class, 'category_id');
    }

    // Scopes
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_rooms', '>', 0);
    }

    public function scopeFull($query)
    {
        return $query->where('available_rooms', 0);
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'badge-success',
            self::STATUS_INACTIVE => 'badge-secondary',
            self::STATUS_MAINTENANCE => 'badge-warning',
            default => 'badge-secondary',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_MAINTENANCE => 'Maintenance',
            default => 'Unknown',
        };
    }

    public function getFormattedMonthlyFeeAttribute()
    {
        return '₹' . number_format($this->monthly_fee, 2);
    }

    public function getFormattedSecurityDepositAttribute()
    {
        return '₹' . number_format($this->security_deposit, 2);
    }

    public function getOccupancyRateAttribute()
    {
        if ($this->capacity == 0) return 0;
        return round((($this->capacity - $this->available_rooms) / $this->capacity) * 100, 2);
    }

    public function getIsAvailableAttribute()
    {
        return $this->available_rooms > 0 && $this->status === self::STATUS_ACTIVE;
    }

    public function getMainImageAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        if ($this->images && count($this->images) > 0) {
            return asset('storage/' . $this->images[0]);
        }
        
        return asset('images/default-hostel.jpg');
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Methods
    public function activate()
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    public function deactivate()
    {
        $this->update(['status' => self::STATUS_INACTIVE]);
    }

    public function setMaintenance()
    {
        $this->update(['status' => self::STATUS_MAINTENANCE]);
    }

    public function updateAvailableRooms()
    {
        $totalRooms = $this->rooms()->count();
        $occupiedRooms = $this->allocations()->where('status', 'active')->count();
        $this->update(['available_rooms' => max(0, $totalRooms - $occupiedRooms)]);
    }

    public function canAccommodate($students = 1)
    {
        return $this->available_rooms >= $students && $this->status === self::STATUS_ACTIVE;
    }

    public function getTotalRevenue()
    {
        return $this->allocations()
            ->where('status', 'active')
            ->sum('monthly_fee');
    }

    public function getFacilitiesList()
    {
        return $this->facilities ? implode(', ', $this->facilities) : 'No facilities listed';
    }

    public function getRulesList()
    {
        return $this->rules ? implode(', ', $this->rules) : 'No rules specified';
    }
}