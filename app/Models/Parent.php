<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParentDetails extends Model
{
    use HasFactory;

    protected $table = 'parent_details';

    protected $fillable = [
        'user_id',
        'school_id',
        'primary_contact_name',
        'father_name',
        'mother_name',
        'guardian_name',
        'phone_primary',
        'phone_secondary',
        'email_primary',
        'email_secondary',
        'address',
        'occupation_father',
        'occupation_mother',
        'income_range',
        'emergency_contact_name',
        'emergency_contact_phone',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_detail_id', 'student_id')
                    ->withPivot('relation', 'notes')
                    ->withTimestamps();
    }

    public function communications(): HasMany
    {
        return $this->hasMany(ParentCommunication::class, 'parent_detail_id');
    }

    public function portalAccesses(): HasMany
    {
        return $this->hasMany(ParentPortalAccess::class, 'parent_detail_id');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->primary_contact_name ?? $this->father_name ?? $this->mother_name ?? 'N/A';
    }

    public function getPrimaryPhoneAttribute(): string
    {
        return $this->phone_primary ?? 'N/A';
    }

    public function getPrimaryEmailAttribute(): string
    {
        return $this->email_primary ?? 'N/A';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            default => 'badge-secondary',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            default => 'Unknown',
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getChildrenCount(): int
    {
        return $this->students()->count();
    }

    public function getActiveChildrenCount(): int
    {
        return $this->students()->where('status', true)->count();
    }
}
