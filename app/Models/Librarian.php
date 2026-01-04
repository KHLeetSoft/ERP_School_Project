<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Librarian extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_id',
        'designation',
        'department',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'joining_date',
        'profile_image',
        'bio',
        'specializations',
        'certifications',
        'emergency_contact',
        'bank_details',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    protected $casts = [
        'specializations' => 'array',
        'certifications' => 'array',
        'emergency_contact' => 'array',
        'bank_details' => 'array',
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return $this->date_of_birth->age;
        }
        return null;
    }

    public function getExperienceAttribute()
    {
        if ($this->joining_date) {
            return $this->joining_date->diffInYears(now());
        }
        return null;
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function updateLastLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);
    }

    // Static methods
    public static function getByUser($userId)
    {
        return static::where('user_id', $userId)->first();
    }

    public static function getActiveLibrarians()
    {
        return static::with('user')->active()->get();
    }
}