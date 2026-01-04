<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'parent_name',
        'contact_number',
        'email',
        'address',
        'class',
        'date',
        'status',
        'note',
        'admin_id',
        'counselor_id',
        'first_contacted_at',
    ];

    protected $casts = [
        'date' => 'date',
        'first_contacted_at' => 'datetime',
    ];

    // Relationship example for follow-ups (if implemented later)
    public function followUps()
    {
        return $this->hasMany(AdmissionEnquiryFollowUp::class);
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
} 