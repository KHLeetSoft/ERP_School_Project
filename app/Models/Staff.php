<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id', 'employee_id', 'first_name', 'last_name', 'email', 'phone',
        'date_of_birth', 'gender', 'address', 'city', 'state', 'country', 'postal_code',
        'designation', 'department', 'hire_date', 'contract_end_date', 'salary',
        'employment_type', 'status', 'emergency_contact_name', 'emergency_contact_phone',
        'emergency_contact_relationship', 'bank_name', 'bank_account_number', 'ifsc_code', 
        'pan_number', 'aadhar_number', 'photo', 'class_id', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'contract_end_date' => 'date',
        'salary' => 'decimal:2',
        'status' => 'string'
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(StaffAttendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(StaffLeave::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(StaffSalary::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(StaffDocument::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFormattedSalaryAttribute(): string
    {
        return '₹' . number_format($this->salary, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status 
            ? '<span class="badge bg-success">Active</span>' 
            : '<span class="badge bg-danger">Inactive</span>';
    }

    public function getEmploymentTypeLabelAttribute(): string
    {
        return match($this->employment_type) {
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'contract' => 'Contract',
            'intern' => 'Intern',
            default => ucfirst(str_replace('_', ' ', $this->employment_type))
        };
    }

    public function getGenderLabelAttribute(): string
    {
        return match($this->gender) {
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
            default => ucfirst($this->gender)
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByDesignation($query, $designation)
    {
        return $query->where('designation', $designation);
    }

    public function scopeByEmploymentType($query, $employmentType)
    {
        return $query->where('employment_type', $employmentType);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('employee_id', 'like', "%{$search}%")
              ->orWhere('designation', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%");
        });
    }

    // Methods
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }

    public function getExperienceAttribute(): string
    {
        if (!$this->hire_date) return 'N/A';
        
        $years = $this->hire_date->diffInYears(now());
        $months = $this->hire_date->diffInMonths(now()) % 12;
        
        if ($years > 0) {
            return $months > 0 ? "{$years} years, {$months} months" : "{$years} years";
        }
        
        return "{$months} months";
    }

    public function isOnLeave($date = null): bool
    {
        $date = $date ?? now();
        return $this->leaves()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('status', 'approved')
            ->exists();
    }

    public function getAttendancePercentage($month = null): float
    {
        $month = $month ?? now()->format('Y-m');
        $totalDays = $this->attendances()
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->count();
            
        $presentDays = $this->attendances()
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->where('status', 'present')
            ->count();
            
        return $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
    }
}
