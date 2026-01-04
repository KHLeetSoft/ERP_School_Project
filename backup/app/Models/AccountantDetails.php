<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountantDetails extends Model
{
    use HasFactory;

    protected $table = 'accountant_details';

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'qualification',
        'experience_years',
        'salary',
        'joining_date',
        'status',
        'bank_account',
        'ifsc_code',
        'pan_number',
        'aadhar_number',
        'emergency_contact',
        'emergency_phone',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'experience_years' => 'integer',
        'salary' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user->name ?? 'N/A';
    }

    public function getEmailAttribute()
    {
        return $this->user->email ?? 'N/A';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'success',
            'inactive' => 'secondary',
            'suspended' => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getExperienceTextAttribute()
    {
        if ($this->experience_years == 0) {
            return 'Fresh Graduate';
        } elseif ($this->experience_years == 1) {
            return '1 year experience';
        } else {
            return $this->experience_years . ' years experience';
        }
    }
}
